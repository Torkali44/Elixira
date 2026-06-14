@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ __('vendor.orders.title') }}</h2>
            <p class="text-muted mb-0">{{ __('vendor.orders.subtitle') }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">{{ __('vendor.orders.col_id') }}</th>
                            <th>{{ __('vendor.orders.col_customer') }}</th>
                            <th>{{ __('vendor.orders.col_phone') }}</th>
                            <th>{{ __('vendor.orders.col_products') }}</th>
                            <th>{{ __('vendor.orders.col_revenue') }}</th>
                            <th>{{ __('vendor.orders.col_status') }}</th>
                            <th class="text-center">{{ __('vendor.orders.col_date') }}</th>
                            <th class="text-end pe-4">{{ __('vendor.orders.col_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $vendorTotal = $order->orderItems->sum(fn($oi) => $oi->price * $oi->quantity);
                                $statusBadge = match($order->status) {
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'preparing' => 'primary',
                                    'ready' => 'success',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary',
                                };
                                $statusLabel = match($order->status) {
                                    'pending' => __('vendor.orders.status_pending'),
                                    'confirmed' => __('vendor.orders.status_confirmed'),
                                    'preparing' => __('vendor.orders.status_preparing'),
                                    'ready' => __('vendor.orders.status_ready'),
                                    'delivered' => __('vendor.orders.status_delivered'),
                                    'cancelled' => __('vendor.orders.status_cancelled'),
                                    default => ucfirst($order->status),
                                };
                            @endphp
                            <tr>
                                <td class="ps-4"><span class="fw-bold">#{{ $order->id }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $order->customer_name }}</div>
                                    @if($order->user)
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $order->customer_phone ?? '—' }}</small></td>
                                <td>
                                    @foreach($order->orderItems as $oi)
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            @if($oi->item && $oi->item->image)
                                                <img src="{{ asset('storage/' . $oi->item->image) }}" class="rounded" style="width: 28px; height: 28px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <small class="fw-bold">{{ $oi->item->name ?? __('vendor.orders.deleted') }}</small>
                                                <small class="text-muted ms-1">× {{ $oi->quantity }} — ﷼{{ number_format($oi->price, 2) }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="fw-bold text-success">﷼ {{ number_format($vendorTotal, 2) }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $statusBadge }} py-2 px-3">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="text-center text-muted small">
                                    {{ $order->created_at->format('M d, Y') }}<br>
                                    <small>{{ $order->created_at->format('H:i') }}</small>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('vendor.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                        <i class="fas fa-eye me-1"></i> {{ __('vendor.orders.details') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                    <p class="mb-0">{{ __('vendor.orders.empty') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $orders->links() }}
    </div>
</div>
@endsection
