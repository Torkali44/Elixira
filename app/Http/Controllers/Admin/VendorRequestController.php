<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorProfile;

class VendorRequestController extends Controller
{
    public function index()
    {
        $requests = VendorProfile::with('user')->whereIn('status', ['pending', 'approved', 'rejected'])->latest()->paginate(15);
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
            'status' => 'required|in:approved,rejected'
        ]);

        $vendorProfile->status = $request->status;
        $vendorProfile->save();

        if ($request->status === 'approved') {
            $user = $vendorProfile->user;
            $user->role = 'vendor';
            $user->save();
        } elseif ($request->status === 'rejected') {
            $user = $vendorProfile->user;
            if ($user->role === 'vendor') {
                $user->role = 'user';
                $user->save();
            }
        }

        return redirect()->route('admin.vendors.requests.index')->with('success', 'Vendor request ' . $request->status . ' successfully.');
    }
}
