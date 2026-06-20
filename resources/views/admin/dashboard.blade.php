@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="mb-0 fw-bold">{{ __('admin.dashboard.title') }}</h2>
        <span class="badge bg-light text-dark shadow-sm border py-2 px-3">
            <i class="fas fa-calendar-alt text-primary me-2"></i> {{ now()->format('l, d M Y') }}
        </span>
    </div>

    {{-- Counters row --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white h-100 shadow-sm"
                style="background-color: #13252D; border: none; border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0 opacity-75 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('admin.dashboard.categories') }}</h6>
                            <h2 class="mt-2 mb-0 fw-bold">{{ $categoriesCount }}</h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-layer-group fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('admin.categories.index') }}" class="text-white text-decoration-none small">{{ __('admin.common.manage') }} <i
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
                            <h6 class="card-title mb-0 opacity-75 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('admin.dashboard.total_products') }}</h6>
                            <h2 class="mt-2 mb-0 fw-bold">{{ $itemsCount }}</h2>
                            <small class="opacity-75" style="font-size: 0.7rem;">{{ __('admin.dashboard.active_products', ['count' => $itemsBreakdown['approved']]) }}</small>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-box-open fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('admin.items.index') }}" class="text-white text-decoration-none small">{{ __('admin.dashboard.inventory') }} <i
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
                            <h6 class="card-title mb-0 opacity-75 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('admin.dashboard.pending_orders') }}</h6>
                            <h2 class="mt-2 mb-0 fw-bold">{{ $pendingOrdersCount }}</h2>
                            <small class="opacity-75" style="font-size: 0.7rem;">{{ __('admin.dashboard.out_of_orders', ['count' => $ordersCount]) }}</small>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('admin.orders.index') }}" class="text-white text-decoration-none small">{{ __('admin.dashboard.process') }} <i
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
                            <h6 class="card-title mb-0 opacity-75 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('admin.dashboard.total_users') }}</h6>
                            <h2 class="mt-2 mb-0 fw-bold">{{ $usersCount }}</h2>
                            <small class="opacity-75" style="font-size: 0.7rem;">{{ __('admin.dashboard.active_sellers', ['count' => $vendorsCount]) }}</small>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-user-circle fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none small">{{ __('admin.dashboard.manage_users') }} <i
                            class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Breakdown --}}
    <div class="row g-4 mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-chart-pie text-primary me-2"></i> {{ __('admin.dashboard.product_status') }}</h6>
                    <div class="progress" style="height: 24px; border-radius: 12px; overflow: hidden;">
                        @php
                            $totalItems = max($itemsCount, 1);
                            $approvedPercent = round(($itemsBreakdown['approved'] / $totalItems) * 100);
                            $pendingPercent = round(($itemsBreakdown['pending'] / $totalItems) * 100);
                            $rejectedPercent = round(($itemsBreakdown['rejected'] / $totalItems) * 100);
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $approvedPercent }}%" 
                            title="{{ __('admin.dashboard.approved') }} ({{ $itemsBreakdown['approved'] }})">
                            {{ __('admin.dashboard.percent_approved', ['percent' => $approvedPercent]) }}
                        </div>
                        <div class="progress-bar bg-warning text-dark" role="progressbar" style="width: {{ $pendingPercent }}%" 
                            title="{{ __('admin.dashboard.pending') }} ({{ $itemsBreakdown['pending'] }})">
                            {{ __('admin.dashboard.percent_pending', ['percent' => $pendingPercent]) }}
                        </div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $rejectedPercent }}%" 
                            title="{{ __('admin.dashboard.rejected') }} ({{ $itemsBreakdown['rejected'] }})">
                            {{ __('admin.dashboard.percent_rejected', ['percent' => $rejectedPercent]) }}
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-4 mt-3 flex-wrap">
                        <span class="small text-muted"><i class="fas fa-circle text-success me-1"></i> {{ __('admin.dashboard.approved') }}: <strong>{{ $itemsBreakdown['approved'] }}</strong></span>
                        <span class="small text-muted"><i class="fas fa-circle text-warning me-1"></i> {{ __('admin.dashboard.pending') }}: <strong>{{ $itemsBreakdown['pending'] }}</strong></span>
                        <span class="small text-muted"><i class="fas fa-circle text-danger me-1"></i> {{ __('admin.dashboard.rejected') }}: <strong>{{ $itemsBreakdown['rejected'] }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Vendor Statistics & Top Sellers section --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-store-alt text-primary me-2"></i> {{ __('admin.dashboard.top_sellers_table') }}</h5>
                    <span class="badge bg-primary rounded-pill px-3 py-2">{{ __('admin.dashboard.total_vendors', ['count' => $vendorsCount]) }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">{{ __('admin.dashboard.rank') }}</th>
                                    <th>{{ __('admin.dashboard.brand') }}</th>
                                    <th>{{ __('admin.dashboard.vendor_owner') }}</th>
                                    <th>{{ __('admin.dashboard.products_count') }}</th>
                                    <th>{{ __('admin.dashboard.units_sold') }}</th>
                                    <th>{{ __('admin.dashboard.total_revenue') }}</th>
                                    <th>{{ __('admin.dashboard.service_countries') }}</th>
                                    <th class="pe-4">{{ __('admin.dashboard.brand_description') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topVendors as $index => $vendor)
                                    @php
                                        $rankColors = [
                                            0 => ['#ffd700', __('admin.dashboard.rank_gold')],
                                            1 => ['#c0c0c0', __('admin.dashboard.rank_silver')],
                                            2 => ['#cd7f32', __('admin.dashboard.rank_bronze')]
                                        ];
                                        $rankColor = $rankColors[$index] ?? null;
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            @if($rankColor)
                                                <span class="fs-4 me-1" style="color: {{ $rankColor[0] }};" title="{{ $rankColor[1] }}">
                                                    <i class="fas fa-crown"></i>
                                                </span>
                                                <strong style="color: {{ $rankColor[0] }}">{{ $index + 1 }}</strong>
                                            @else
                                                <span class="text-muted">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($vendor->brand_logo)
                                                    <img src="{{ asset('storage/' . $vendor->brand_logo) }}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-store text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $vendor->brand_name }}</div>
                                                    <a href="{{ route('brands.show', \App\Models\Brand::find($vendor->brand_id)?->slug ?? '') }}" target="_blank" class="small text-decoration-none text-cyan">{{ __('admin.dashboard.view_storefront') }} <i class="fas fa-external-link-alt" style="font-size:0.65rem;"></i></a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $vendor->vendor_name }}</div>
                                            <small class="text-muted">{{ $vendor->vendor_email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary rounded-pill px-2 py-1">
                                                {{ __('admin.dashboard.products_badge', ['count' => \App\Models\Brand::find($vendor->brand_id)?->items()->count() ?? 0]) }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-dark">{{ number_format($vendor->total_units_sold) }}</strong>
                                        </td>
                                        <td>
                                            <strong class="text-success">﷼ {{ number_format($vendor->total_sales, 2) }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $brandModel = \App\Models\Brand::find($vendor->brand_id);
                                            @endphp
                                            @if($brandModel && $brandModel->service_countries)
                                                <div class="d-flex gap-1 flex-wrap">
                                                    @foreach($brandModel->service_countries as $country)
                                                        @php
                                                            $flagSrc = '';
                                                            if (stripos($country, 'Saudi') !== false || stripos($country, 'KSA') !== false) {
                                                                $flagSrc = asset('images/sa.png');
                                                            } elseif (stripos($country, 'Emirates') !== false || stripos($country, 'UAE') !== false) {
                                                                $flagSrc = asset('images/AE.png');
                                                            }
                                                        @endphp
                                                        @if($flagSrc)
                                                            <img src="{{ $flagSrc }}" alt="{{ $country }}" width="18" height="12"
                                                                style="border-radius: 2px;" title="{{ $country }}">
                                                        @else
                                                            <span class="badge bg-light text-dark" style="font-size: 0.65rem;">{{ $country }}</span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-muted small" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ \App\Models\Brand::find($vendor->brand_id)?->description ?? __('admin.dashboard.no_description') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="fas fa-store-slash d-block mb-3" style="font-size: 2.5rem; opacity: 0.3;"></i>
                                            {{ __('admin.dashboard.no_vendor_sales') }}
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
                'pending' => __('admin.dashboard.status_pending'),
                'confirmed' => __('admin.dashboard.status_confirmed'),
                'preparing' => __('admin.dashboard.status_preparing'),
                'ready' => __('admin.dashboard.status_ready'),
                'delivered' => __('admin.dashboard.status_delivered'),
                'cancelled' => __('admin.dashboard.status_cancelled'),
                default => ucfirst($status),
            };
        };
    @endphp

    <div class="row g-4">
        {{-- Recent Orders --}}
        <div class="col-lg-7">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">{{ __('admin.dashboard.recent_orders') }}</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light text-primary fw-bold">{{ __('admin.dashboard.all_orders') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">{{ __('admin.dashboard.order_id') }}</th>
                                    <th>{{ __('admin.dashboard.customer') }}</th>
                                    <th>{{ __('admin.dashboard.total') }}</th>
                                    <th>{{ __('admin.dashboard.status') }}</th>
                                    <th class="text-center pe-4">{{ __('admin.dashboard.date') }}</th>
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
                                            {{ $order->created_at->format('M d, H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">{{ __('admin.dashboard.no_orders') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users list --}}
        <div class="col-lg-5">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">{{ __('admin.dashboard.users_control') }}</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">{{ __('admin.dashboard.view_all') }}</a>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <small class="text-muted d-block">{{ __('admin.dashboard.all_users') }}</small>
                            <strong>{{ $usersCount }}</strong>
                        </div>
                        <div>
                            <small class="text-muted d-block">{{ __('admin.dashboard.suspended_users') }}</small>
                            <strong>{{ $suspendedUsersCount }}</strong>
                        </div>
                        <div>
                            <small class="text-muted d-block">{{ __('admin.dashboard.admins_label') }}</small>
                            <strong>{{ $adminsCount }}</strong>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse(\App\Models\User::verified()->with('vendorProfile')->latest()->take(6)->get() as $user)
                            <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <x-user-avatar :user="$user" size="48" />
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span
                                                class="badge {{ $user->role === 'admin' ? 'bg-primary' : ($user->role === 'vendor' ? 'bg-info' : 'bg-secondary') }} px-2 py-1"
                                                style="font-size: 0.65rem;">{{ ucfirst($user->role) }}</span>
                                            @if($user->role === 'vendor' && $user->vendorProfile && $user->vendorProfile->service_countries)
                                                @foreach($user->vendorProfile->service_countries as $country)
                                                    @php
                                                        $flagSrc = '';
                                                        if (stripos($country, 'Saudi') !== false || stripos($country, 'KSA') !== false) {
                                                            $flagSrc = asset('images/sa.png');
                                                        } elseif (stripos($country, 'Emirates') !== false || stripos($country, 'UAE') !== false) {
                                                            $flagSrc = asset('images/AE.png');
                                                        }
                                                    @endphp
                                                    @if($flagSrc)
                                                        <img src="{{ $flagSrc }}" alt="{{ $country }}" width="18" height="12"
                                                            style="border-radius: 2px;" title="{{ $country }}">
                                                    @endif
                                                @endforeach
                                            @else
                                                <x-phone-flag :phone="$user->phone" :show-phone="false" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="btn btn-sm btn-outline-secondary">{{ __('admin.dashboard.edit') }}</a>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">{{ __('admin.dashboard.no_users') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Low Stock Items --}}
        <div class="col-12 mt-4">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold">{{ __('admin.dashboard.low_stock_alerts') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse(\App\Models\Item::where('stock', '<=', 10)->orderBy('stock', 'asc')->take(6)->get() as $lowStockItem)
                            <div class="list-group-item border-0 py-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle text-warning me-3"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $lowStockItem->local_name }}</h6>
                                        <small class="text-muted">{{ __('admin.dashboard.low_stock_desc', ['count' => $lowStockItem->stock, 'brand' => $lowStockItem->brandModel->name ?? $lowStockItem->brand ?? 'Elixira Store']) }}</small>
                                    </div>
                                </div>
                                <a href="{{ route('admin.items.edit', $lowStockItem->id) }}"
                                    class="btn btn-sm btn-outline-secondary rounded-pill">{{ __('admin.dashboard.update') }}</a>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-check-circle text-success d-block mb-3" style="font-size: 2rem;"></i>
                                <p class="mb-0">{{ __('admin.dashboard.all_stocked') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection