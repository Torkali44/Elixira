<a href="{{ route('admin.dashboard') }}" class="dashboard-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i>
    <span>{{ __('admin.nav.dashboard') }}</span>
</a>
<a href="{{ route('admin.categories.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
    <i class="fas fa-list"></i>
    <span>{{ __('admin.nav.categories') }}</span>
</a>
<a href="{{ route('admin.items.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.items.index') || request()->routeIs('admin.items.edit') || request()->routeIs('admin.items.create') ? 'active' : '' }}">
    <i class="fas fa-boxes"></i>
    <span>{{ __('admin.nav.products') }}</span>
    @if($newPendingItemsCount > 0)
        <span class="badge bg-danger rounded-pill">{{ $newPendingItemsCount }}</span>
    @endif
</a>
<a href="{{ route('admin.packages.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
    <i class="fas fa-box-open"></i>
    <span>{{ __('admin.nav.packages') }}</span>
    @if($newPendingPackagesCount > 0)
        <span class="badge bg-danger rounded-pill">{{ $newPendingPackagesCount }}</span>
    @endif
</a>
<a href="{{ route('admin.users.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
    <i class="fas fa-users"></i>
    <span>{{ __('admin.nav.users') }}</span>
</a>
<a href="{{ route('admin.orders.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
    <i class="fas fa-shopping-bag"></i>
    <span>{{ __('admin.nav.orders') }}</span>
    @if($newOrdersCount > 0)
        <span class="badge bg-danger rounded-pill">{{ $newOrdersCount }}</span>
    @endif
</a>
<a href="{{ route('admin.reports.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
    <i class="fas fa-chart-line"></i>
    <span>{{ __('admin.nav.reports') }}</span>
</a>
<a href="{{ route('admin.reviews.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
    <i class="fas fa-star"></i>
    <span>{{ __('admin.nav.reviews') }}</span>
</a>
<a href="{{ route('admin.subscribers.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}">
    <i class="fas fa-envelope-open-text"></i>
    <span>{{ __('admin.nav.subscribers') }}</span>
</a>
<a href="{{ route('admin.avatar-options.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.avatar-options.*') ? 'active' : '' }}">
    <i class="fas fa-user-astronaut"></i>
    <span>{{ __('admin.nav.avatars') }}</span>
</a>
<a href="{{ route('admin.special-requests.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.special-requests.*') ? 'active' : '' }}">
    <i class="fas fa-hand-holding-heart"></i>
    <span>{{ __('admin.nav.special_requests') }}</span>
    @if($newSpecialRequestsCount > 0)
        <span class="badge bg-danger rounded-pill">{{ $newSpecialRequestsCount }}</span>
    @endif
</a>
<a href="{{ route('admin.vendors.requests.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
    <i class="fas fa-store-alt"></i>
    <span>{{ __('admin.nav.vendor_requests') }}</span>
    @if($newVendorsCount > 0)
        <span class="badge bg-danger rounded-pill">{{ $newVendorsCount }}</span>
    @endif
</a>
<a href="{{ route('admin.contact-messages.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
    <i class="fas fa-envelope"></i>
    <span>{{ __('admin.nav.contact_messages') }}</span>
    @if($newContactMessagesCount > 0)
        <span class="badge bg-danger rounded-pill">{{ $newContactMessagesCount }}</span>
    @endif
</a>
<a href="{{ route('admin.dxn-team-requests.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.dxn-team-requests.*') ? 'active' : '' }}">
    <i class="fas fa-users"></i>
    <span>{{ __('admin.nav.dxn_team_requests') }}</span>
    @if($newDxnTeamRequestsCount > 0)
        <span class="badge bg-danger rounded-pill">{{ $newDxnTeamRequestsCount }}</span>
    @endif
</a>
<a href="{{ route('admin.dxn-sponsor-codes.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.dxn-sponsor-codes.*') ? 'active' : '' }}">
    <i class="fas fa-id-badge"></i>
    <span>{{ __('admin.nav.dxn_sponsor_codes') }}</span>
</a>
<a href="{{ route('admin.brands.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
    <i class="fas fa-tags"></i>
    <span>{{ __('admin.nav.brands') }}</span>
    @if($newBrandsCount > 0)
        <span class="badge bg-danger rounded-pill">{{ $newBrandsCount }}</span>
    @endif
</a>
@if(Route::has('admin.faqs.index'))
    <a href="{{ route('admin.faqs.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
        <i class="fas fa-question-circle"></i>
        <span>{{ __('admin.nav.faqs') }}</span>
    </a>
@endif
@if(Route::has('admin.blogs.index'))
    <a href="{{ route('admin.blogs.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
        <i class="fas fa-blog"></i>
        <span>{{ __('admin.nav.blogs') }}</span>
    </a>
@endif
<a href="{{ route('admin.home-sections.index') }}" class="dashboard-nav-link {{ request()->routeIs('admin.home-sections.*') ? 'active' : '' }}">
    <i class="fas fa-image"></i>
    <span>{{ __('admin.nav.home_sections') }}</span>
</a>
<a href="{{ route('admin.settings.translations') }}" class="dashboard-nav-link {{ request()->routeIs('admin.settings.translations') ? 'active' : '' }}">
    <i class="fas fa-globe"></i>
    <span>{{ __('admin.nav.translations') }}</span>
</a>
<a href="{{ route('home') }}" class="dashboard-nav-link" target="_blank" rel="noopener">
    <i class="fas fa-external-link-alt"></i>
    <span>{{ __('admin.nav.view_storefront') }}</span>
</a>
