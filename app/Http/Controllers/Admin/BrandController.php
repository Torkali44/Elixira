<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\VendorProfile;
use App\Support\TagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::with('vendorProfile.user')
            ->withCount('items')
            ->latest()
            ->paginate(15);

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        return view('admin.brands.create', $this->brandFormData());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateBrand($request);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        $brand = Brand::create([
            'vendor_profile_id' => $validated['vendor_profile_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name']),
            'description' => $validated['description'],
            'logo' => $logoPath,
            'instagram_link' => $validated['instagram_link'],
            'tiktok_link' => $validated['tiktok_link'],
            'snapchat_link' => $validated['snapchat_link'],
            'twitter_link' => $validated['twitter_link'],
            'store_link' => $validated['store_link'],
            'store_link_description' => $validated['store_link_description'],
            'service_countries' => $validated['service_countries'],
            'is_active' => $request->has('is_active'),
        ]);

        app(TagService::class)->syncFromInput($brand, $request->input('tags'));

        return redirect()->route('admin.brands.index')->with('success', __('admin.brands_page.created'));
    }

    public function edit(Brand $brand): View
    {
        $brand->load('tags');

        return view('admin.brands.edit', compact('brand') + $this->brandFormData($brand));
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $this->validateBrand($request, $brand);

        $logoPath = $brand->logo;
        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        if ($brand->name !== $validated['name']) {
            $brand->slug = $this->uniqueSlug($validated['name'], $brand->id);
        }

        $brand->update([
            'vendor_profile_id' => $validated['vendor_profile_id'] ?? null,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'logo' => $logoPath,
            'instagram_link' => $validated['instagram_link'],
            'tiktok_link' => $validated['tiktok_link'],
            'snapchat_link' => $validated['snapchat_link'],
            'twitter_link' => $validated['twitter_link'],
            'store_link' => $validated['store_link'],
            'store_link_description' => $validated['store_link_description'],
            'service_countries' => $validated['service_countries'],
            'is_active' => $request->has('is_active'),
        ]);

        app(TagService::class)->syncFromInput($brand, $request->input('tags'));

        $vendorProfile = $brand->vendorProfile;
        if ($vendorProfile) {
            $vendorProfile->update([
                'brand_name' => $validated['name'],
                'brand_logo' => $logoPath,
                'brand_description' => $validated['description'],
                'instagram_link' => $validated['instagram_link'],
                'tiktok_link' => $validated['tiktok_link'],
                'snapchat_link' => $validated['snapchat_link'],
                'store_link' => $validated['store_link'],
                'store_link_description' => $validated['store_link_description'],
                'service_countries' => $validated['service_countries'],
            ]);
        }

        return redirect()->route('admin.brands.index')->with('success', __('admin.brands_page.updated'));
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->items()->exists() || \App\Models\Package::where('brand_id', $brand->id)->exists()) {
            return redirect()->back()->with('error', __('admin.brands_page.cannot_delete_with_products'));
        }

        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', __('admin.brands_page.deleted'));
    }

    /**
     * @return array<string, mixed>
     */
    private function validateBrand(Request $request, ?Brand $brand = null): array
    {
        return $request->validate([
            'vendor_profile_id' => [
                'nullable',
                'exists:vendor_profiles,id',
                Rule::unique('brands', 'vendor_profile_id')->ignore($brand?->id),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'instagram_link' => 'nullable|url|max:255',
            'tiktok_link' => 'nullable|url|max:255',
            'snapchat_link' => 'nullable|url|max:255',
            'twitter_link' => 'nullable|url|max:255',
            'store_link' => 'nullable|url|max:255',
            'store_link_description' => 'nullable|string|max:500',
            'service_countries' => 'required|array|min:1',
            'service_countries.*' => 'string|in:Saudi Arabia,UAE',
            'tags' => 'nullable|string|max:1000',
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (Brand::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $original.'-'.$count++;
        }

        return $slug;
    }

    /**
     * @return array{selectedTags: string, tagSuggestions: list<string>, vendorProfiles: \Illuminate\Support\Collection}
     */
    private function brandFormData(?Brand $brand = null): array
    {
        $assignedProfileIds = Brand::query()
            ->when($brand, fn ($query) => $query->where('id', '!=', $brand->id))
            ->whereNotNull('vendor_profile_id')
            ->pluck('vendor_profile_id');

        $vendorProfiles = VendorProfile::with('user')
            ->where(function ($query) use ($brand, $assignedProfileIds) {
                $query->whereNotIn('id', $assignedProfileIds);
                if ($brand?->vendor_profile_id) {
                    $query->orWhere('id', $brand->vendor_profile_id);
                }
            })
            ->orderBy('brand_name')
            ->get();

        return [
            'selectedTags' => $brand?->tagNames() ?? '',
            'tagSuggestions' => Tag::query()->orderBy('name')->pluck('name')->all(),
            'vendorProfiles' => $vendorProfiles,
        ];
    }
}
