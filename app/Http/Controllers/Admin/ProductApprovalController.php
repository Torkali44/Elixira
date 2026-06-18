<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Support\SiteBroadcastService;
use App\Support\UserNotifier;
use Illuminate\Http\Request;

class ProductApprovalController extends Controller
{
    public function index()
    {
        $pendingItems = Item::with(['category', 'brandModel'])->where('status', 'pending')->latest()->paginate(20);

        return view('admin.items.pending', compact('pendingItems'));
    }

    public function approve(Item $item)
    {
        $item->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        try {
            $vendor = $item->vendor;
            if ($vendor) {
                UserNotifier::send($vendor->id, 'product_approved', [
                    'product' => $item->name,
                ], route('vendor.items.index'));
            }

            $brandName = $item->brandModel?->name;
            if ($brandName) {
                app(SiteBroadcastService::class)->broadcastIfAllowed(
                    'brand_new_product',
                    ['brand' => $brandName, 'product' => $item->local_name],
                    route('menu.show', $item)
                );
            } else {
                app(SiteBroadcastService::class)->broadcastIfAllowed(
                    'new_product',
                    ['product' => $item->local_name],
                    route('menu.show', $item)
                );
            }
        } catch (\Throwable $e) {
            \Log::error('Product approval notification failed: '.$e->getMessage());
        }

        return redirect()->route('admin.items.index')->with('success', 'Product approved successfully.');
    }

    public function reject(Request $request, Item $item)
    {
        $request->validate([
            'rejection_reason' => 'required_if:reject_type,notes|nullable|string|max:1000',
            'reject_type' => 'required|in:final,notes',
        ]);

        $status = $request->reject_type === 'notes' ? 'rejected_with_notes' : 'rejected';

        $item->update([
            'status' => $status,
            'rejection_reason' => $request->rejection_reason,
        ]);

        try {
            $vendor = $item->vendor;
            if ($vendor) {
                UserNotifier::send($vendor->id, 'product_rejected', [
                    'product' => $item->name,
                    'reason' => $request->rejection_reason ?? '',
                ], route('vendor.items.edit', $item->id));
            }
        } catch (\Throwable $e) {
            \Log::error('Product rejection notification failed: '.$e->getMessage());
        }

        return redirect()->route('admin.items.index')->with('success', 'Product rejected successfully.');
    }
}
