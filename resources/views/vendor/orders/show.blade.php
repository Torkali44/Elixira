@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="mb-0 fw-bold">Order #{{ $order->id }}</h2>
        <a href="{{ route('vendor.orders') }}" class="btn btn-outline-secondary" style="border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Orders
        </a>
    </div>

    <div class="row">
        <!-- Order Items -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold m-0 text-dark">Your Products in this Order</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $vendorTotal = 0; @endphp
                                @foreach($order->orderItems as $oi)
                                    @php 
                                        $subtotal = $oi->price * $oi->quantity;
                                        $vendorTotal += $subtotal;
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                @if($oi->item && $oi->item->image)
                                                    <img src="{{ asset('storage/' . $oi->item->image) }}" class="rounded" style="width: 44px; height: 44px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <span class="fw-bold text-dark d-block">{{ $oi->item->name ?? 'Deleted Product' }}</span>
                                                    @if($oi->item && $oi->item->brandModel)
                                                        <small class="text-muted">{{ $oi->item->brandModel->name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>﷼ {{ number_format($oi->price, 2) }}</td>
                                        <td>{{ $oi->quantity }}</td>
                                        <td class="text-end pe-4 fw-bold text-success">﷼ {{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-light fw-bold">
                                    <td colspan="3" class="text-end">Your Revenue Total:</td>
                                    <td class="text-end pe-4 text-success" style="font-size: 1.15rem;">﷼ {{ number_format($vendorTotal, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Fulfillment status -->
        <div class="col-md-4">
            <!-- Customer Information Card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold m-0 text-dark">Customer Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted d-block small">Name</span>
                        <span class="fw-bold text-dark">{{ $order->customer_name }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted d-block small">Phone</span>
                        <span class="fw-bold text-dark">
                            <i class="fas fa-phone me-1 text-muted"></i>{{ $order->customer_phone ?? '—' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted d-block small">Code Referral</span>
                        <span class="fw-bold text-dark">{{ $order->user_code ?? '—' }}</span>
                     </div>

                    @if($order->address)
                    <div class="mb-3">
                        <span class="text-muted d-block small">Shipping Address</span>
                        <span class="text-dark d-block" style="line-height: 1.4;">{{ $order->address }}</span>
                    </div>
                    @endif

                    <div class="mb-3">
                        <span class="text-muted d-block small">Ordered On</span>
                        <span class="text-dark">{{ $order->created_at->format('Y-m-d h:i A') }}</span>
                    </div>

                    @if($order->notes)
                    <div class="p-3 bg-light rounded text-muted small" style="border-left: 4px solid #6a1b9a;">
                        <strong class="text-dark d-block mb-1">Customer Notes:</strong>
                        {{ $order->notes }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Fulfillment Status Form Card -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold m-0 text-dark">Update Fulfillment</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('vendor.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold small text-muted">Order Status</label>
                            <select name="status" id="status" class="form-select" style="border-radius: 8px; padding: 0.6rem;">
                                <option value="pending" @selected($order->status === 'pending')>Pending Approval</option>
                                <option value="confirmed" @selected($order->status === 'confirmed')>Confirmed</option>
                                <option value="preparing" @selected($order->status === 'preparing')>Preparing</option>
                                <option value="ready" @selected($order->status === 'ready')>Ready to Ship</option>
                                <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                                <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-medium" style="border-radius: 8px; background-color: #2D1325; border-color: #2D1325;">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
