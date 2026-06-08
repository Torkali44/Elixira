<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $vendorProfile = $user->vendorProfile;

        if (! $vendorProfile || ! $vendorProfile->brand) {
            return redirect()->route('vendor.dashboard')->with('error', 'Brand not found or your profile is not approved yet.');
        }

        $brand = $vendorProfile->brand;

        return view('vendor.brand.edit', compact('brand'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $vendorProfile = $user->vendorProfile;

        if (! $vendorProfile || ! $vendorProfile->brand) {
            return redirect()->route('vendor.dashboard')->with('error', 'Brand not found or profile not approved.');
        }

        $brand = $vendorProfile->brand;

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
        ]);

        // Logo Upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $logoPath = $request->file('logo')->store('brands', 'public');
            $brand->logo = $logoPath;
            $vendorProfile->brand_logo = $logoPath;
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

        // Update Brand Model
        $brand->name = $validated['name'];
        $brand->description = $validated['description'];
        $brand->instagram_link = $validated['instagram_link'];
        $brand->tiktok_link = $validated['tiktok_link'];
        $brand->snapchat_link = $validated['snapchat_link'];
        $brand->twitter_link = $validated['twitter_link'];
        $brand->store_link = $validated['store_link'];
        $brand->store_link_description = $validated['store_link_description'];
        $brand->service_countries = $validated['service_countries'];
        $brand->save();

        // Sync with VendorProfile model to ensure data consistency
        $vendorProfile->brand_name = $validated['name'];
        $vendorProfile->brand_description = $validated['description'];
        $vendorProfile->instagram_link = $validated['instagram_link'];
        $vendorProfile->tiktok_link = $validated['tiktok_link'];
        $vendorProfile->snapchat_link = $validated['snapchat_link'];
        $vendorProfile->store_link = $validated['store_link'];
        $vendorProfile->store_link_description = $validated['store_link_description'];
        $vendorProfile->service_countries = $validated['service_countries'];
        $vendorProfile->save();

        return redirect()->route('vendor.brand.edit')->with('success', 'Your Brand Profile has been successfully updated and synchronized.');
    }
}
