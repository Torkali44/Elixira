@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="mb-0">Order #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Back to
            orders</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Line items</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->item->name ?? 'Removed product' }}</td>
                                    <td>﷼ {{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>﷼ {{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light fw-bold">
                                <td colspan="3" class="text-end">Total</td>
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
                    <h5 class="mb-0">Customer</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-2"><strong>Phone:</strong>
                        <x-phone-flag :phone="$order->customer_phone" />
                    </p>
                    <p><strong>Role:</strong> {{ $order->user ? $order->user->role : 'Guest' }}</p>
                    @if($order->user_code)
                        <p><strong>Code referral : </strong> {{ $order->user_code }}</p>
                    @endif
                    @if($order->address)
                        <p><strong>Address:</strong> {{ $order->address }}</p>
                    @endif
                    <p><strong>Placed:</strong> {{ $order->created_at->format('Y-m-d h:i A') }}</p>
                    @if($order->notes)
                        <div class="alert alert-secondary mb-0">
                            <strong>Notes:</strong><br>
                            {{ $order->notes }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order status</h5>
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
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'preparing' => 'Preparing',
                            'ready' => 'Ready to ship',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                            default => ucfirst($order->status),
                        };
                    @endphp
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fw-bold">Status:</span>
                        <span class="badge bg-{{ $badge }} fs-6 px-3 py-2 rounded-pill">{{ $label }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection