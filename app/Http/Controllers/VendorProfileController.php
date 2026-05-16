<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorProfile;

class VendorProfileController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        if ($user->vendorProfile) {
            if ($user->vendorProfile->status === 'pending') {
                return redirect()->route('vendor.pending');
            } elseif ($user->vendorProfile->status === 'approved') {
                return redirect()->route('dashboard')->with('status', 'You are already an approved vendor.');
            } elseif ($user->vendorProfile->status === 'rejected') {
                return redirect()->route('dashboard')->with('error', 'Your vendor application has been permanently rejected.');
            } elseif ($user->vendorProfile->status === 'rejected_with_notes' && !request()->has('edit')) {
                return redirect()->route('vendor.rejected');
            }
        }
        
        return view('vendor.onboarding', [
            'vendorProfile' => $user->vendorProfile ?? new VendorProfile()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'brand_logo' => 'nullable|image|max:2048',
            'brand_description' => 'required|string|max:1000',
            'instagram_link' => 'nullable|url|max:255',
            'tiktok_link' => 'nullable|url|max:255',
            'snapchat_link' => 'nullable|url|max:255',
            'store_link' => 'nullable|url|max:255',
            'store_link_description' => 'nullable|string|max:500',
            'service_countries' => 'required|array',
            'product_types' => 'required|array',
            'verification_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'action' => 'required|in:draft,submit',
        ]);

        $user = auth()->user();
        
        if ($user->vendorProfile && !in_array($user->vendorProfile->status, ['draft', 'rejected_with_notes'])) {
            return redirect()->route('vendor.pending')->with('error', 'You have already submitted a request.');
        }

        $profile = $user->vendorProfile ?? new VendorProfile();
        $profile->user_id = $user->id;
        $profile->brand_name = $request->brand_name;
        $profile->brand_description = $request->brand_description;
        $profile->instagram_link = $request->instagram_link;
        $profile->tiktok_link = $request->tiktok_link;
        $profile->snapchat_link = $request->snapchat_link;
        $profile->store_link = $request->store_link;
        $profile->store_link_description = $request->store_link_description;
        $profile->service_countries = $request->service_countries;
        $profile->product_types = $request->product_types;
        $profile->payment_method = 'cash_on_delivery';

        if ($request->hasFile('brand_logo')) {
            if ($profile->brand_logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($profile->brand_logo);
            }
            $profile->brand_logo = $request->file('brand_logo')->store('vendor_logos', 'public');
        }

        if ($request->hasFile('verification_document')) {
            if ($profile->verification_document) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($profile->verification_document);
            }
            $profile->verification_document = $request->file('verification_document')->store('vendor_docs', 'public');
        }

        if ($request->action === 'submit') {
            $profile->status = 'pending';
        } else {
            $profile->status = 'draft';
        }

        $profile->save();

        if ($profile->status === 'pending') {
            return redirect()->route('vendor.pending')->with('status', 'Your vendor request has been submitted successfully.');
        }

        return redirect()->route('vendor.onboarding')->with('status', 'Draft saved successfully.');
    }

    public function pending()
    {
        $user = auth()->user();
        if (!$user->vendorProfile || $user->vendorProfile->status !== 'pending') {
            return redirect()->route('vendor.onboarding');
        }

        return view('vendor.pending');
    }

    public function rejected()
    {
        $user = auth()->user();
        if (!$user->vendorProfile || $user->vendorProfile->status !== 'rejected_with_notes') {
            return redirect()->route('vendor.onboarding');
        }

        return view('vendor.rejected', [
            'vendorProfile' => $user->vendorProfile
        ]);
    }
}
