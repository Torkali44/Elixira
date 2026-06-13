<!DOCTYPE html>
<html lang="{{ $currentLocale ?? 'en' }}" dir="{{ ($isRtl ?? false) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('vendor.dashboard.title') }} - Elixira</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        body { font-family: 'Istok Web', sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #2D1325; color: #fff; }
        .main-content { padding: 20px; }
        .vendor-offcanvas { background-color: #2D1325; color: #fff; }
        .vendor-offcanvas .btn-close { filter: invert(1); }
        @media (max-width: 767.98px) { .main-content { padding: 12px; } }
        @media print {
            .sidebar, .navbar, .d-print-none, .offcanvas { display: none !important; }
            .main-content { padding: 0 !important; }
            .col-12.col-md-10 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="{{ ($userTheme ?? 'light') === 'dark' ? 'dashboard-dark' : '' }}">
    @php
        $vendorProfile = auth()->user()->vendorProfile;
        $vendorPendingOrdersCount = 0;
        $vendorRejectedProductsCount = 0;
        $vendorSpecialRequestsCount = 0;
        if ($vendorProfile && $vendorProfile->brand) {
            $brandId = $vendorProfile->brand->id;
            $vendorItemIds = \App\Models\Item::where('brand_id', $brandId)->pluck('id');
            $vendorOrderIds = \App\Models\OrderItem::whereIn('item_id', $vendorItemIds)->pluck('order_id')->unique();
            $vendorPendingOrdersCount = \App\Models\Order::whereIn('id', $vendorOrderIds)->where('status', 'pending')->count();
            $vendorRejectedProductsCount = \App\Models\Item::where('brand_id', $brandId)->whereIn('status', ['rejected', 'rejected_with_notes'])->count();
            $vendorSpecialRequestsCount = \App\Models\SpecialRequest::whereIn('item_id', $vendorItemIds)->where('status', 'pending')->count();
        }
    @endphp

    <div class="row g-0">
        <div class="col-md-2 sidebar vendor-sidebar d-none d-md-block">
            <div class="p-3 text-center border-bottom border-secondary border-opacity-25">
                <h4 class="m-0">Elixira</h4>
                <small class="text-white-50">{{ __('vendor.common.vendor') }}</small>
            </div>
            <nav class="mt-2" aria-label="{{ __('vendor.nav.dashboard') }}">
                @include('partials.vendor-sidebar-links')
            </nav>
        </div>

        <div class="col-12 col-md-10">
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
                <div class="container-fluid flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#vendorSidebar" aria-controls="vendorSidebar" aria-label="{{ __('admin.common.menu') }}">
                            <i class="fas fa-bars"></i>
                        </button>
                        <x-user-avatar :user="Auth::user()" size="42" />
                        <div>
                            <div class="navbar-brand mb-0 h1 fs-5">{{ __('vendor.common.hello') }}, {{ Auth::user()->name ?? 'Vendor' }}</div>
                            <small class="text-muted">{{ Auth::user()->vendorProfile->brand_name ?? __('vendor.common.vendor') }}</small>
                        </div>
                    </div>
                    <div class="dashboard-navbar-actions ms-auto">
                        <x-dashboard-theme-toggle />
                        <x-locale-switcher />
                        <div class="dropdown" id="vendorNotificationsMenu">
                            <button class="btn btn-link position-relative text-dark p-2" type="button" id="vendorNotifDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none; border-radius: 50%; background: #f1f3f5; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bell fs-5"></i>
                                @php $vendorUnreadNotifCount = auth()->user()->unreadNotifications()->count(); @endphp
                                @if($vendorUnreadNotifCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger vendor-notif-badge" style="font-size: 0.7rem; padding: 0.25em 0.5em;">{{ $vendorUnreadNotifCount }}</span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0 py-0" aria-labelledby="vendorNotifDropdown" style="width: 320px; border-radius: 12px; overflow: hidden; max-height: 450px;">
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light">
                                    <h6 class="m-0 fw-bold">{{ __('app.notifications') }}</h6>
                                    @if($vendorUnreadNotifCount > 0)
                                        <button onclick="markAllVendorNotificationsAsRead(event)" class="btn btn-link p-0 text-decoration-none text-primary vendor-clear-notif" style="font-size: 0.8rem; font-weight: 600;">{{ __('app.mark_all_read') }}</button>
                                    @endif
                                </div>
                                <div class="vendor-notifications-list" style="max-height: 300px; overflow-y: auto;">
                                    @forelse(auth()->user()->notifications()->take(10)->get() as $notif)
                                        <div class="dropdown-item p-3 border-bottom vendor-notif-item {{ $notif->is_read ? 'bg-white' : 'bg-light border-start border-primary border-4' }}"
                                             onclick="handleVendorNotificationClick(event, '{{ route('notifications.read', $notif->id) }}', '{{ $notif->url ?? '#' }}')"
                                             style="cursor: pointer; white-space: normal;">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <strong class="vendor-notif-title text-dark" style="font-size: 0.85rem; font-weight: {{ $notif->is_read ? 'normal' : 'bold' }};">{{ $notif->display_title }}</strong>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="text-muted mb-0" style="font-size: 0.8rem; line-height: 1.3;">{{ $notif->display_message }}</p>
                                        </div>
                                    @empty
                                        <div class="p-4 text-center text-muted">
                                            <i class="fas fa-bell-slash d-block fs-3 mb-2 opacity-50"></i>
                                            {{ __('app.no_notifications') }}
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline mb-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('vendor.common.log_out') }}</button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start vendor-offcanvas d-md-none" tabindex="-1" id="vendorSidebar" aria-labelledby="vendorSidebarLabel">
        <div class="offcanvas-header border-bottom border-secondary border-opacity-25">
            <div>
                <h5 class="offcanvas-title mb-0 text-white" id="vendorSidebarLabel">Elixira</h5>
                <small class="text-white-50">{{ __('vendor.common.vendor') }}</small>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ __('app.close') }}"></button>
        </div>
        <nav class="offcanvas-body p-0" aria-label="{{ __('vendor.nav.dashboard') }}">
            @include('partials.vendor-sidebar-links')
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', text: "{!! addslashes(session('success')) !!}", toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', text: "{!! addslashes(session('error')) !!}", toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
        @endif
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: @json(__('admin.common.validation_error')),
                html: `{!! collect($errors->all())->map(fn ($error) => '<div style="margin:.25rem 0;">• '.e($error).'</div>')->implode('') !!}`,
                confirmButtonText: @json(__('app.confirm'))
            });
        @endif
        function markAllVendorNotificationsAsRead(event) {
            event.preventDefault();
            event.stopPropagation();
            fetch("{{ route('notifications.read-all') }}", { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('.vendor-notif-badge')?.remove();
                    document.querySelectorAll('.vendor-notif-item').forEach(item => {
                        item.classList.remove('bg-light', 'border-start', 'border-primary', 'border-4');
                        item.classList.add('bg-white');
                        const title = item.querySelector('.vendor-notif-title');
                        if (title) title.style.fontWeight = 'normal';
                    });
                    document.querySelector('.vendor-clear-notif')?.remove();
                }
            });
        }
        function handleVendorNotificationClick(event, readUrl, redirectUrl) {
            event.preventDefault();
            event.stopPropagation();
            fetch(readUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } }).finally(() => { window.location.href = redirectUrl; });
        }
        document.querySelectorAll('form[data-confirm]').forEach((form) => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: @json(__('admin.common.please_confirm')),
                    text: form.dataset.confirm,
                    showCancelButton: true,
                    confirmButtonText: @json(__('admin.common.yes')),
                    cancelButtonText: @json(__('admin.common.cancel')),
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });
        });
        document.querySelectorAll('#vendorSidebar a').forEach((link) => {
            link.addEventListener('click', () => {
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('vendorSidebar'));
                if (offcanvas) offcanvas.hide();
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
