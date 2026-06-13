<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <script>
        document.documentElement.classList.add('elx-animate-scroll');
        // Immediately apply saved theme to prevent FOUC
        (function() {
            var theme = @json($userTheme ?? session('theme', 'dark'));
            if (theme === 'light') {
                document.body.classList.add('light-mode');
            }
            localStorage.setItem('elx-theme', theme);
            localStorage.setItem('theme', theme);
        })();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Elixira - Superfoods & Wellness')</title>
    <meta name="description" content="A blend of superfoods, science, and self-care rituals.">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Istok+Web:wght@400;700&family=Bricolage+Grotesque:wght@200..800&family=DM+Sans:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- Icons & Styles --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/elixira-home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
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

        /* Notifications Dropdown Toggle */
        #notificationsMenu.open .elx-nav__notifications-dropdown {
            display: block !important;
        }
        .elx-nav__notifications-item:hover {
            background: rgba(255, 255, 255, 0.08) !important;
        }

        /* ─── Nav Dropdown Menus ─── */
        .elx-nav__dropdown {
            position: relative;
        }
        .elx-nav__dropdown-menu {
            position: absolute;
            top: calc(100% + 0.6rem);
            left: 50%;
            transform: translateX(-50%);
            min-width: 180px;
            padding: 0.5rem;
            border-radius: 16px;
            background: rgba(6, 15, 20, 0.97);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 16px 50px rgba(0,0,0,0.4);
            backdrop-filter: blur(20px);
            opacity: 0;
            pointer-events: none;
            transform: translateX(-50%) translateY(-8px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            z-index: 1050;
        }
        .elx-nav__dropdown:hover .elx-nav__dropdown-menu,
        .elx-nav__dropdown.open .elx-nav__dropdown-menu {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }
        .elx-nav__dropdown-menu a {
            display: block;
            padding: 0.65rem 1rem;
            border-radius: 10px;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .elx-nav__dropdown-menu a:hover,
        .elx-nav__dropdown-menu a.active {
            background: rgba(74, 200, 246, 0.1);
            color: #fff;
        }
        .elx-nav__dropdown > a .fa-chevron-down {
            font-size: 0.6rem;
            margin-left: 4px;
            transition: transform 0.2s ease;
        }
        .elx-nav__dropdown:hover > a .fa-chevron-down {
            transform: rotate(180deg);
        }

        /* ─── Theme & Lang Toggle Buttons ─── */
        .elx-nav__icon-btn {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .elx-nav__icon-btn:hover {
            background: rgba(74, 200, 246, 0.15);
            border-color: var(--elx-cyan);
            color: var(--elx-cyan);
            transform: scale(1.08);
        }
        .elx-nav__lang-btn {
            width: auto;
            padding: 0 0.85rem;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            border-radius: 100px;
            height: 38px;
        }

        /* ─── Light Theme Override ─── */
        body.light-mode {
            --elx-dark: #f5f7fa;
            --elx-darker: #eef1f5;
            --elx-primary: #f0f4f8;
            --elx-white: #1a2a33;
            --elx-gray: #5a6a75;
            --elx-light: #3a5565;
            --elx-glass: #ffffff;
            --elx-glass-strong: rgba(255,255,255,0.95);
            --elx-border: rgba(0,0,0,0.08);
        }
        body.light-mode {
            background: #eef1f5;
            color: #1a2a33;
        }
        body.light-mode .elx-nav.scrolled {
            background: rgba(255,255,255,0.92);
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }
        body.light-mode .elx-nav__links a {
            color: rgba(26,42,51,0.7);
        }
        body.light-mode .elx-nav__links a:hover,
        body.light-mode .elx-nav__links a.active {
            color: #1a2a33;
        }
        body.light-mode .elx-nav__cart,
        body.light-mode .elx-nav__icon-btn {
            background: rgba(0,0,0,0.04);
            border-color: rgba(0,0,0,0.1);
            color: #1a2a33;
        }
        body.light-mode .elx-nav__profile-trigger {
            background: rgba(0,0,0,0.04);
            border-color: rgba(0,0,0,0.1);
            color: #1a2a33;
        }
        body.light-mode .elx-nav__profile-menu,
        body.light-mode .elx-nav__dropdown-menu {
            background: rgba(255,255,255,0.98);
            border-color: rgba(0,0,0,0.08);
            box-shadow: 0 16px 50px rgba(0,0,0,0.12);
        }
        body.light-mode .elx-nav__dropdown-menu a {
            color: rgba(26,42,51,0.75);
        }
        body.light-mode .elx-nav__dropdown-menu a:hover {
            background: rgba(74, 200, 246, 0.08);
            color: #1a2a33;
        }
        body.light-mode .elx-nav__profile-head strong { color: #1a2a33; }
        body.light-mode .elx-nav__profile-head span { color: #5a6a75; }
        body.light-mode .elx-nav__profile-menu a,
        body.light-mode .elx-nav__profile-menu button { color: rgba(26,42,51,0.8); }
        body.light-mode .elx-nav__profile-menu a:hover,
        body.light-mode .elx-nav__profile-menu button:hover { background: rgba(0,0,0,0.04); color: #1a2a33; }
        body.light-mode .elx-footer { background-color: #dce3ea !important; }
        body.light-mode .elx-footer__inner { background: #dce3ea !important; border-color: rgba(0,0,0,0.06) !important; }
        body.light-mode .elx-nav__notifications-dropdown { background: #fff !important; border-color: rgba(0,0,0,0.1) !important; }
        body.light-mode .elx-nav__notifications-head { background: rgba(0,0,0,0.02) !important; border-color: rgba(0,0,0,0.06) !important; }
        body.light-mode .elx-nav__notifications-head strong { color: #1a2a33 !important; }
        body.light-mode .elx-nav__profile-meta strong,
        body.light-mode .elx-nav__profile-meta small { color: #1a2a33 !important; }
        body.light-mode .elx-hero__subtitle { color: #3d4f5c !important; }
        body.light-mode .page-content { color: #13252d; }
    </style>
    @yield('head')
</head>

<body>

    {{-- NAVIGATION BAR (Global) --}}
     <nav class="elx-nav @yield('nav-class', 'scrolled')" id="elxNav">
        <div class="elx-nav__inner">
            <a href="{{ route('home') }}" class="elx-nav__logo">
                <img src="https://framerusercontent.com/images/uXbQX90j2iRjfRCUW6NdMiNzUVM.png" alt="Elixira" class="elx-nav__logo-img" style="height: 28px; width: auto;">
            </a>

            <ul class="elx-nav__links" id="navLinks">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('app.home') }}</a></li>
                <li><a href="{{ route('menu.index') }}"
                        class="{{ request()->routeIs('menu.*') ? 'active' : '' }}">{{ __('app.shop') }}</a></li>
                <li class="elx-nav__dropdown">
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') || request()->routeIs('brands.*') || request()->routeIs('faqs.*') ? 'active' : '' }}">{{ __('app.about') }} <i class="fas fa-chevron-down"></i></a>
                    <div class="elx-nav__dropdown-menu">
                        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __('app.about_us') }}</a>
                        <a href="{{ route('brands.index') }}" class="{{ request()->routeIs('brands.*') ? 'active' : '' }}">{{ __('app.brands') }}</a>
                        <a href="{{ route('faqs.index') }}" class="{{ request()->routeIs('faqs.*') ? 'active' : '' }}">{{ __('app.faqs') }}</a>
                    </div>
                </li>
                <li><a href="{{ route('testimonials.index') }}" class="{{ request()->routeIs('testimonials.*') ? 'active' : '' }}">{{ __('app.testimonials') }}</a></li>
                <li class="elx-nav__dropdown">
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') || request()->routeIs('blogs.*') ? 'active' : '' }}">{{ __('app.contact') }} <i class="fas fa-chevron-down"></i></a>
                    <div class="elx-nav__dropdown-menu">
                        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">{{ __('app.contact_us') }}</a>
                        <a href="{{ route('blogs.index') }}" class="{{ request()->routeIs('blogs.*') ? 'active' : '' }}">{{ __('app.blogs') }}</a>
                    </div>
                </li>
                <li><a href="{{ route('orders.track') }}"
                        class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">{{ __('app.track_order') }}</a></li>
            </ul>

            <div class="elx-nav__actions" style="gap: 0.6rem;">
                @auth
                    <div class="elx-nav__notifications-wrapper" id="notificationsMenu" style="position: relative; margin-right: 0.5rem;">
                        <button type="button" class="elx-nav__notifications-trigger" id="notificationsTrigger" aria-expanded="false" aria-controls="notificationsDropdown" style="background: none; border: none; color: #fff; font-size: 1.35rem; cursor: pointer; position: relative; padding: 5px; display: flex; align-items: center; justify-content: center; height: 42px; width: 42px; border-radius: 50%; background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.1); transition: var(--elx-transition);">
                            <i class="fas fa-bell"></i>
                            @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="elx-nav__notifications-badge" style="position: absolute; top: -4px; right: -4px; background-color: #dc3545; color: white; font-size: 0.65rem; font-weight: bold; border-radius: 50%; padding: 2px 5px; min-width: 17px; text-align: center; line-height: 1;">{{ $unreadCount }}</span>
                            @endif
                        </button>
                        <div class="elx-nav__notifications-dropdown" id="notificationsDropdown" style="position: absolute; right: 0; top: calc(100% + 0.8rem); width: 320px; background: #13252d; border: 1px solid rgba(20, 204, 255, 0.15); border-radius: 24px; box-shadow: 0 24px 80px rgba(0,0,0,0.5); display: none; z-index: 1100; overflow: hidden; font-family: var(--elx-font);">
                            <div class="elx-nav__notifications-head" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border-bottom: 1px solid rgba(255,255,255,0.08); background: rgba(0,0,0,0.15);">
                                <strong style="color: #fff; font-size: 0.95rem;">{{ __('app.notifications') }}</strong>
                                @if($unreadCount > 0)
                                    <button onclick="markAllNotificationsAsRead(event)" class="elx-nav__notifications-clear" style="background: none; border: none; color: #4ac8f6; font-size: 0.8rem; cursor: pointer; padding: 0; font-weight: 500;">{{ __('app.mark_all_read') }}</button>
                                @endif
                            </div>
                            <div class="elx-nav__notifications-list" style="max-height: 350px; overflow-y: auto;">
                                @forelse(auth()->user()->notifications()->take(10)->get() as $notif)
                                    <div class="elx-nav__notifications-item {{ $notif->is_read ? '' : 'unread' }}" 
                                         onclick="handleNotificationClick(event, '{{ route('notifications.read', $notif->id) }}', '{{ $notif->url ?? '#' }}')"
                                         style="padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,0.08); cursor: pointer; transition: background 0.25s; background: {{ $notif->is_read ? 'rgba(0, 0, 0, 0.2)' : 'rgba(74, 200, 246, 0.12)' }}; text-align: left;">
                                        <div style="display: flex; align-items: start; gap: 8px;">
                                            @if(!$notif->is_read)
                                                <span class="unread-dot" style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: #4ac8f6; box-shadow: 0 0 8px #4ac8f6; margin-top: 6px; flex-shrink: 0;"></span>
                                            @endif
                                            <div style="flex-grow: 1;">
                                                <div class="notif-title" style="color: {{ $notif->is_read ? '#8fa4af' : '#4ac8f6' }}; font-weight: {{ $notif->is_read ? 'normal' : 'bold' }}; font-size: 0.9rem; margin-bottom: 4px;">{{ $notif->display_title }}</div>
                                                <div class="notif-message" style="color: rgba(255,255,255,0.6); font-size: 0.8rem; line-height: 1.4; margin-bottom: 6px;">{{ $notif->display_message }}</div>
                                                <div class="notif-time" style="color: rgba(255,255,255,0.35); font-size: 0.7rem;">{{ $notif->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="elx-nav__notifications-empty" style="padding: 32px 16px; text-align: center; color: rgba(255,255,255,0.4); font-size: 0.85rem;">
                                        <i class="fas fa-bell-slash" style="display: block; font-size: 1.8rem; margin-bottom: 10px; opacity: 0.4;"></i>
                                        {{ __('app.no_notifications') }}
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endauth

                {{-- Dark Mode Toggle --}}
                <button type="button" class="elx-nav__icon-btn" id="themeToggle" title="Toggle theme">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>

                {{-- Language Toggle --}}
                @if(app()->getLocale() === 'ar')
                    <a href="{{ route('lang.switch', 'en') }}" class="elx-nav__icon-btn elx-nav__lang-btn" title="Switch to English">EN</a>
                @else
                    <a href="{{ route('lang.switch', 'ar') }}" class="elx-nav__icon-btn elx-nav__lang-btn" title="التبديل إلى العربية">ع</a>
                @endif

                <a href="{{ route('cart.index') }}" class="elx-nav__cart" title="Cart">
                    <i class="fas fa-shopping-bag"></i>
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span class="elx-nav__cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <!-- ======================================================= -->
                <!-- قبل -->
               @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="elx-nav__btn elx-nav__btn--admin">
                            <i class="fas fa-cog"></i> <span>{{ __('app.admin_btn') }}</span>
                        </a>
                    @elseif(auth()->user()->role === 'vendor')
                        <a href="{{ route('vendor.dashboard') }}" class="elx-nav__btn elx-nav__btn--admin" style="background-color: #6a1b9a;">
                            <i class="fas fa-store"></i> <span>{{ __('app.vendor_portal') }}</span>
                        </a>
                    @endif
                    <div class="elx-nav__profile" id="profileMenu">
                        <button type="button" class="elx-nav__profile-trigger" id="profileTrigger" aria-expanded="false" aria-controls="profileDropdown">
                            <x-user-avatar :user="auth()->user()" size="34" class="elx-nav__profile-avatar" />
                            <span class="elx-nav__profile-meta">
                                <strong>{{ \Illuminate\Support\Str::limit(auth()->user()->name, 16) }}</strong>
                                <small>{{ __('app.my_account') }}</small>
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div class="elx-nav__profile-menu" id="profileDropdown">
                            <div class="elx-nav__profile-head">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <x-user-avatar :user="auth()->user()" size="42" />
                                    <div style="display: grid; gap: 0.15rem;">
                                        <strong>{{ auth()->user()->name }}</strong>
                                        <span>{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('profile.edit') }}">
                                <span>{{ __('app.profile_settings') }}</span>
                                <i class="fas fa-user"></i>
                            </a>
                            <a href="{{ route('profile.orders.index') }}">
                                <span>{{ __('app.orders') }}</span>
                                <i class="fas fa-receipt"></i>
                            </a>
                               <form method="POST" action="{{ route('logout') }}" class="elx-nav__profile-form">
                                @csrf
                                <button type="submit" class="elx-nav__profile-logout">
                                    <span>{{ __('app.logout') }}</span>
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="elx-nav__btn elx-nav__btn--login">{{ __('app.join_us') }}</a>
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


    {{-- FOOTER (Global) --}}
    <footer class="elx-footer"
        style="position: relative; overflow: visible; padding: 6rem 1rem 4rem; background-color: #13252d;">
        {{-- Cloud Decorations - Absolute positioned --}}
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
                <div class="elx-footer__grid"
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 4rem; margin-bottom: 4rem;">
                    <div class="elx-footer__brand">
                        <a href="{{ route('home') }}" style="display: block; margin-bottom: 2rem;">
                            <img src="https://framerusercontent.com/images/uXbQX90j2iRjfRCUW6NdMiNzUVM.png"
                                alt="Elixira Logo" style="height: 35px;">
                        </a>
                        <p style="color: rgba(255,255,255,0.6); font-size: 0.95rem; line-height: 1.6;">
                            {{ __('home.footer_tagline') }}
                        </p>
                    </div>

                    <div class="elx-footer__nav-group">
                        <h4
                            style="color: #4ac8f6; font-size: 1rem; margin-bottom: 1.5rem; letter-spacing: 1px; text-transform: uppercase;">
                            {{ __('home.footer_shop') }}</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li><a href="{{ route('menu.index') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">{{ __('home.footer_browse') }}</a></li>
                            <li><a href="{{ route('cart.index') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">{{ __('home.footer_cart') }}</a></li>
                            <li><a href="{{ route('orders.track') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">{{ __('home.footer_track') }}</a></li>
                        </ul>
                    </div>

                    <div class="elx-footer__nav-group">
                        <h4
                            style="color: #4ac8f6; font-size: 1rem; margin-bottom: 1.5rem; letter-spacing: 1px; text-transform: uppercase;">
                            {{ __('home.footer_about') }}</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li><a href="{{ route('about') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">{{ __('home.footer_ritual') }}</a></li>
                            <li><a href="{{ route('contact') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">{{ __('home.footer_contact') }}</a></li>
                            <li><a href="#"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">{{ __('home.footer_instagram') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Bottom info --}}
                <div
                    style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 2rem; display: flex; justify-content: space-between; align-items: center; color: rgba(255,255,255,0.5); font-size: 0.85rem;">
                    <span>{{ __('home.footer_rights', ['year' => date('Y')]) }}</span>
                    <div style="display: flex; gap: 2rem;">
                        <a href="#" style="color: inherit; text-decoration: none;">{{ __('home.footer_privacy') }}</a>
                        <a href="#" style="color: inherit; text-decoration: none;">{{ __('home.footer_terms') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const showInfoPopup = (message, icon = 'info') => {
            Swal.fire({
                icon,
                text: message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#1f8db5',
            });
        };

        @php
            $testimonialsWriteInlineAlerts =
                request()->routeIs('testimonials.index') && request()->query('tab') === 'write';
        @endphp

        @if(session('success'))
            @unless($testimonialsWriteInlineAlerts)
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
            @endunless
        @endif

        @if(session('error'))
            @unless($testimonialsWriteInlineAlerts)
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
            @endunless
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

        const navToggle = document.getElementById('navToggle');
        const navLinks = document.getElementById('navLinks');
        navToggle?.addEventListener('click', () => {
            navLinks.classList.toggle('open');
            navToggle.classList.toggle('active');
        });

        const profileMenu = document.getElementById('profileMenu');
        const profileTrigger = document.getElementById('profileTrigger');
        profileTrigger?.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            profileMenu.classList.toggle('open');
            profileTrigger.setAttribute('aria-expanded', profileMenu.classList.contains('open') ? 'true' : 'false');
            // Close notifications menu if open
            notificationsMenu?.classList.remove('open');
        });

        const notificationsMenu = document.getElementById('notificationsMenu');
        const notificationsTrigger = document.getElementById('notificationsTrigger');
        notificationsTrigger?.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            notificationsMenu.classList.toggle('open');
            notificationsTrigger.setAttribute('aria-expanded', notificationsMenu.classList.contains('open') ? 'true' : 'false');
            // Close profile menu if open
            profileMenu?.classList.remove('open');
        });

        document.addEventListener('click', (event) => {
            if (profileMenu && !profileMenu.contains(event.target)) {
                profileMenu.classList.remove('open');
                profileTrigger?.setAttribute('aria-expanded', 'false');
            }
            if (notificationsMenu && !notificationsMenu.contains(event.target)) {
                notificationsMenu.classList.remove('open');
                notificationsTrigger?.setAttribute('aria-expanded', 'false');
            }
        });

        function markAllNotificationsAsRead(event) {
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
                    const badge = document.querySelector('.elx-nav__notifications-badge');
                    if (badge) badge.remove();
                    document.querySelectorAll('.elx-nav__notifications-item').forEach(item => {
                        item.style.background = 'rgba(0, 0, 0, 0.2)';
                        item.classList.remove('unread');
                        const title = item.querySelector('.notif-title');
                        if (title) {
                            title.style.color = '#8fa4af';
                            title.style.fontWeight = 'normal';
                        }
                        const dot = item.querySelector('.unread-dot');
                        if (dot) dot.remove();
                    });
                    const clearBtn = document.querySelector('.elx-nav__notifications-clear');
                    if (clearBtn) clearBtn.remove();
                }
            });
        }

        function handleNotificationClick(event, readUrl, redirectUrl) {
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

        const nav = document.getElementById('elxNav');
        if (nav && !nav.classList.contains('scrolled-permanent')) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    nav.classList.add('scrolled');
                } else if (!document.body.classList.contains('home-page')) {
                    //add white bg
                    nav.classList.add('scrolled');
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

        function showSpecialRequestModal(itemId, itemName) {
            Swal.fire({
                title: `Special Order - ${itemName}`,
                html: `
                    <p style="margin-bottom: 1rem; font-size: 0.95rem; color: #9fb2bc;">
                        This product is currently out of stock. Leave your details and we will notify you on WhatsApp once it is available.
                    </p>
                    <input type="text" id="swal-name" style="width: 90%; padding: 0.75rem 1rem; background: #13252d; border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; border-radius: 8px; outline: none; font-size: 1rem; margin-bottom: 1rem;" placeholder="Your Name" value="{{ auth()->check() ? auth()->user()->name : '' }}" required>
                    <div style="display:flex; gap:0.5rem; align-items: center; margin: 0 auto 1rem; width: 90%;">
                        <select id="swal-country-code" style="width: 100px; padding: 0.75rem 0.5rem; background: #13252d; border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; border-radius: 8px; outline: none; font-size: 0.9rem;">
                            <option value="+966">+966</option>
                            <option value="+971">+971</option>
                        </select>
                        <input type="text" id="swal-phone" style="flex-grow: 1; padding: 0.75rem 1rem; background: #13252d; border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; border-radius: 8px; outline: none; font-size: 1rem;" placeholder="WhatsApp Number" required>
                    </div>
                    <input type="email" id="swal-email" style="width: 90%; padding: 0.75rem 1rem; background: #13252d; border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; border-radius: 8px; outline: none; font-size: 1rem;" placeholder="Email (optional)" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                `,
                background: '#0d1a20',
                color: '#eaf4f8',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Send Request',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#4ac8f6',
                cancelButtonColor: '#6c757d',
                preConfirm: () => {
                    const name = Swal.getPopup().querySelector('#swal-name').value.trim();
                    const phoneInput = Swal.getPopup().querySelector('#swal-phone').value.trim();
                    const countryCode = Swal.getPopup().querySelector('#swal-country-code').value;
                    const email = Swal.getPopup().querySelector('#swal-email').value.trim();
                    if (!phoneInput) {
                        Swal.showValidationMessage('Please enter your WhatsApp number.');
                        return false;
                    }
                    const phone = countryCode + phoneInput;
                    return { name: name, phone: phone, email: email, item_id: itemId };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('special-requests.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Request Sent',
                                text: 'Your special order request has been received. We will contact you soon.',
                                background: '#0d1a20',
                                color: '#eaf4f8',
                                confirmButtonColor: '#4ac8f6',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Failed',
                                text: data.message || 'We could not submit your request. Please try again.',
                                background: '#0d1a20',
                                color: '#eaf4f8',
                                confirmButtonColor: '#dc3545',
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Error',
                            text: 'A network error occurred while sending your request.',
                            background: '#0d1a20',
                            color: '#eaf4f8',
                            confirmButtonColor: '#dc3545',
                        });
                    });
                }
            });
        }

        // ─── Dark Mode Toggle ───
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;

        function applyTheme(theme) {
            if (theme === 'light') {
                body.classList.add('light-mode');
                if (themeIcon) { themeIcon.classList.remove('fa-moon'); themeIcon.classList.add('fa-sun'); }
            } else {
                body.classList.remove('light-mode');
                if (themeIcon) { themeIcon.classList.remove('fa-sun'); themeIcon.classList.add('fa-moon'); }
            }
        }

        const serverTheme = @json($userTheme ?? session('theme', 'dark'));
        applyTheme(serverTheme || localStorage.getItem('elx-theme') || 'dark');

        themeToggle?.addEventListener('click', () => {
            const current = body.classList.contains('light-mode') ? 'light' : 'dark';
            const next = current === 'dark' ? 'light' : 'dark';
            const url = next === 'light' ? @json(route('theme.switch', 'light')) : @json(route('theme.switch', 'dark'));

            fetch(url, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            }).finally(() => {
                localStorage.setItem('elx-theme', next);
                localStorage.setItem('theme', next);
                applyTheme(next);
            });
        });

        // ─── RTL: flip dropdown position for Arabic ───
        @if(app()->getLocale() === 'ar')
        document.querySelectorAll('.elx-nav__dropdown-menu').forEach(menu => {
            menu.style.left = 'auto';
            menu.style.right = '50%';
            menu.style.transform = 'translateX(50%)';
        });
        document.querySelectorAll('.elx-nav__dropdown:hover .elx-nav__dropdown-menu').forEach(menu => {
            menu.style.transform = 'translateX(50%) translateY(0)';
        });
        @endif
    </script>
    @yield('scripts')
    <script>
        window.setTimeout(function () {
            document.querySelectorAll('[data-animate]:not(.animate-in)').forEach(function (el) {
                el.classList.add('animate-in');
            });
        }, 2500);
    </script>
    
    <!-- Language Selector Component -->
    <x-language-selector />
</body>

</html>
