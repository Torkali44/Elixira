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
            <div class="mt-3">
                <a href="{{ route('vendor.dashboard') }}" class="{{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
                <a href="{{ route('vendor.items.index') }}" class="{{ request()->routeIs('vendor.items.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes me-2"></i> My Products
                </a>
                <a href="{{ route('vendor.orders') }}" class="{{ request()->routeIs('vendor.orders') ? 'active' : '' }}">
                    <i class="fas fa-receipt me-2"></i> My Orders
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
                    <form method="POST" action="{{ route('logout') }}" class="d-inline ms-auto">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Log out</button>
                    </form>
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
