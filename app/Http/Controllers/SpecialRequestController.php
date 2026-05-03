<?php

namespace App\Http\Controllers;

use App\Models\SpecialItemOffer;
use App\Models\SpecialRequest;
use App\Models\User;
use Illuminate\Http\Request;

class SpecialRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['phone'] = $this->normalizePhone($validated['phone']);
        $validated['email'] = isset($validated['email']) ? strtolower(trim((string) $validated['email'])) : null;

        SpecialRequest::create($validated);

        return response()->json(['success' => true, 'message' => 'Request submitted successfully.']);
    }

    public function index()
    {
        session(['special_requests_last_viewed_at' => now()]);
        $specialRequests = SpecialRequest::with(['item', 'user', 'offers' => function ($query) {
            $query->where('is_active', true)->latest();
        }])->latest()->paginate(15);

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

    public function assignOffer(Request $request, SpecialRequest $specialRequest)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:20',
        ]);

        if (!$specialRequest->item) {
            return back()->with('error', 'This request is no longer linked to an existing product.');
        }

        $normalizedPhone = $specialRequest->phone ? $this->normalizePhone($specialRequest->phone) : null;
        $normalizedEmail = $specialRequest->email ? strtolower(trim((string) $specialRequest->email)) : null;
        $resolvedUser = $specialRequest->user;

        if (!$resolvedUser && ($normalizedEmail || $normalizedPhone)) {
            $resolvedUser = User::query()
                ->when($normalizedEmail, fn ($q) => $q->orWhereRaw('LOWER(email) = ?', [$normalizedEmail]))
                ->get()
                ->first(function (User $candidate) use ($normalizedPhone) {
                    if (!$normalizedPhone) {
                        return true;
                    }

                    return $this->normalizePhone((string) $candidate->phone) === $normalizedPhone;
                });
        }

        SpecialItemOffer::create([
            'item_id' => $specialRequest->item_id,
            'special_request_id' => $specialRequest->id,
            'user_id' => $resolvedUser?->id,
            'target_phone' => $normalizedPhone,
            'target_email' => $normalizedEmail,
            'quantity' => (int) $validated['quantity'],
            'used_quantity' => 0,
            'is_active' => true,
        ]);

        $specialRequest->update(['status' => 'notified']);

        return back()->with('success', 'Private offer has been assigned successfully.');
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }
}
