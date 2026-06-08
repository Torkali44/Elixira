@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    @if(auth()->user()->vendorProfile?->brand && !auth()->user()->vendorProfile->brand->is_active)
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-start gap-3 mb-4" style="border-radius: 12px;">
            <i class="fas fa-store-slash mt-1"></i>
            <div>
                <strong>Your brand storefront is inactive.</strong>
                <div class="small mt-1">Public customers cannot access your store page. Visit <a href="{{ route('vendor.brand.edit') }}" class="alert-link">My Brand</a> for details.</div>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-9">
            <h2 class="mb-1">Vendor Dashboard</h2>
            <h4 class="text-primary mb-2">
                <i class="fas fa-store me-2"></i> {{ auth()->user()->vendorProfile->brand_name ?? 'Your Brand' }}
            </h4>
            <p class="text-muted mb-0">Welcome to your store overview — Track sales, orders, and customers.</p>
        </div>
        <div class="col-md-3 text-md-end d-print-none mt-3 mt-md-0">
            <button onclick="window.print()" class="btn btn-outline-primary px-3 py-2" style="border-radius: 8px; border-color: #2D1325; color: #2D1325; font-weight: 600; background: transparent; transition: all 0.2s;">
                <i class="fas fa-print me-2"></i> Print Dashboard
            </button>
        </div>
    </div>

    {{-- Row 1: Product Stats --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Total Products</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_items'] }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(45, 19, 37, 0.08);">
                            <i class="fas fa-boxes text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Pending Approval</h6>
                            <h2 class="mb-0 fw-bold text-warning">{{ $stats['pending_items'] }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(245, 158, 11, 0.08);">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Approved</h6>
                            <h2 class="mb-0 fw-bold text-success">{{ $stats['approved_items'] }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(16, 185, 129, 0.08);">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Needs Revision</h6>
                            <h2 class="mb-0 fw-bold text-warning">{{ $stats['revision_items'] }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(245, 158, 11, 0.08);">
                            <i class="fas fa-edit text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($stats['rejected_items'] > 0)
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Permanently Rejected</h6>
                            <h2 class="mb-0 fw-bold text-danger">{{ $stats['rejected_items'] }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(239, 68, 68, 0.08);">
                            <i class="fas fa-times-circle text-danger fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Row 2: Revenue Stats --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-white" style="border-radius: 16px; background: linear-gradient(135deg, #2D1325, #5a2d4a);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="opacity-75 text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Total Revenue</h6>
                            <h2 class="mb-0 fw-bold">﷼ {{ number_format($stats['total_revenue'], 2) }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-coins fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-white" style="border-radius: 16px; background: linear-gradient(135deg, #0d4e42, #0d9488);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="opacity-75 text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Total Orders</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_orders'] }}</h2>
                            <small class="opacity-75">{{ $stats['items_sold'] }} items sold</small>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-shopping-bag fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-white" style="border-radius: 16px; background: linear-gradient(135deg, #1e3a5f, #0e7490);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="opacity-75 text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Unique Customers</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['unique_customers'] }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-user-friends fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Monthly Sales Chart + Top Products --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2 text-primary"></i> Monthly Sales</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between" style="height: 200px; padding: 0 10px;">
                        @php $maxRevenue = max(array_column($monthlySales, 'revenue')) ?: 1; @endphp
                        @foreach($monthlySales as $sale)
                            <div class="text-center flex-fill mx-1">
                                <div class="d-flex flex-column align-items-center justify-content-end" style="height: 170px;">
                                    @php $height = ($sale['revenue'] / $maxRevenue) * 150; @endphp
                                    <small class="text-muted mb-1" style="font-size: 0.65rem;">﷼{{ number_format($sale['revenue']) }}</small>
                                    <div style="width: 100%; max-width: 45px; height: {{ max($height, 4) }}px; background: linear-gradient(180deg, #5a2d4a, #2D1325); border-radius: 8px 8px 4px 4px; transition: all 0.3s;"></div>
                                </div>
                                <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">{{ $sale['month'] }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-trophy me-2 text-warning"></i> Top Selling Products</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($topProducts as $tp)
                            <div class="list-group-item border-0 px-4 py-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    @if($tp->item && $tp->item->image)
                                        <img src="{{ asset('storage/' . $tp->item->image) }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-box text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold" style="font-size: 0.9rem;">{{ $tp->item->name ?? 'Deleted Product' }}</div>
                                        <small class="text-muted">{{ $tp->total_sold }} sold</small>
                                    </div>
                                </div>
                                <span class="badge bg-success rounded-pill px-3 py-2">﷼ {{ number_format($tp->total_revenue, 2) }}</span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-chart-line d-block mb-2" style="font-size: 1.5rem; opacity: 0.3;"></i>
                                No sales data yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 4: Recent Orders --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-receipt me-2 text-info"></i> Recent Orders</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Order #</th>
                                    <th>Customer</th>
                                    <th>Products</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
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
                                    @endphp
                                    <tr>
                                        <td class="ps-4"><span class="fw-bold">#{{ $order->id }}</span></td>
                                        <td>
                                            <div class="fw-bold">{{ $order->customer_name }}</div>
                                            @if($order->user)
                                                <small class="text-muted">{{ $order->user->email }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($order->orderItems as $oi)
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    @if($oi->item && $oi->item->image)
                                                        <img src="{{ asset('storage/' . $oi->item->image) }}" class="rounded" style="width: 24px; height: 24px; object-fit: cover;">
                                                    @endif
                                                    <small>{{ $oi->item->name ?? 'Deleted' }} × {{ $oi->quantity }}</small>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="fw-bold">﷼ {{ number_format($vendorTotal, 2) }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-{{ $statusBadge }} py-2 px-3">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center text-muted small">
                                            {{ $order->created_at->format('M d, H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                            No orders yet. Once customers buy your products, they will appear here.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 5: Top Customers + Low Stock --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-crown me-2" style="color: #f59e0b;"></i> Top Customers</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($topCustomers as $tc)
                            <div class="list-group-item border-0 px-4 py-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <x-user-avatar :user="$tc->user" size="40" />
                                    <div>
                                        <div class="fw-bold">{{ $tc->user->name }}</div>
                                        <small class="text-muted">{{ $tc->order_count }} order(s)</small>
                                    </div>
                                </div>
                                <span class="badge bg-primary rounded-pill px-3 py-2">﷼ {{ number_format($tc->total_spent, 2) }}</span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-users d-block mb-2" style="font-size: 1.5rem; opacity: 0.3;"></i>
                                No customer data yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-exclamation-triangle me-2 text-warning"></i> Low Stock Alert</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($lowStockItems as $item)
                            <div class="list-group-item border-0 px-4 py-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-box text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $item->name }}</div>
                                        <small class="{{ $item->stock <= 3 ? 'text-danger' : 'text-warning' }} fw-bold">{{ $item->stock }} left in stock</small>
                                    </div>
                                </div>
                                <a href="{{ route('vendor.items.edit', $item) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-edit me-1"></i> Update
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-check-circle text-success d-block mb-2" style="font-size: 1.5rem;"></i>
                                All products are well stocked!
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
