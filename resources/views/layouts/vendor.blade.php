<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Elixira</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body {
            font-family: 'Istok Web', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #2D1325; /* Different color for vendor */
            color: #fff;
        }
        .sidebar a {
            color: rgba(255,255,255,.85);
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar a:hover, .sidebar a.active {
            color: #fff;
            background-color: rgba(215, 183, 208, 0.12);
        }
        .main-content {
            padding: 20px;
        }
        @media print {
            .sidebar, .navbar, .d-print-none {
                display: none !important;
            }
            .main-content {
                padding: 0 !important;
            }
            .col-12.col-md-10 {
                width: 100% !important;
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>
    <div class="row g-0">
        <div class="col-md-2 sidebar d-none d-md-block">
            <div class="p-3 text-center border-bottom border-secondary border-opacity-25">
                <h4 class="m-0">Elixira</h4>
                <small class="text-white-50">Vendor Portal</small>
            </div>
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
            <div class="mt-3">
                <a href="{{ route('vendor.dashboard') }}" class="{{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
                <a href="{{ route('vendor.items.index') }}" class="{{ request()->routeIs('vendor.items.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes me-2"></i> My Products
                    @if($vendorRejectedProductsCount > 0)
                        <span class="badge bg-danger rounded-pill ms-2" title="Rejected Products">{{ $vendorRejectedProductsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('vendor.orders') }}" class="{{ request()->routeIs('vendor.orders') ? 'active' : '' }}">
                    <i class="fas fa-receipt me-2"></i> My Orders
                    @if($vendorPendingOrdersCount > 0)
                        <span class="badge bg-danger rounded-pill ms-2" title="Pending Orders">{{ $vendorPendingOrdersCount }}</span>
                    @endif
                </a>
                <a href="{{ route('vendor.special-requests.index') }}" class="{{ request()->routeIs('vendor.special-requests.*') ? 'active' : '' }}">
                    <i class="fas fa-magic me-2"></i> Special Requests
                    @if($vendorSpecialRequestsCount > 0)
                        <span class="badge bg-danger rounded-pill ms-2" title="Pending Special Requests">{{ $vendorSpecialRequestsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('vendor.brand.edit') }}" class="{{ request()->routeIs('vendor.brand.*') ? 'active' : '' }}">
                    <i class="fas fa-store me-2"></i> My Brand
                </a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener">
                    <i class="fas fa-external-link-alt me-2"></i> View storefront
                </a>
            </div>
        </div>

        <div class="col-12 col-md-10">
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
                <div class="container-fluid">
                    <div class="d-flex align-items-center gap-3">
                        <x-user-avatar :user="Auth::user()" size="42" />
                        <div>
                            <div class="navbar-brand mb-0 h1 fs-5">Hello, {{ Auth::user()->name ?? 'Vendor' }}</div>
                            <small class="text-muted">{{ Auth::user()->vendorProfile->brand_name ?? 'Vendor' }}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 ms-auto">
                        <!-- Notification Bell Dropdown -->
                        <div class="dropdown me-3" id="vendorNotificationsMenu">
                            <button class="btn btn-link position-relative text-dark p-2" type="button" id="vendorNotifDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none; border-radius: 50%; background: #f1f3f5; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bell fs-5"></i>
                                @php $vendorUnreadNotifCount = auth()->user()->unreadNotifications()->count(); @endphp
                                @if($vendorUnreadNotifCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger vendor-notif-badge" style="font-size: 0.7rem; padding: 0.25em 0.5em;">
                                        {{ $vendorUnreadNotifCount }}
                                    </span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0 py-0" aria-labelledby="vendorNotifDropdown" style="width: 320px; border-radius: 12px; overflow: hidden; max-height: 450px;">
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light">
                                    <h6 class="m-0 fw-bold">Notifications</h6>
                                    @if($vendorUnreadNotifCount > 0)
                                        <button onclick="markAllVendorNotificationsAsRead(event)" class="btn btn-link p-0 text-decoration-none text-primary vendor-clear-notif" style="font-size: 0.8rem; font-weight: 600;">Mark all as read</button>
                                    @endif
                                </div>
                                <div class="vendor-notifications-list" style="max-height: 300px; overflow-y: auto;">
                                    @forelse(auth()->user()->notifications()->take(10)->get() as $notif)
                                        <div class="dropdown-item p-3 border-bottom vendor-notif-item {{ $notif->is_read ? 'bg-white' : 'bg-light border-start border-primary border-4' }}" 
                                             onclick="handleVendorNotificationClick(event, '{{ route('notifications.read', $notif->id) }}', '{{ $notif->url ?? '#' }}')"
                                             style="cursor: pointer; white-space: normal;">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <strong class="vendor-notif-title text-dark" style="font-size: 0.85rem; font-weight: {{ $notif->is_read ? 'normal' : 'bold' }};">{{ $notif->title }}</strong>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="text-muted mb-0" style="font-size: 0.8rem; line-height: 1.3;">{{ $notif->message }}</p>
                                        </div>
                                    @empty
                                        <div class="p-4 text-center text-muted">
                                            <i class="fas fa-bell-slash d-block fs-3 mb-2 opacity-50"></i>
                                            No notifications yet.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Log out</button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="main-content">
                @yield('content')
            </div>
        </div>
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
                title: 'Validation error',
                html: `{!! collect($errors->all())->map(fn ($error) => '<div style="margin:.25rem 0;">• '.e($error).'</div>')->implode('') !!}`,
                confirmButtonText: 'OK'
            });
        @endif

        function markAllVendorNotificationsAsRead(event) {
            event.preventDefault();
            event.stopPropagation();
            fetch("{{ route('notifications.read-all') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const badge = document.querySelector('.vendor-notif-badge');
                    if (badge) badge.remove();
                    document.querySelectorAll('.vendor-notif-item').forEach(item => {
                        item.classList.remove('bg-light', 'border-start', 'border-primary', 'border-4');
                        item.classList.add('bg-white');
                        const title = item.querySelector('.vendor-notif-title');
                        if (title) {
                            title.style.fontWeight = 'normal';
                        }
                    });
                    const clearBtn = document.querySelector('.vendor-clear-notif');
                    if (clearBtn) clearBtn.remove();
                }
            });
        }

        function handleVendorNotificationClick(event, readUrl, redirectUrl) {
            event.preventDefault();
            event.stopPropagation();
            
            fetch(readUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).finally(() => {
                window.location.href = redirectUrl;
            });
        }

        document.querySelectorAll('form[data-confirm]').forEach((form) => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Please confirm',
                    text: form.dataset.confirm,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
