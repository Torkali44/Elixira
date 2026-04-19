<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Elixira — Superfoods & Wellness')</title>
    <meta name="description" content="A blend of superfoods, science, and self‑care rituals.">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Istok+Web:wght@400;700&family=Bricolage+Grotesque:wght@200..800&family=DM+Sans:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- Icons & Styles --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/elixira-home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* إضافة ستايل خاص للمحتوى الداخلي لضمان ظهوره تحت الناف بار الثابت */
        .page-content {
            padding-top: 100px;
            padding-bottom: 80px;
            min-height: 80vh;
        }

        .section-header-internal {
            text-align: center;
            margin-bottom: 3rem;
        }

        /* Footer Links Hover */
        .elx-footer a {
            transition: all 0.3s ease;
            display: inline-block;
        }
        .elx-footer a:hover {
            color: #4ac8f6 !important;
            transform: translateX(5px);
        }
    </style>
    @yield('head')
</head>

<body>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- NAVIGATION BAR (Global) --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <nav class="elx-nav @yield('nav-class', 'scrolled')" id="elxNav">
        <div class="elx-nav__inner">
            <a href="{{ route('home') }}" class="elx-nav__logo">
                <img src="https://framerusercontent.com/images/uXbQX90j2iRjfRCUW6NdMiNzUVM.png" alt="Elixira" class="elx-nav__logo-img" style="height: 28px; width: auto;">
            </a>

            <ul class="elx-nav__links" id="navLinks">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('menu.index') }}"
                        class="{{ request()->routeIs('menu.*') ? 'active' : '' }}">Shop</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                </li>
                <li><a href="{{ route('testimonials.index') }}" class="{{ request()->routeIs('testimonials.*') ? 'active' : '' }}">Testimonials</a></li>
                <li><a href="{{ route('contact') }}"
                        class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                <li><a href="{{ route('orders.track') }}"
                        class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">Track Order</a></li>
            </ul>

            <div class="elx-nav__actions">
                <a href="{{ route('cart.index') }}" class="elx-nav__cart" title="Cart">
                    <i class="fas fa-shopping-bag"></i>
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span class="elx-nav__cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="elx-nav__btn elx-nav__btn--admin">
                            <i class="fas fa-cog"></i> <span>Admin</span>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="elx-nav__logout-form">
                        @csrf
                        <button type="submit" class="elx-nav__btn elx-nav__btn--logout">
                            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="elx-nav__btn elx-nav__btn--login">Join Us</a>
                @endauth

                <button class="elx-nav__toggle" id="navToggle">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- FOOTER (Global) --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- FOOTER (Global) --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <footer class="elx-footer"
        style="position: relative; overflow: visible; padding: 6rem 1rem 4rem; background-color: #13252d;">
        {{-- Cloud Decorations — Absolute positioned --}}
        <div
            style="position: absolute; top: -50px; left: -100px; width: 400px; opacity: 0.35; pointer-events: none; z-index: 1;">
            <img src="https://framerusercontent.com/images/iR8Ma0AjH7EaIAPThF3xcp9l3bM.png"
                style="width: 100%; height: auto;" alt="Cloud Left">
        </div>
        <div
            style="position: absolute; top: -50px; right: -100px; width: 450px; opacity: 0.35; pointer-events: none; z-index: 1;">
            <img src="https://framerusercontent.com/images/qazH0744I2w9AnpfmUJIze7g.png"
                style="width: 100%; height: auto;" alt="Cloud Right">
        </div>

        <div class="elx-container" style="position: relative; z-index: 2;">
            <div class="elx-footer__inner"
                style="background: #13252D; backdrop-filter: blur(14px); border: 1px solid rgba(20, 204, 255, 0.15); border-radius: 32px; padding: 4rem 2rem;">

                {{-- Tagline --}}
                <div class="elx-footer__tagline"
                    style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem; margin-bottom: 4rem;">
                    <p style="font-size: 1.1rem; color: #fff; margin: 0;">✦ Clean, potent, and beautifully crafted
                        formulas</p>
                    <p style="font-size: 1.1rem; color: #fff; margin: 0;">✦ Rooted in nature</p>
                    <p style="font-size: 1.1rem; color: #fff; margin: 0;">✦ Guided by modern wellness</p>
                </div>

                <div class="elx-footer__grid"
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 4rem; margin-bottom: 4rem;">
                    <div class="elx-footer__brand">
                        <a href="{{ route('home') }}" style="display: block; margin-bottom: 2rem;">
                            <img src="https://framerusercontent.com/images/uXbQX90j2iRjfRCUW6NdMiNzUVM.png"
                                alt="Elixira Logo" style="height: 35px;">
                        </a>
                        <p style="color: rgba(255,255,255,0.6); font-size: 0.95rem; line-height: 1.6;">
                            *Designed to support beauty, energy, and inner harmony through the ritual of superfoods and
                            science.
                        </p>
                    </div>

                    <div class="elx-footer__nav-group">
                        <h4
                            style="color: #4ac8f6; font-size: 1rem; margin-bottom: 1.5rem; letter-spacing: 1px; text-transform: uppercase;">
                            Shop</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li><a href="{{ route('menu.index') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">Browse
                                    Store</a></li>
                            <li><a href="{{ route('cart.index') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">View
                                    Cart</a></li>
                            <li><a href="{{ route('orders.track') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">Track
                                    Order</a></li>
                        </ul>
                    </div>

                    <div class="elx-footer__nav-group">
                        <h4
                            style="color: #4ac8f6; font-size: 1rem; margin-bottom: 1.5rem; letter-spacing: 1px; text-transform: uppercase;">
                            About</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li><a href="{{ route('about') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">The
                                    Ritual</a></li>
                            <li><a href="{{ route('contact') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">Contact
                                    Us</a></li>
                            <li><a href="#"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">Instagram</a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Bottom info --}}
                <div
                    style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 2rem; display: flex; justify-content: space-between; align-items: center; color: rgba(255,255,255,0.5); font-size: 0.85rem;">
                    <span>© 2026 Elixira. All Rights Reserved.</span>
                    <div style="display: flex; gap: 2rem;">
                        <a href="#" style="color: inherit; text-decoration: none;">Privacy Policy</a>
                        <a href="#" style="color: inherit; text-decoration: none;">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{!! addslashes(session('success')) !!}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{!! addslashes(session('error')) !!}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
            });
        @endif

        const navToggle = document.getElementById('navToggle');
        const navLinks = document.getElementById('navLinks');
        navToggle?.addEventListener('click', () => {
            navLinks.classList.toggle('open');
            navToggle.classList.toggle('active');
        });

        const nav = document.getElementById('elxNav');
        // إذا كنت في الصفحة الرئيسية، اجعل الناف بار يتغير لونه عند السكرول
        if (nav && !nav.classList.contains('scrolled-permanent')) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    nav.classList.add('scrolled');
                } else if (!document.body.classList.contains('home-page')) {
                    // لا تفعل شيئا
                } else {
                    nav.classList.remove('scrolled');
                }
            });
        }

        const animateEls = document.querySelectorAll('[data-animate]');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        animateEls.forEach(el => observer.observe(el));

        function addToCartAjax(btn, event) {
            event.stopPropagation();
            event.preventDefault();
            
            let form = btn.closest('form');
            let url = form.action;
            let formData = new FormData(form);

            btn.disabled = true;
            let originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalContent;

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    
                    let badge = document.querySelector('.elx-nav__cart-badge');
                    if (badge) {
                        badge.innerText = data.cartCount;
                    } else if (data.cartCount > 0) {
                        let cartIcon = document.querySelector('.elx-nav__cart');
                        if(cartIcon) {
                            cartIcon.innerHTML += `<span class="elx-nav__cart-badge">${data.cartCount}</span>`;
                        }
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                    });
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = originalContent;
                console.error('Error:', error);
            });
        }
    </script>
    @yield('scripts')
</body>

</html>