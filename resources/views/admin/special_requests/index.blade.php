@extends('layouts.admin')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="mb-1">Special Requests</h2>
        <p class="text-muted mb-0">Overview of special requests for out-of-stock items.</p>
    </div>
</div>

{{-- Statistics Dashboard --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px;">
            <div class="fw-bold fs-4 text-primary">{{ $totalRequests }}</div>
            <div class="text-muted small">Total Requests</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px;">
            <div class="fw-bold fs-4 text-warning">{{ $pendingRequestsCount }}</div>
            <div class="text-muted small">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px;">
            <div class="fw-bold fs-4 text-success">{{ $notifiedRequestsCount }}</div>
            <div class="text-muted small">Notified / Completed</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px; height: 100%; display: flex; flex-direction: column; justify-content: center;">
            <div class="fw-bold text-truncate text-dark" style="font-size: 1.05rem;" title="{{ $topProductName }}">{{ $topProductName }}</div>
            <div class="text-muted small">Top Requested ({{ $topProductCount }} qty)</div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>User</th>
                        <th>Country</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Private Offers</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($specialRequests as $request)
                        <tr>
                            <td>
                                @if($request->item)
                                    <div class="d-flex align-items-center gap-3">
                                        @if($request->item->image)
                                            <img src="{{ asset('storage/' . $request->item->image) }}" alt="" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div style="width: 48px; height: 48px; background: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark">{{ $request->item->name }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Deleted Product</span>
                                @endif
                            </td>
                            <td>
                                @if($request->user)
                                    <div class="fw-bold">{{ $request->user->name }}</div>
                                @else
                                    <div class="fw-bold">{{ $request->name ?? 'Guest' }}</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $phoneRaw = trim((string) $request->phone);
                                    $digitsOnly = preg_replace('/\D+/', '', $phoneRaw) ?: '';
                                    $country = null;
                                    if (str_starts_with($phoneRaw, '+971') || str_starts_with($digitsOnly, '971')) {
                                        $country = 'images/AE.png';
                                    } elseif (str_starts_with($phoneRaw, '+966') || str_starts_with($digitsOnly, '966')) {
                                        $country = 'images/sa.png';
                                    }
                                @endphp
                                @if($country)
                                    <img src="{{ asset($country) }}" alt="Country" width="22" height="16" style="flex-shrink: 0; object-fit: cover; border-radius: 2px; box-shadow: 0 0 0 1px rgba(0,0,0,.08);">
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            
                            <td>
                                <span class="text-muted">{{ $request->phone }}</span>
                            </td>

                            <td>{{ $request->email ?: '—' }}</td>
                            <td>{{ $request->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-success">Notified</span>
                                @endif
                            </td>
                            <td>
                                @if($request->offers->isNotEmpty())
                                    @foreach($request->offers as $offer)
                                        <div class="small mb-1">
                                            <span class="badge bg-info text-dark">
                                                {{ max(0, $offer->quantity - $offer->used_quantity) }} / {{ $offer->quantity }} left
                                            </span>
                                            @if($offer->user)
                                                <span class="text-muted">for {{ $offer->user->name }}</span>
                                            @elseif($offer->target_email)
                                                <span class="text-muted">for {{ $offer->target_email }}</span>
                                            @elseif($offer->target_phone)
                                                <span class="text-muted">for {{ $offer->target_phone }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-muted small">No offer yet</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No special requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $specialRequests->links() }}
        </div>
    </div>
</div>
@endsection
