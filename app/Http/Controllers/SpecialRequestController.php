<?php

namespace App\Http\Controllers;

use App\Models\SpecialItemOffer;
use App\Models\SpecialRequest;
use App\Models\User;
use App\Support\UserNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        SpecialRequest::markAllAdminRead();

        $totalRequests = SpecialRequest::count();
        $pendingRequestsCount = SpecialRequest::where('status', 'pending')->count();
        $notifiedRequestsCount = SpecialRequest::where('status', 'notified')->count();

        $topRequested = SpecialRequest::select('item_id', DB::raw('count(*) as count'))
            ->groupBy('item_id')
            ->orderBy('count', 'desc')
            ->with('item')
            ->first();

        $topProductName = $topRequested && $topRequested->item
            ? $topRequested->item->local_name
            : __('admin.special_requests_admin.na');
        $topProductCount = $topRequested ? $topRequested->count : 0;

        $specialRequests = SpecialRequest::with(['item', 'user', 'offers' => function ($query) {
            $query->where('is_active', true)->latest();
        }])->latest()->paginate(15);

        return view('admin.special_requests.index', compact(
            'specialRequests', 'totalRequests', 'pendingRequestsCount', 'notifiedRequestsCount', 'topProductName', 'topProductCount'
        ));
    }

    public function updateStatus(Request $request, SpecialRequest $specialRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,notified',
        ]);

        $specialRequest->update([
            'status' => $validated['status'],
            'admin_read_at' => $specialRequest->admin_read_at ?? now(),
        ]);

        return back()->with('success', __('admin.special_requests_admin.status_updated'));
    }

    public function assignOffer(Request $request, SpecialRequest $specialRequest)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:20',
        ]);

        if (! $specialRequest->item) {
            return back()->with('error', __('admin.special_requests_admin.product_missing'));
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

        $specialRequest->update([
            'status' => 'notified',
            'admin_read_at' => $specialRequest->admin_read_at ?? now(),
        ]);

        try {
            if ($resolvedUser) {
                UserNotifier::send($resolvedUser->id, 'special_request_offer', [
                    'quantity' => (string) $validated['quantity'],
                    'product' => $specialRequest->item->local_name,
                ], route('menu.show', $specialRequest->item_id));
            }
        } catch (\Throwable $e) {
            \Log::error('Special request offer notification failed: '.$e->getMessage());
        }

        return back()->with('success', __('admin.special_requests_admin.offer_assigned'));
    }

    public function destroy(SpecialRequest $specialRequest)
    {
        $specialRequest->offers()->delete();
        $specialRequest->delete();

        return back()->with('success', __('admin.special_requests_admin.deleted'));
    }


    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }
}
