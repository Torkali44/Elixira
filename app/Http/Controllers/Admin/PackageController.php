<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePackageRequest;
use App\Http\Requests\Admin\UpdatePackageRequest;
use App\Models\Item;
use App\Models\Package;
use App\Models\Tag;
use App\Support\PackagePricingService;
use App\Support\SiteBroadcastService;
use App\Support\TagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $status = request('status');
        $query = Package::query()->with('brand')->withCount('items')->latest();

        if ($status === 'new') {
            $query->where('status', 'pending')
                ->where('created_at', '>=', now()->subHours(24));
        } elseif ($status === 'pending') {
            $query->where('status', 'pending')
                ->where('created_at', '<', now()->subHours(24));
        } elseif ($status === 'approved') {
            $query->where('status', 'approved');
        } elseif ($status === 'rejected') {
            $query->whereIn('status', ['rejected', 'rejected_with_notes']);
        }

        $packages = $query->paginate(20)->withQueryString();

        return view('admin.packages.index', compact('packages'));
    }

    public function create(): View
    {
        $items = Item::query()->publiclyVisible()->orderBy('name')->get();

        return view('admin.packages.create', compact('items') + $this->tagFormData());
    }

    public function store(StorePackageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');
        $data['status'] = 'approved';
        $data = $this->normalizeBilingualFields($data);
        $data['price'] = $data['price'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        $package = Package::create($data);
        $package->items()->sync($this->itemQuantitiesFromRequest($request));
        app(PackagePricingService::class)->syncCountryPrices($package, $request->input('country_prices', []));
        app(TagService::class)->syncFromInput($package, $request->input('tags'));

        app(SiteBroadcastService::class)->broadcastIfAllowed(
            'new_package',
            ['package' => $package->local_name],
            route('packages.show', $package)
        );

        return redirect()->route('admin.packages.index')->with('success', __('admin.packages_page.created'));
    }

    public function edit(Package $package): View
    {
        $package->load(['items', 'countryPrices', 'tags']);
        $items = Item::query()->publiclyVisible()->orderBy('name')->get();

        return view('admin.packages.edit', compact('package', 'items') + $this->tagFormData($package));
    }

    public function update(UpdatePackageRequest $request, Package $package): RedirectResponse
    {
        $data = $request->validated();
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');
        $data = $this->normalizeBilingualFields($data);
        $data['price'] = $data['price'] ?? $package->price;

        if ($request->hasFile('image')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $data['image'] = $request->file('image')->store('packages', 'public');
        } else {
            unset($data['image']);
        }

        $package->update($data);
        $package->items()->sync($this->itemQuantitiesFromRequest($request));
        app(PackagePricingService::class)->syncCountryPrices($package, $request->input('country_prices', []));
        app(TagService::class)->syncFromInput($package, $request->input('tags'));

        return redirect()->route('admin.packages.index')->with('success', __('admin.packages_page.updated'));
    }

    public function destroy(Package $package): RedirectResponse
    {
        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }

        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', __('admin.packages_page.deleted'));
    }

    /**
     * @return array<string, mixed>
     */
    private function tagFormData(?Package $package = null): array
    {
        return [
            'selectedTags' => $package?->tagNames() ?? '',
            'tagSuggestions' => Tag::query()->orderBy('name')->pluck('name')->all(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function normalizeBilingualFields(array $data): array
    {
        $data['name'] = $data['name_en'] ?? $data['name'] ?? '';

        return $data;
    }

    /**
     * @return array<int, array{quantity: int}>
     */
    private function itemQuantitiesFromRequest(StorePackageRequest|UpdatePackageRequest $request): array
    {
        $sync = [];

        foreach ($request->input('package_items', []) as $itemId => $row) {
            if (empty($row['selected'])) {
                continue;
            }

            $sync[(int) $itemId] = ['quantity' => max(1, (int) ($row['quantity'] ?? 1))];
        }

        return $sync;
    }
}
