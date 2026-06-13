@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="mb-0">{{ __('admin.orders.order_number', ['id' => $order->id]) }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> {{ __('admin.orders.back_to_orders') }}</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('admin.orders.line_items') }}</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('admin.orders.product') }}</th>
                                <th>{{ __('admin.orders.price') }}</th>
                                <th>{{ __('admin.orders.qty') }}</th>
                                <th>{{ __('admin.orders.subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->item->name ?? __('admin.orders.removed_product') }}</td>
                                    <td>﷼ {{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>﷼ {{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light fw-bold">
                                <td colspan="3" class="text-end">{{ __('admin.orders.total') }}</td>
                                <td>﷼ {{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('admin.orders.col_customer') }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>{{ __('admin.users_page.full_name') }}:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-2"><strong>{{ __('admin.orders.phone') }}:</strong>
                        <x-phone-flag :phone="$order->customer_phone" />
                    </p>
                    <p><strong>{{ __('admin.users_page.role') }}:</strong> {{ $order->user ? ($order->user->role === 'admin' ? __('admin.orders.admin') : __('admin.orders.user')) : __('admin.orders.guest') }}</p>
                    @if($order->user_code)
                        <p><strong>{{ __('admin.orders.code_referral') }}: </strong> {{ $order->user_code }}</p>
                    @endif
                    @if($order->address)
                        <p><strong>{{ __('admin.orders.address') }}:</strong> {{ $order->address }}</p>
                    @endif
                    <p><strong>{{ __('admin.orders.placed') }}:</strong> {{ $order->created_at->format('Y-m-d h:i A') }}</p>
                    @if($order->notes)
                        <div class="alert alert-secondary mb-0">
                            <strong>{{ __('admin.orders.notes') }}:</strong><br>
                            {{ $order->notes }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('admin.orders.order_status') }}</h5>
                </div>
                <div class="card-body">
                    @php
                        $badge = match ($order->status) {
                            'pending' => 'warning text-dark',
                            'confirmed' => 'info',
                            'preparing' => 'primary',
                            'ready' => 'success',
                            'delivered' => 'success',
                            'cancelled' => 'danger',
                            default => 'secondary',
                        };
                        $label = match ($order->status) {
                            'pending' => __('admin.orders.status_pending'),
                            'confirmed' => __('admin.orders.status_confirmed'),
                            'preparing' => __('admin.orders.status_preparing'),
                            'ready' => __('admin.orders.status_ready'),
                            'delivered' => __('admin.orders.status_delivered'),
                            'cancelled' => __('admin.orders.status_cancelled'),
                            default => ucfirst($order->status),
                        };
                    @endphp
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fw-bold">{{ __('admin.orders.status') }}:</span>
                        <span class="badge bg-{{ $badge }} fs-6 px-3 py-2 rounded-pill">{{ $label }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection