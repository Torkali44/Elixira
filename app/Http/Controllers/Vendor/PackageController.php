<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePackageRequest;
use App\Http\Requests\Admin\UpdatePackageRequest;
use App\Models\Item;
use App\Models\Package;
use App\Models\Tag;
use App\Support\AdminNotifier;
use App\Support\PackagePricingService;
use App\Support\TagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PackageController extends Controller
{
    private function getBrandId(): int
    {
        $vendorProfile = auth()->user()->vendorProfile;
        if (! $vendorProfile || ! $vendorProfile->brand) {
            abort(403, 'Brand not found.');
        }

        return (int) $vendorProfile->brand->id;
    }

    public function index(): View
    {
        $packages = Package::query()
            ->withCount('items')
            ->where('brand_id', $this->getBrandId())
            ->latest()
            ->paginate(20);

        return view('vendor.packages.index', compact('packages'));
    }

    public function create(): View
    {
        $items = Item::query()
            ->where('brand_id', $this->getBrandId())
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        return view('vendor.packages.create', compact('items') + $this->tagFormData());
    }

    public function store(StorePackageRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request);
        $data['brand_id'] = $this->getBrandId();
        $data['is_featured'] = false;
        $data['is_active'] = false;
        $data['status'] = 'pending';

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        $package = Package::create($data);
        $package->load('brand');
        $package->items()->sync($this->itemQuantitiesFromRequest($request));
        app(PackagePricingService::class)->syncCountryPrices($package, $request->input('country_prices', []));
        app(TagService::class)->syncFromInput($package, $request->input('tags'));

        AdminNotifier::notifyAll('vendor_package_submitted', [
            'package' => $package->local_name,
            'brand' => $package->brand?->name ?? auth()->user()->name,
        ], route('admin.packages.index', ['status' => 'new']));

        return redirect()->route('vendor.packages.index')->with('success', __('admin.packages_page.submitted_for_approval'));
    }

    public function edit(Package $package): View
    {
        $this->authorizePackage($package);
        $package->load(['items', 'countryPrices', 'tags']);
        $items = Item::query()
            ->where('brand_id', $this->getBrandId())
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        return view('vendor.packages.edit', compact('package', 'items') + $this->tagFormData($package));
    }

    public function update(UpdatePackageRequest $request, Package $package): RedirectResponse
    {
        $this->authorizePackage($package);
        $data = $this->prepareData($request);
        $resubmitted = $package->status === 'rejected_with_notes';

        if (in_array($package->status, ['rejected_with_notes', 'rejected'], true)) {
            $data['status'] = 'pending';
            $data['rejection_reason'] = null;
            $data['is_active'] = false;
        }

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

        if ($resubmitted) {
            AdminNotifier::notifyAll('vendor_package_submitted', [
                'package' => $package->local_name,
                'brand' => $package->brand?->name ?? auth()->user()->name,
            ], route('admin.packages.index', ['status' => 'new']));
        }

        return redirect()->route('vendor.packages.index')->with('success', $resubmitted
            ? __('admin.packages_page.resubmitted_for_approval')
            : __('admin.packages_page.updated'));
    }

    public function destroy(Package $package): RedirectResponse
    {
        $this->authorizePackage($package);

        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }

        $package->delete();

        return redirect()->route('vendor.packages.index')->with('success', __('admin.packages_page.deleted'));
    }

    private function authorizePackage(Package $package): void
    {
        if ((int) $package->brand_id !== $this->getBrandId()) {
            abort(403);
        }
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
     * @return array<string, mixed>
     */
    private function prepareData(StorePackageRequest|UpdatePackageRequest $request): array
    {
        $data = $request->validated();
        $data['name'] = $data['name_en'] ?? $data['name'] ?? '';
        $data['price'] = $data['price'] ?? 0;

        return $data;
    }

    /**
     * @return array<int, array{quantity: int}>
     */
    private function itemQuantitiesFromRequest(StorePackageRequest|UpdatePackageRequest $request): array
    {
        $sync = [];
        $brandId = $this->getBrandId();

        foreach ($request->input('package_items', []) as $itemId => $row) {
            if (empty($row['selected'])) {
                continue;
            }

            $item = Item::query()->where('id', $itemId)->where('brand_id', $brandId)->first();
            if (! $item) {
                continue;
            }

            $sync[(int) $itemId] = ['quantity' => max(1, (int) ($row['quantity'] ?? 1))];
        }

        return $sync;
    }
}
