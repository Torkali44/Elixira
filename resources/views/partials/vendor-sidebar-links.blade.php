<a href="{{ route('vendor.dashboard') }}" class="dashboard-nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i>
    <span>{{ __('vendor.nav.dashboard') }}</span>
</a>
<a href="{{ route('vendor.items.index') }}" class="dashboard-nav-link {{ request()->routeIs('vendor.items.*') ? 'active' : '' }}">
    <i class="fas fa-boxes"></i>
    <span>{{ __('vendor.nav.products') }}</span>
    @if($vendorRejectedProductsCount > 0)
        <span class="badge bg-danger rounded-pill" title="{{ __('vendor.nav.rejected_products') }}">{{ $vendorRejectedProductsCount }}</span>
    @endif
</a>
<a href="{{ route('vendor.orders') }}" class="dashboard-nav-link {{ request()->routeIs('vendor.orders') ? 'active' : '' }}">
    <i class="fas fa-receipt"></i>
    <span>{{ __('vendor.nav.orders') }}</span>
    @if($vendorPendingOrdersCount > 0)
        <span class="badge bg-danger rounded-pill" title="{{ __('vendor.nav.pending_orders') }}">{{ $vendorPendingOrdersCount }}</span>
    @endif
</a>
<a href="{{ route('vendor.special-requests.index') }}" class="dashboard-nav-link {{ request()->routeIs('vendor.special-requests.*') ? 'active' : '' }}">
    <i class="fas fa-magic"></i>
    <span>{{ __('vendor.nav.special_requests') }}</span>
    @if($vendorSpecialRequestsCount > 0)
        <span class="badge bg-danger rounded-pill" title="{{ __('vendor.nav.pending_special_requests') }}">{{ $vendorSpecialRequestsCount }}</span>
    @endif
</a>
<a href="{{ route('vendor.brand.edit') }}" class="dashboard-nav-link {{ request()->routeIs('vendor.brand.*') ? 'active' : '' }}">
    <i class="fas fa-store"></i>
    <span>{{ __('vendor.nav.brand') }}</span>
</a>
<a href="{{ route('home') }}" class="dashboard-nav-link" target="_blank" rel="noopener">
    <i class="fas fa-external-link-alt"></i>
    <span>{{ __('vendor.nav.view_storefront') }}</span>
</a>
