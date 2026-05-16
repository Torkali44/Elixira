<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorProfile;
use App\Models\Brand;

class VendorRequestController extends Controller
{
    public function index()
    {
        $requests = VendorProfile::with('user')->whereIn('status', ['pending', 'approved', 'rejected', 'rejected_with_notes'])->latest()->paginate(15);
        return view('admin.vendors.requests.index', compact('requests'));
    }

    public function show(VendorProfile $vendorProfile)
    {
        $vendorProfile->load('user');
        return view('admin.vendors.requests.show', compact('vendorProfile'));
    }

    public function update(Request $request, VendorProfile $vendorProfile)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,rejected_with_notes',
            'rejection_reason' => 'required_if:status,rejected_with_notes|nullable|string|max:1000',
        ]);

        $vendorProfile->status = $request->status;
        if ($request->status === 'rejected_with_notes') {
            $vendorProfile->rejection_reason = $request->rejection_reason;
        } else {
            $vendorProfile->rejection_reason = null; // Clear if approved or permanently rejected
        }
        $vendorProfile->save();

        if ($request->status === 'approved') {
            $user = $vendorProfile->user;
            $user->role = 'vendor';
            $user->save();

            // Create Brand from VendorProfile data if not exists
            if (!$vendorProfile->brand) {
                Brand::create([
                    'vendor_profile_id' => $vendorProfile->id,
                    'name' => $vendorProfile->brand_name,
                    'logo' => $vendorProfile->brand_logo,
                    'description' => $vendorProfile->brand_description,
                    'instagram_link' => $vendorProfile->instagram_link,
                    'tiktok_link' => $vendorProfile->tiktok_link,
                    'snapchat_link' => $vendorProfile->snapchat_link,
                    'store_link' => $vendorProfile->store_link,
                    'store_link_description' => $vendorProfile->store_link_description,
                    'service_countries' => $vendorProfile->service_countries,
                    'is_active' => true,
                ]);
            }
        } elseif (in_array($request->status, ['rejected', 'rejected_with_notes'])) {
            $user = $vendorProfile->user;
            if ($user->role === 'vendor') {
                $user->role = 'user';
                $user->save();
            }
        }

        return redirect()->route('admin.vendors.requests.index')->with('success', 'Vendor request ' . str_replace('_', ' ', $request->status) . ' successfully.');
    }
}

