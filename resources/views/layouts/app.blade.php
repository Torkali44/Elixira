<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Elixira')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- Vite (for Breeze styles/scripts if needed later, but keeping visual style consistent with original) -->
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->

    <style>
        /* Small overrides to make Bootstrap play nice with custom CSS if needed */
        a { text-decoration: none; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-custom">
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="logo">Elixira</a>
            
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('menu.index') }}" class="{{ request()->routeIs('menu.*') ? 'active' : '' }}">Shop</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                <li><a href="{{ route('orders.track') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">Track order</a></li>
                
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}" class="text-primary fw-bold">Admin</a></li>
                    @endif
                    <li class="d-flex align-items-center gap-2">
                        <x-user-avatar :user="auth()->user()" size="32" />
                        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">Account</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-link" style="background:none; border:none; color:inherit; font-family:inherit; cursor:pointer; padding: 0;">Log out</button>
                        </form>
                    </li>
                @else
                    <li class="nav-auth-btns d-flex align-items-center gap-2 flex-wrap justify-content-center">
                        <a href="{{ route('login') }}" class="btn btn-sm rounded-pill px-3 nav-login-btn">Log in</a>
                        <a href="{{ route('register') }}" class="small fw-semibold text-decoration-none nav-register-link">Register</a>
                    </li>
                @endauth
                
                <li class="cart-icon">
                    <a href="{{ route('cart.index') }}" aria-label="Shopping cart">
                        <i class="fas fa-shopping-basket"></i>
                        @if(session('cart'))
                            <span class="badge bg-gold" style="background-color: var(--primary-color); color: white; border-radius: 50%; padding: 2px 6px; font-size: 0.7rem; position: relative; top: -10px;">{{ count(session('cart')) }}</span>
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
        /* Custom Navbar Styles tailored for Laravel integration */
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
            gap: 2rem;
            margin: 0;
            padding: 0;
        }
        .nav-links a, .btn-link {
            color: var(--text-color);
            font-weight: 500;
            transition: color 0.3s;
            font-size: 1.1rem;
        }
        .nav-links a:hover, .nav-links a.active, .btn-link:hover {
            color: var(--primary-color);
        }
        .nav-login-btn {
            background-color: var(--primary-color) !important;
            color: var(--white) !important;
            border: none;
            font-weight: 600;
        }
        .nav-login-btn:hover {
            background-color: var(--secondary-color) !important;
            color: var(--primary-color) !important;
        }
        .nav-register-link { color: var(--text-color) !important; }
        .nav-register-link:hover { color: var(--primary-color) !important; }
        body { padding-top: 80px; } /* Prevent content from hiding behind fixed nav */
    </style>

    <!-- Main Content -->
    <main>@yield('content')</main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center text-white pb-5">
            <h2 style="color: var(--secondary-color);">Unlock exclusive launches, curated tips, and membersâ€‘only offers.</h2>
            <p>No Spam, Good Stuff Only!</p>
            <div class="mt-4 mb-5 d-flex justify-content-center">
                <input type="email" placeholder="Your email address" style="padding: 10px 20px; border-radius: 50px 0 0 50px; border: none; outline: none; width: 300px;">
                <button class="btn btn-primary" style="border-radius: 0 50px 50px 0; background-color: var(--secondary-color); color: #000; border: none;">Subscribe</button>
            </div>
        </div>
        
        <div style="background-color: var(--footer-bottom-bg); padding: 40px 0;">
            <div class="container text-center text-white">
                <p>
                    <span style="color: var(--secondary-color);">*</span> Clean, potent, and beautifully crafted formulas &nbsp;&nbsp;
                    <span style="color: var(--secondary-color);">*</span> Rooted in nature &nbsp;&nbsp;
                    <span style="color: var(--secondary-color);">*</span> guided by modern wellness &nbsp;&nbsp;
                    <span style="color: var(--secondary-color);">*</span> Designed
                </p>
                <div class="mt-4" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                    <ul class="d-flex flex-wrap justify-content-center gap-4 p-0 m-0" style="list-style: none;">
                        <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="{{ route('menu.index') }}" class="text-white text-decoration-none">Shop</a></li>
                        <li><a href="{{ route('about') }}" class="text-white text-decoration-none">About</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white text-decoration-none">Contact</a></li>
                        <li><a href="{{ route('orders.track') }}" class="text-white text-decoration-none">Track order</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-white text-decoration-none">Cart</a></li>
                        @guest
                        <li><a href="{{ route('login') }}" class="text-white text-decoration-none">Log in</a></li>
                        @endguest
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
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
    @stack('scripts')
</body>
</html>
