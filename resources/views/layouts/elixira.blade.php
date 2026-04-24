<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Elixira')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/elixira-sections.css') }}">
    <style>
        a { text-decoration: none; }
    </style>
    @stack('head')
</head>
<body>
    <nav class="navbar navbar-custom">
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="logo">Elixira</a>

            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('menu.index') }}" class="{{ request()->routeIs('menu.*') ? 'active' : '' }}">Shop</a></li>
                <li><a href="{{ route('explore') }}" class="{{ request()->routeIs('explore') ? 'active' : '' }}">Explore</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}" class="text-primary fw-bold">Admin</a></li>
                    @endif
                    <li><a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">Account</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-link" style="background:none; border:none; color:inherit; font-family:inherit; cursor:pointer; padding: 0;">Log out</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Log in</a></li>
                @endauth

                <li class="cart-icon">
                    <a href="{{ route('cart.index') }}" aria-label="Shopping cart">
                        <i class="fas fa-shopping-basket"></i>
                        @php $cartCount = count(session('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span class="badge bg-gold elx-cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            <button class="mobile-menu-btn" aria-label="Toggle menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>
    </nav>

    <style>
        .navbar-custom {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            padding: 1rem 0;
        }
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-links {
            display: flex;
            list-style: none;
            align-items: center;
            gap: 1.35rem;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .nav-links a, .btn-link {
            color: var(--text-color);
            font-weight: 500;
            transition: color 0.3s;
            font-size: 1.05rem;
        }
        .nav-links a:hover, .nav-links a.active, .btn-link:hover {
            color: var(--primary-color);
        }
        body { padding-top: 80px; }
        .elx-cart-badge {
            background-color: var(--primary-color) !important;
            color: white !important;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
            position: relative;
            top: -10px;
        }
    </style>

    <main>@yield('content')</main>

    <footer class="footer">
        <div class="container text-center text-white pb-5">
            <h2 style="color: var(--secondary-color);">Unlock exclusive launches, curated tips, and membersâ€‘only offers.</h2>
            <p>No spam - good stuff only.</p>
            <div class="mt-4 mb-5 d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('contact') }}" class="btn btn-lg rounded-pill px-4" style="background-color: var(--secondary-color); color: #000; border: none;">Get in touch</a>
                <a href="{{ route('orders.track') }}" class="btn btn-lg btn-outline-light rounded-pill px-4">Track an order</a>
            </div>
        </div>

        <div style="background-color: var(--footer-bottom-bg); padding: 40px 0;">
            <div class="container text-center text-white">
                <p>
                    <span style="color: var(--secondary-color);">*</span> Clean, potent formulas
                    &nbsp;&nbsp;<span style="color: var(--secondary-color);">*</span> Rooted in nature
                    &nbsp;&nbsp;<span style="color: var(--secondary-color);">*</span> Modern wellness
                </p>
                <div class="mt-4" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                    <ul class="d-flex flex-wrap justify-content-center gap-4 p-0 m-0" style="list-style: none;">
                        <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="{{ route('menu.index') }}" class="text-white text-decoration-none">Shop</a></li>
                        <li><a href="{{ route('explore') }}" class="text-white text-decoration-none">Explore</a></li>
                        <li><a href="{{ route('about') }}" class="text-white text-decoration-none">About</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white text-decoration-none">Contact</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-white text-decoration-none">Cart</a></li>
                        @guest
                            <li><a href="{{ route('login') }}" class="text-white text-decoration-none">Log in</a></li>
                        @else
                            <li><a href="{{ route('profile.edit') }}" class="text-white text-decoration-none">Account</a></li>
                        @endguest
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', text: "{!! addslashes(session('success')) !!}", toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', text: "{!! addslashes(session('error')) !!}", toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
        @endif
    </script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        document.querySelectorAll('[data-elx-animate]').forEach(function (el) {
            el.classList.add('elx-animate-init');
        });
        if ('IntersectionObserver' in window) {
            var obs = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('elx-animate-in');
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            document.querySelectorAll('[data-elx-animate]').forEach(function (el) { obs.observe(el); });
        } else {
            document.querySelectorAll('[data-elx-animate]').forEach(function (el) { el.classList.add('elx-animate-in'); });
        }
    </script>
    @stack('scripts')
</body>
</html>
