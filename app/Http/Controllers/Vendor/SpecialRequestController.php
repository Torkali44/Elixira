<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Notification;
use App\Models\SpecialItemOffer;
use App\Models\SpecialRequest;
use App\Models\User;
use Illuminate\Http\Request;

class SpecialRequestController extends Controller
{
    public function index()
    {
        $vendorProfile = auth()->user()->vendorProfile;
        if (! $vendorProfile || ! $vendorProfile->brand) {
            return redirect()->route('vendor.dashboard')->with('error', 'Brand profile not found.');
        }

        $brandId = $vendorProfile->brand->id;
        $vendorItemIds = Item::where('brand_id', $brandId)->pluck('id');

        $specialRequests = SpecialRequest::whereIn('item_id', $vendorItemIds)
            ->with(['item', 'user', 'offers' => function ($query) {
                $query->where('is_active', true)->latest();
            }])
            ->latest()
            ->paginate(15);

        return view('vendor.special_requests.index', compact('specialRequests'));
    }

    public function updateStatus(Request $request, SpecialRequest $specialRequest)
    {
        $vendorProfile = auth()->user()->vendorProfile;
        if (! $vendorProfile || ! $vendorProfile->brand) {
            return redirect()->route('vendor.dashboard')->with('error', 'Brand profile not found.');
        }

        // Authorize that the special request belongs to this vendor's item
        if ($specialRequest->item->brand_id !== $vendorProfile->brand->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,notified',
        ]);

        $specialRequest->update(['status' => $validated['status']]);

        return back()->with('success', 'Request status updated successfully.');
    }

    public function assignOffer(Request $request, SpecialRequest $specialRequest)
    {
        $vendorProfile = auth()->user()->vendorProfile;
        if (! $vendorProfile || ! $vendorProfile->brand) {
            return redirect()->route('vendor.dashboard')->with('error', 'Brand profile not found.');
        }

        // Authorize that the special request belongs to this vendor's item
        if ($specialRequest->item->brand_id !== $vendorProfile->brand->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:20',
        ]);

        if (! $specialRequest->item) {
            return back()->with('error', 'This request is no longer linked to an existing product.');
        }

        $normalizedPhone = $specialRequest->phone ? $this->normalizePhone($specialRequest->phone) : null;
        $normalizedEmail = $specialRequest->email ? strtolower(trim((string) $specialRequest->email)) : null;
        $resolvedUser = $specialRequest->user;

        if (! $resolvedUser && ($normalizedEmail || $normalizedPhone)) {
            $resolvedUser = User::query()
                ->when($normalizedEmail, fn ($q) => $q->orWhereRaw('LOWER(email) = ?', [$normalizedEmail]))
                ->get()
                ->first(function (User $candidate) use ($normalizedPhone) {
                    if (! $normalizedPhone) {
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

        try {
            if ($resolvedUser) {
                Notification::create([
                    'user_id' => $resolvedUser->id,
                    'title' => 'Special Request Offer Assigned',
                    'message' => 'An offer of quantity '.$validated['quantity'].' has been assigned to your special request for "'.$specialRequest->item->name.'". You can now purchase it!',
                    'url' => route('menu.show', $specialRequest->item_id),
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error('Special request offer notification failed: '.$e->getMessage());
        }

        return back()->with('success', 'Private offer assigned successfully.');
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }
}
