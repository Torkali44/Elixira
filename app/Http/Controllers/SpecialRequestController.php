<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpecialRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $validated['user_id'] = auth()->id();

        \App\Models\SpecialRequest::create($validated);

        return response()->json(['success' => true, 'message' => 'Request submitted successfully.']);
    }

    public function index()
    {
        $specialRequests = \App\Models\SpecialRequest::with(['item', 'user'])->latest()->paginate(15);
        return view('admin.special_requests.index', compact('specialRequests'));
    }

    public function updateStatus(Request $request, \App\Models\SpecialRequest $specialRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,notified',
        ]);

        $specialRequest->update(['status' => $validated['status']]);

        return back()->with('success', 'Status updated successfully.');
    }
}
