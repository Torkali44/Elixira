@extends('layouts.admin')

@section('content')
    <h2 class="mb-4">Dashboard Overview</h2>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white h-100 shadow-sm"
                style="background-color: #13252D; border: none; border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0 opacity-75">Categories</h6>
                            <h2 class="mt-2 mb-0 fw-bold">{{ \App\Models\Category::count() }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-layer-group fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('admin.categories.index') }}" class="text-white text-decoration-none small">Manage <i
                            class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white h-100 shadow-sm"
                style="background-color: #0d9488; border: none; border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0 opacity-75">Products</h6>
                            <h2 class="mt-2 mb-0 fw-bold">{{ \App\Models\Item::count() }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-box-open fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('admin.items.index') }}" class="text-white text-decoration-none small">Inventory <i
                            class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white h-100 shadow-sm"
                style="background-color: #f59e0b; border: none; border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0 opacity-75">Pending Orders</h6>
                            <h2 class="mt-2 mb-0 fw-bold">{{ \App\Models\Order::where('status', 'pending')->count() }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('admin.orders.index') }}" class="text-white text-decoration-none small">Process <i
                            class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white h-100 shadow-sm"
                style="background-color: #0e7490; border: none; border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0 opacity-75">Users</h6>
                            <h2 class="mt-2 mb-0 fw-bold">{{ \App\Models\User::count() }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-user-circle fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none small">Manage Users <i
                            class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></a>
                </div>
            </div>
        </div>
    </div>

    @php
        $adminStatusBadge = function ($status) {
            return match ($status) {
                'pending' => 'warning',
                'confirmed' => 'info',
                'preparing' => 'primary',
                'ready' => 'success',
                'delivered' => 'success',
                'cancelled' => 'danger',
                default => 'secondary',
            };
        };
        $adminStatusLabel = function ($status) {
            return match ($status) {
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'preparing' => 'Preparing',
                'ready' => 'Ready to ship',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
                default => ucfirst($status),
            };
        };
    @endphp

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light text-primary fw-bold">All
                        Orders</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Order ID</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-center pe-4">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Order::latest()->take(6)->get() as $order)
                                    <tr>
                                        <td class="ps-4"><span class="fw-bold">{{ $order->id }}</span></td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td class="fw-bold">﷼ {{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge rounded-pill bg-{{ $adminStatusBadge($order->status) }} py-2 px-3">
                                                {{ $adminStatusLabel($order->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center text-muted pe-4 small">
                                            {{ $order->created_at->format('M d, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Users Control Center</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <small class="text-muted d-block">All Users</small>
                            <strong>{{ \App\Models\User::count() }}</strong>
                        </div>
                        <div>
                            <small class="text-muted d-block">Suspended users</small>
                            <strong>{{ \App\Models\User::where('is_suspended', true)->count() }}</strong>
                        </div>
                        <div>
                            <small class="text-muted d-block">Admins</small>
                            <strong>{{ \App\Models\User::where('role', 'admin')->count() }}</strong>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse(\App\Models\User::latest()->take(6)->get() as $user)
                            <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <x-user-avatar :user="$user" size="48" />
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small
                                            class="text-muted">{{ $user->avatar ? 'Custom avatar uploaded' : 'Using initials avatar' }}</small>
                                    </div>
                                </div>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="btn btn-sm btn-outline-secondary">Edit</a>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">No users found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold">Low Stock</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse(\App\Models\Item::where('stock', '<=', 10)->orderBy('stock', 'asc')->take(6)->get() as $lowStockItem)
                            <div class="list-group-item border-0 py-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle text-warning me-3"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $lowStockItem->name }}</h6>
                                        <small class="text-muted">Currently {{ $lowStockItem->stock }} in stock</small>
                                    </div>
                                </div>
                                <a href="{{ route('admin.items.edit', $lowStockItem->id) }}"
                                    class="btn btn-sm btn-outline-secondary rounded-pill">Update</a>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-check-circle text-success d-block mb-3" style="font-size: 2rem;"></i>
                                <p class="mb-0">All items are sufficiently stocked.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection