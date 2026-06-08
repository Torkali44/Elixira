<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Notification;
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
                Notification::create([
                    'user_id' => $vendor->id,
                    'title' => 'Product Approved',
                    'message' => 'Your product "'.$item->name.'" has been approved by the admin and is now live.',
                    'url' => route('vendor.items.index'),
                ]);
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
                $reason = $request->rejection_reason ? ' Reason: '.$request->rejection_reason : '';
                Notification::create([
                    'user_id' => $vendor->id,
                    'title' => 'Product Rejected',
                    'message' => 'Your product "'.$item->name.'" has been rejected.'.$reason,
                    'url' => route('vendor.items.edit', $item->id),
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error('Product rejection notification failed: '.$e->getMessage());
        }

        return redirect()->route('admin.items.index')->with('success', 'Product rejected successfully.');
    }
}
