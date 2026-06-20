<!DOCTYPE html>
<html lang="{{ $currentLocale ?? 'en' }}" dir="{{ ($isRtl ?? false) ? 'rtl' : 'ltr' }}">
@php Illuminate\Pagination\Paginator::defaultView('pagination.bootstrap-admin'); @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.common.admin') }} - Elixira</title>
    @include('partials.favicon')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        body { font-family: 'Istok Web', sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #13252D; color: #fff; }
        .main-content { padding: 20px; }
        .sidebar-offcanvas { background-color: #13252D; color: #fff; }
        .sidebar-offcanvas .btn-close { filter: invert(1); }
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
        if (request()->routeIs('admin.orders.*')) {
            session(['orders_last_viewed_at' => now()]);
        }
        if (request()->routeIs('admin.special-requests.*')) {
            session(['special_requests_last_viewed_at' => now()]);
        }
        if (request()->routeIs('admin.vendors.requests.*')) {
            session(['vendors_last_viewed_at' => now()]);
        }
        if (request()->routeIs('admin.brands.*')) {
            session(['brands_last_viewed_at' => now()]);
        }
        if (request()->routeIs('admin.contact-messages.*')) {
            session(['contact_messages_last_viewed_at' => now()]);
        }
        if (request()->routeIs('admin.dxn-team-requests.*')) {
            session(['dxn_team_requests_last_viewed_at' => now()]);
        }
        if (request()->routeIs('admin.items.*')) {
            session(['items_last_viewed_at' => now()]);
        }
        if (request()->routeIs('admin.packages.*')) {
            session(['packages_last_viewed_at' => now()]);
        }

        $ordersLastViewed = session('orders_last_viewed_at');
        $newOrdersCount = $ordersLastViewed ? \App\Models\Order::where('created_at', '>', $ordersLastViewed)->count() : \App\Models\Order::where('status', 'pending')->count();
        $specialRequestsLastViewed = session('special_requests_last_viewed_at');
        $newSpecialRequestsCount = $specialRequestsLastViewed ? \App\Models\SpecialRequest::where('created_at', '>', $specialRequestsLastViewed)->count() : \App\Models\SpecialRequest::where('status', 'pending')->count();
        $vendorsLastViewed = session('vendors_last_viewed_at');
        $newVendorsCount = $vendorsLastViewed ? \App\Models\VendorProfile::where('status', 'pending')->where('updated_at', '>', $vendorsLastViewed)->count() : \App\Models\VendorProfile::where('status', 'pending')->count();
        $brandsLastViewed = session('brands_last_viewed_at');
        $newBrandsCount = $brandsLastViewed ? \App\Models\Brand::where('created_at', '>', $brandsLastViewed)->count() : \App\Models\Brand::where('is_active', false)->count();
        $contactMessagesLastViewed = session('contact_messages_last_viewed_at');
        $newContactMessagesCount = $contactMessagesLastViewed
            ? \App\Models\ContactMessage::where('created_at', '>', $contactMessagesLastViewed)->count()
            : \App\Models\ContactMessage::whereNull('read_at')->count();
        $dxnTeamLastViewed = session('dxn_team_requests_last_viewed_at');
        $newDxnTeamRequestsCount = $dxnTeamLastViewed
            ? \App\Models\DxnTeamRequest::where('created_at', '>', $dxnTeamLastViewed)->count()
            : \App\Models\DxnTeamRequest::where('status', 'pending')->whereNull('read_at')->count();
        $itemsLastViewed = session('items_last_viewed_at');
        $newPendingItemsCount = $itemsLastViewed
            ? \App\Models\Item::where('status', 'pending')->where('created_at', '>', $itemsLastViewed)->count()
            : \App\Models\Item::where('status', 'pending')->count();
        $packagesLastViewed = session('packages_last_viewed_at');
        $newPendingPackagesCount = $packagesLastViewed
            ? \App\Models\Package::where('status', 'pending')->where('created_at', '>', $packagesLastViewed)->count()
            : \App\Models\Package::where('status', 'pending')->count();
    @endphp

    <div class="row g-0">
        <div class="col-md-2 sidebar d-none d-md-block">
            <div class="p-3 text-center border-bottom border-secondary border-opacity-25">
                <h4 class="m-0">Elixira</h4>
                <small class="text-white-50">{{ __('admin.common.admin') }}</small>
            </div>
            <nav class="mt-2" aria-label="{{ __('admin.nav.dashboard') }}">
                @include('partials.admin-sidebar-links')
            </nav>
        </div>

        <div class="col-12 col-md-10">
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
                <div class="container-fluid flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="{{ __('admin.common.menu') }}">
                            <i class="fas fa-bars"></i>
                        </button>
                        <x-user-avatar :user="Auth::user()" size="42" />
                        <div>
                            <div class="navbar-brand mb-0 h1 fs-5">{{ __('admin.common.hello') }}, {{ Auth::user()->name ?? 'Admin' }}</div>
                            <small class="text-muted">{{ Auth::user()->email ?? 'Administrator' }}</small>
                        </div>
                    </div>
                    <div class="dashboard-navbar-actions ms-auto">
                        <x-dashboard-theme-toggle />
                        <x-locale-switcher />
                        <form method="POST" action="{{ route('logout') }}" class="d-inline mb-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('admin.common.log_out') }}</button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="main-content">
                @include('partials.dashboard-validation-support')
                @yield('content')
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start sidebar-offcanvas d-md-none" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
        <div class="offcanvas-header border-bottom border-secondary border-opacity-25">
            <div>
                <h5 class="offcanvas-title mb-0 text-white" id="adminSidebarLabel">Elixira</h5>
                <small class="text-white-50">{{ __('admin.common.admin') }}</small>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ __('app.close') }}"></button>
        </div>
        <nav class="offcanvas-body p-0" aria-label="{{ __('admin.nav.dashboard') }}">
            @include('partials.admin-sidebar-links')
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

            document.addEventListener('DOMContentLoaded', () => {
                const fieldTabMap = {
                    name_ar: '#name-ar-tab',
                    description_ar: '#desc-ar-tab',
                    name_en: '#name-en-tab',
                    description_en: '#desc-en-tab',
                };

                for (const [field, tabSelector] of Object.entries(fieldTabMap)) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (!input || !input.classList.contains('is-invalid')) {
                        continue;
                    }

                    const tabTrigger = document.querySelector(`[data-bs-toggle="tab"][href="${tabSelector}"], [data-bs-toggle="tab"][data-bs-target="${tabSelector}"]`);
                    if (tabTrigger) {
                        bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
                    }

                    input.focus();
                    break;
                }

                document.querySelectorAll('.nav-tabs .nav-link').forEach((link) => {
                    const target = link.getAttribute('href') || link.getAttribute('data-bs-target');
                    if (!target) {
                        return;
                    }

                    const pane = document.querySelector(target);
                    if (pane && pane.querySelector('.is-invalid')) {
                        link.classList.add('text-danger', 'fw-bold');
                    }
                });
            });
        @endif
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
        document.querySelectorAll('button[data-swal-confirm]').forEach((button) => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const form = button.closest('form');
                Swal.fire({
                    icon: 'warning',
                    title: @json(__('admin.common.please_confirm')),
                    text: button.dataset.swalConfirm,
                    showCancelButton: true,
                    confirmButtonText: @json(__('admin.common.yes')),
                    cancelButtonText: @json(__('admin.common.cancel')),
                }).then((result) => { if (result.isConfirmed && form) form.submit(); });
            });
        });
        document.querySelectorAll('#adminSidebar a').forEach((link) => {
            link.addEventListener('click', () => {
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('adminSidebar'));
                if (offcanvas) offcanvas.hide();
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
