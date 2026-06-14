@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold text-dark">{{ __('vendor.special_requests.title') }}</h2>
            <p class="text-muted mb-0">{{ __('vendor.special_requests.subtitle') }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-0 py-3 ps-4">
            <h5 class="card-title fw-bold m-0 text-dark">{{ __('vendor.special_requests.card_title') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">{{ __('vendor.special_requests.col_product') }}</th>
                            <th>{{ __('vendor.special_requests.col_customer') }}</th>
                            <th>{{ __('vendor.special_requests.col_country') }}</th>
                            <th>{{ __('vendor.special_requests.col_whatsapp') }}</th>
                            <th>{{ __('vendor.special_requests.col_email') }}</th>
                            <th>{{ __('vendor.special_requests.col_date') }}</th>
                            <th>{{ __('vendor.special_requests.col_status') }}</th>
                            <th>{{ __('vendor.special_requests.col_offers') }}</th>
                            <th class="text-end pe-4">{{ __('vendor.special_requests.col_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specialRequests as $request)
                            <tr>
                                <td class="ps-4">
                                    @if($request->item)
                                        <div class="d-flex align-items-center gap-3">
                                            @if($request->item->image)
                                                <img src="{{ asset('storage/' . $request->item->image) }}" alt="" class="rounded" style="width: 44px; height: 44px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark">{{ $request->item->name }}</div>
                                                <small class="text-muted">ID: #{{ $request->item->id }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">{{ __('vendor.special_requests.deleted_product') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->user)
                                        <span class="fw-bold">{{ $request->user->name }}</span>
                                    @else
                                        <span class="fw-bold">{{ $request->name ?? __('vendor.special_requests.guest_user') }}</span>
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
                                        <img src="{{ asset($country) }}" alt="Country Flag" width="22" height="16" style="flex-shrink: 0; object-fit: cover; border-radius: 2px; box-shadow: 0 0 0 1px rgba(0,0,0,.08);">
                                    @else
                                        <span class="text-muted small">{{ __('vendor.special_requests.na') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $request->phone) }}?text={{ urlencode('Hello! The product (' . ($request->item ? $request->item->name : 'you requested') . ') is now available at Elixira!') }}" target="_blank" class="btn btn-sm btn-success px-3 py-1.5 fw-medium" style="border-radius: 6px;">
                                        <i class="fab fa-whatsapp me-1"></i> {{ __('vendor.special_requests.chat', ['phone' => $request->phone]) }}
                                    </a>
                                </td>
                                <td>
                                    @if($request->email)
                                        <small class="text-muted">{{ $request->email }}</small>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small text-muted">{{ $request->created_at->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    @if($request->status === 'pending')
                                        <span class="badge bg-warning text-dark py-1.5 px-3 rounded-pill" style="font-size: 0.75rem;">{{ __('vendor.special_requests.status_pending') }}</span>
                                    @else
                                        <span class="badge bg-success py-1.5 px-3 rounded-pill" style="font-size: 0.75rem;">{{ __('vendor.special_requests.status_notified') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->offers->isNotEmpty())
                                        @foreach($request->offers as $offer)
                                            <div class="small mb-1 d-flex flex-column gap-0.5">
                                                <span class="badge bg-info text-dark" style="font-size: 0.7rem; width: fit-content;">
                                                    {{ max(0, $offer->quantity - $offer->used_quantity) }} / {{ $offer->quantity }} {{ __('vendor.special_requests.left') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted small">{{ __('vendor.special_requests.no_offer') }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <form action="{{ route('vendor.special-requests.assign-offer', $request) }}" method="POST" class="d-flex align-items-center gap-1">
                                            @csrf
                                            <input type="number" name="quantity" value="1" min="1" max="20" class="form-control form-control-sm" style="width: 60px; border-radius: 6px;">
                                            <button type="submit" class="btn btn-sm btn-primary" title="Assign private purchase offer" style="border-radius: 6px; background-color: #2D1325; border-color: #2D1325;">
                                                <i class="fas fa-plus"></i> {{ __('vendor.special_requests.offer_btn') }}
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('vendor.special-requests.updateStatus', $request) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            @if($request->status === 'pending')
                                                <input type="hidden" name="status" value="notified">
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Mark as Notified" style="border-radius: 6px;">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @else
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Mark as Pending" style="border-radius: 6px;">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                    <p class="mb-0">{{ __('vendor.special_requests.empty') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $specialRequests->links() }}
    </div>
</div>
@endsection
