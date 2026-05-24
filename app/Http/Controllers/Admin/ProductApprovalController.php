<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

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
            'rejection_reason' => null
        ]);

        return back()->with('success', 'Product approved successfully.');
    }

    public function reject(Request $request, Item $item)
    {
        $request->validate([
            'rejection_reason' => 'required_if:reject_type,notes|nullable|string|max:1000',
            'reject_type' => 'required|in:final,notes'
        ]);

        $status = $request->reject_type === 'notes' ? 'rejected_with_notes' : 'rejected';

        $item->update([
            'status' => $status,
            'rejection_reason' => $request->rejection_reason
        ]);

        return back()->with('success', 'Product rejected successfully.');
    }
}
