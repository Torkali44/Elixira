@extends('layouts.admin')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="mb-1"> Special Requests </h2>
        <p class="text-muted mb-0">Manage special requests for out-of-stock items.</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>User</th>
                        <th>Country</th>
                        <th>WhatsApp</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Private Offers</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($specialRequests as $request)
                        <tr>
                            <td>
                                @if($request->item)
                                    <div class="d-flex align-items-center gap-3">
                                        @if($request->item->image)
                                            <img src="{{ storage_public_url($request->item->image) }}" alt="" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div style="width: 48px; height: 48px; background: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $request->item->name }}</div>
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
                                <img src="{{ asset($country) }}" alt="Country" width="22" height="16" style="flex-shrink: 0; object-fit: cover; border-radius: 2px; box-shadow: 0 0 0 1px rgba(0,0,0,.08);">
                            </td>
                            
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $request->phone) }}?text={{ urlencode('مرحباً، المنتج (' . ($request->item ? $request->item->name : 'الذي طلبته') . ') متوفر الآن في Elixira!') }}" target="_blank" class="btn btn-sm btn-success">
                                    <i class="fab fa-whatsapp"></i> {{ $request->phone }}
                                </a>
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
                            <td class="text-end">
                                <form action="{{ route('admin.special-requests.assign-offer', $request) }}" method="POST" class="d-inline-flex align-items-center gap-1 me-1">
                                    @csrf
                                    <input type="number" name="quantity" value="1" min="1" max="20" class="form-control form-control-sm" style="width: 72px;">
                                    <button type="submit" class="btn btn-sm btn-primary" title="Assign private offer">
                                        <i class="fas fa-user-lock"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.special-requests.updateStatus', $request) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($request->status === 'pending')
                                        <input type="hidden" name="status" value="notified">
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Mark as Notified">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @else
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Mark as Pending">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @endif
                                </form>
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
