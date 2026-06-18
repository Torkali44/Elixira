<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Tag;
use App\Support\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::with('vendorProfile.user')
            ->withCount('items')
            ->latest()
            ->paginate(15);

        return view('admin.brands.index', compact('brands'));
    }

    public function edit(Brand $brand)
    {
        $brand->load('tags');

        return view('admin.brands.edit', compact('brand') + $this->tagFormData($brand));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
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

        $logoPath = $brand->logo;
        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        // Update Brand slug if name changed
        if ($brand->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $original = $slug;
            $count = 1;
            while (Brand::where('slug', $slug)->where('id', '!=', $brand->id)->exists()) {
                $slug = $original.'-'.$count++;
            }
            $brand->slug = $slug;
        }

        $brand->update([
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

        // Sync with VendorProfile
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

        return redirect()->route('admin.brands.index')->with('success', 'Brand and vendor profile updated successfully.');
    }

    /**
     * @return array{selectedTags: string, tagSuggestions: list<string>}
     */
    private function tagFormData(Brand $brand): array
    {
        return [
            'selectedTags' => $brand->tagNames(),
            'tagSuggestions' => Tag::query()->orderBy('name')->pluck('name')->all(),
        ];
    }
}
