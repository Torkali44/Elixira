@extends('layouts.admin')

@section('content')
<style>
    @media print {
        .d-print-none, .sidebar, .navbar, .btn, .sticky-top, .card-header button { display: none !important; }
        .content-wrapper { margin: 0 !important; padding: 0 !important; }
        .card { border: 1px solid #ddd !important; box-shadow: none !important; margin-bottom: 20px !important; }
        body { background: white !important; color: black !important; }
        .table-responsive { overflow: visible !important; }
        .badge { border: 1px solid #000; color: #000 !important; background: transparent !important; }
        h2 { font-size: 24pt; margin-bottom: 10px; }
    }
</style>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Detailed Reports</h2>
        <p class="text-muted">Overview of your store performance, inventory, and users.</p>
    </div>
    <!-- print -->
    <!-- <div class="d-print-none">
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-2"></i> Print Full Report</button>
    </div> -->
</div>

<div class="row g-4 mb-5">
    <!-- Revenue Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #28a745 !important;">
            <div class="card-body">
                <small class="text-muted text-uppercase fw-bold">Total Revenue</small>
                <h3 class="mt-2">SAR {{ number_format($totalRevenue, 2) }}</h3>
                <p class="mb-0 text-success small"><i class="fas fa-wallet me-1"></i> Non-cancelled orders</p>
            </div>
        </div>
    </div>
    <!-- Orders Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #007bff !important;">
            <div class="card-body">
                <small class="text-muted text-uppercase fw-bold">Total Orders</small>
                <h3 class="mt-2">{{ $totalOrders }}</h3>
                <p class="mb-0 text-muted small"><i class="fas fa-shopping-bag me-1"></i> Lifetime orders</p>
            </div>
        </div>
    </div>
    <!-- Users Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #ffc107 !important;">
            <div class="card-body">
                <small class="text-muted text-uppercase fw-bold">Total Users</small>
                <h3 class="mt-2">{{ $totalUsers }}</h3>
                <p class="mb-0 text-muted small"><i class="fas fa-users me-1"></i> Registered accounts</p>
            </div>
        </div>
    </div>
    <!-- Stock Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #dc3545 !important;">
            <div class="card-body">
                <small class="text-muted text-uppercase fw-bold">Out of Stock</small>
                <h3 class="mt-2">{{ $outOfStock->count() }}</h3>
                <p class="mb-0 text-danger small"><i class="fas fa-exclamation-triangle me-1"></i> Needs restock</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Top Products -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-crown text-warning me-2"></i> Top Selling Products</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th class="text-center">Sold</th>
                                <th class="text-center">Stock</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->image)
                                            <img src="{{ asset('storage/'.$item->image) }}" class="rounded me-2" alt="" width="40">
                                        @endif
                                        <span class="fw-bold">{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $item->category->name }}</td>
                                <td class="text-center"><span class="badge bg-light text-dark px-3">{{ $item->total_sold }}</span></td>
                                <td class="text-center">
                                    <span class="badge {{ $item->stock > 0 ? 'bg-success' : 'bg-danger' }}">{{ $item->stock }}</span>
                                </td>
                                <td class="text-end fw-bold">SAR {{ number_format($item->total_sold * $item->price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Orders Breakdown -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-tasks me-2"></i> Orders by Status</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    @foreach($ordersByStatus as $status)
                    <div class="col-6 col-md-3 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">{{ $status->count }}</h4>
                            <small class="text-muted text-uppercase">{{ $status->status }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column (Alerts & Recent) -->
    <div class="col-lg-4">
        <!-- Stock Alerts -->
        @if($lowStock->count() > 0 || $outOfStock->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-danger text-white py-3">
                <h5 class="mb-0"><i class="fas fa-bell me-2"></i> Inventory Alerts</h5>
            </div>
            <div class="card-body">
                @foreach($outOfStock as $item)
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <strong class="text-danger">{{ $item->name }}</strong><br>
                        <small class="text-muted">Out of stock</small>
                    </div>
                    <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">Update</a>
                </div>
                @endforeach
                
                @foreach($lowStock as $item)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong class="text-warning">{{ $item->name }}</strong><br>
                        <small class="text-muted">Low stock: {{ $item->stock }} left</small>
                    </div>
                    <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">Update</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- All Users -->
        <div class="card border-0 shadow-sm" style="max-height: 500px; overflow-y: auto;">
            <div class="card-header bg-white py-3 sticky-top">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i> All Users</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($allUsers as $user)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <div class="fw-bold">{{ $user->name }} {!! $user->role === 'admin' ? '<span class="badge bg-primary ms-1">Admin</span>' : '' !!}</div>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <small class="text-muted me-3 d-none d-md-block">{{ $user->created_at->format('Y-m-d') }}</small>
                            @if($user->role !== 'admin')
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger d-print-none"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <!-- Detailed Orders Table (Great for Printing) -->
    <div class="col-12 mt-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i> Detailed Orders & Revenue History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Customer Name</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th class="text-end">Total Amount (Profit)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->customer_phone }}</td>
                                <td>
                                    @if($order->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">SAR {{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No orders found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="5" class="text-end">Total Revenue (Completed/Pending):</td>
                                <td class="text-end text-success">SAR {{ number_format($totalRevenue, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
