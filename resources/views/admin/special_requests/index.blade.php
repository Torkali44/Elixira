@extends('layouts.admin')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="mb-1">{{ __('admin.special_requests_admin.title') }}</h2>
        <p class="text-muted mb-0">{{ __('admin.special_requests_admin.subtitle') }}</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px;">
            <div class="fw-bold fs-4 text-primary">{{ $totalRequests }}</div>
            <div class="text-muted small">{{ __('admin.special_requests_admin.total_requests') }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px;">
            <div class="fw-bold fs-4 text-warning">{{ $pendingRequestsCount }}</div>
            <div class="text-muted small">{{ __('admin.special_requests_admin.pending') }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px;">
            <div class="fw-bold fs-4 text-success">{{ $notifiedRequestsCount }}</div>
            <div class="text-muted small">{{ __('admin.special_requests_admin.notified_completed') }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px; height: 100%; display: flex; flex-direction: column; justify-content: center;">
            <div class="fw-bold text-truncate text-dark" style="font-size: 1.05rem;" title="{{ $topProductName }}">{{ $topProductName }}</div>
            <div class="text-muted small">{{ __('admin.special_requests_admin.top_requested', ['qty' => $topProductCount]) }}</div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">{{ __('admin.special_requests_admin.product') }}</th>
                        <th>{{ __('admin.special_requests_admin.user') }}</th>
                        <th>{{ __('admin.special_requests_admin.country') }}</th>
                        <th>{{ __('admin.special_requests_admin.phone') }}</th>
                        <th>{{ __('admin.special_requests_admin.email') }}</th>
                        <th>{{ __('admin.special_requests_admin.date') }}</th>
                        <th>{{ __('admin.special_requests_admin.status') }}</th>
                        <th>{{ __('admin.special_requests_admin.private_offers') }}</th>
                        <th class="text-end pe-3">{{ __('admin.special_requests_admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($specialRequests as $request)
                        <tr class="{{ $request->admin_read_at ? '' : 'table-warning' }}">
                            <td class="ps-3">
                                @if($request->item)
                                    <div class="d-flex align-items-center gap-3">
                                        @if($request->item->image)
                                            <img
                                                src="{{ asset('storage/' . $request->item->image) }}"
                                                alt=""
                                                width="48"
                                                height="48"
                                                loading="lazy"
                                                decoding="async"
                                                style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px;"
                                            >
                                        @else
                                            <div style="width: 48px; height: 48px; background: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark">{{ $request->item->local_name }}</div>
                                            <small class="text-muted">#{{ $request->item->id }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">{{ __('admin.special_requests_admin.deleted_product') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($request->user)
                                    <div class="fw-bold">{{ $request->user->name }}</div>
                                @else
                                    <div class="fw-bold">{{ $request->name ?? __('admin.special_requests_admin.guest') }}</div>
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
                                    <img src="{{ asset($country) }}" alt="" width="22" height="16" loading="lazy" decoding="async" style="object-fit: cover; border-radius: 2px; box-shadow: 0 0 0 1px rgba(0,0,0,.08);">
                                @else
                                    <span class="text-muted small">{{ __('admin.special_requests_admin.na') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $request->phone) }}" target="_blank" rel="noopener" class="btn btn-sm btn-success">
                                    <i class="fab fa-whatsapp me-1"></i>{{ $request->phone }}
                                </a>
                            </td>
                            <td>{{ $request->email ?: '—' }}</td>
                            <td><span class="small text-muted">{{ $request->created_at->format('M d, Y h:i A') }}</span></td>
                            <td>
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning text-dark">{{ __('admin.special_requests_admin.status_pending') }}</span>
                                @else
                                    <span class="badge bg-success">{{ __('admin.special_requests_admin.status_notified') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($request->offers->isNotEmpty())
                                    @foreach($request->offers as $offer)
                                        <div class="small mb-1">
                                            <span class="badge bg-info text-dark">
                                                {{ max(0, $offer->quantity - $offer->used_quantity) }} / {{ $offer->quantity }} {{ __('admin.special_requests_admin.left') }}
                                            </span>
                                            @if($offer->user)
                                                <span class="text-muted">{{ __('admin.special_requests_admin.for') }} {{ $offer->user->name }}</span>
                                            @elseif($offer->target_email)
                                                <span class="text-muted">{{ __('admin.special_requests_admin.for') }} {{ $offer->target_email }}</span>
                                            @elseif($offer->target_phone)
                                                <span class="text-muted">{{ __('admin.special_requests_admin.for') }} {{ $offer->target_phone }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-muted small">{{ __('admin.special_requests_admin.no_offer') }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex flex-wrap justify-content-end align-items-center gap-2">
                                    @if($request->item)
                                        <form action="{{ route('admin.special-requests.assign-offer', $request) }}" method="POST" class="d-flex align-items-center gap-1">
                                            @csrf
                                            <input type="number" name="quantity" value="1" min="1" max="20" class="form-control form-control-sm" style="width: 64px;" title="{{ __('admin.special_requests_admin.quantity') }}">
                                            <button type="submit" class="btn btn-sm btn-primary" title="{{ __('admin.special_requests_admin.offer_btn') }}">
                                                <i class="fas fa-plus"></i> {{ __('admin.special_requests_admin.offer_btn') }}
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.special-requests.updateStatus', $request) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        @if($request->status === 'pending')
                                            <input type="hidden" name="status" value="notified">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="{{ __('admin.special_requests_admin.mark_notified') }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @else
                                            <input type="hidden" name="status" value="pending">
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="{{ __('admin.special_requests_admin.mark_pending') }}">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                    </form>

                                    <form action="{{ route('admin.special-requests.destroy', $request) }}" method="POST" onsubmit="return confirm(@json(__('admin.special_requests_admin.confirm_delete')));">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('admin.common.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">{{ __('admin.special_requests_admin.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $specialRequests->links() }}
</div>
@endsection
