<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Support\SiteBroadcastService;
use App\Support\UserNotifier;
use Illuminate\Http\Request;

class PackageApprovalController extends Controller
{
    public function approve(Package $package)
    {
        $package->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'is_active' => true,
        ]);

        try {
            $vendor = $package->brand?->vendorProfile?->user;
            if ($vendor) {
                UserNotifier::send($vendor->id, 'package_approved', [
                    'package' => $package->local_name,
                ], route('vendor.packages.index'));
            }

            app(SiteBroadcastService::class)->broadcastIfAllowed(
                'new_package',
                ['package' => $package->local_name],
                route('packages.show', $package)
            );
        } catch (\Throwable $e) {
            \Log::error('Package approval notification failed: '.$e->getMessage());
        }

        return redirect()->route('admin.packages.index')->with('success', __('admin.packages_page.approved'));
    }

    public function reject(Request $request, Package $package)
    {
        $request->validate([
            'rejection_reason' => 'required_if:reject_type,notes|nullable|string|max:1000',
            'reject_type' => 'required|in:final,notes',
        ]);

        $status = $request->reject_type === 'notes' ? 'rejected_with_notes' : 'rejected';

        $package->update([
            'status' => $status,
            'rejection_reason' => $request->rejection_reason,
            'is_active' => false,
        ]);

        try {
            $vendor = $package->brand?->vendorProfile?->user;
            if ($vendor) {
                UserNotifier::send($vendor->id, 'package_rejected', [
                    'package' => $package->local_name,
                    'reason' => $request->rejection_reason ?? '',
                ], route('vendor.packages.edit', $package));
            }
        } catch (\Throwable $e) {
            \Log::error('Package rejection notification failed: '.$e->getMessage());
        }

        return redirect()->route('admin.packages.index')->with('success', __('admin.packages_page.rejected'));
    }
}
