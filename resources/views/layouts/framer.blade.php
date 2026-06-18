<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <script>
        document.documentElement.classList.add('elx-animate-scroll');
        // Immediately apply saved theme to prevent FOUC
        (function() {
            document.body.classList.remove('light-mode');
            localStorage.setItem('elx-theme', 'dark');
            localStorage.setItem('theme', 'dark');
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
        .elx-footer button:hover {
            color: #4ac8f6 !important;
        }
        .elx-legal-modal {
            position: fixed;
            inset: 0;
            z-index: 3000;
            background: rgba(0, 0, 0, 0.75);
            padding: 1rem;
            align-items: center;
            justify-content: center;
        }
        .elx-legal-modal__dialog {
            background: #13252d;
            border: 1px solid rgba(74, 200, 246, 0.25);
            border-radius: 20px;
            max-width: 760px;
            width: 100%;
            max-height: 90vh;
            overflow: auto;
            padding: 1rem 1.25rem 1.35rem;
        }
        .elx-legal-modal__head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }
        .elx-legal-modal__head h3 {
            margin: 0;
            color: #fff;
            font-size: 1.05rem;
        }
        .elx-legal-modal__close {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            line-height: 1;
        }
        .elx-legal-modal__body p {
            color: rgba(255, 255, 255, 0.78);
            line-height: 1.7;
            margin: 0 0 0.85rem;
        }
        .elx-legal-modal__body p:last-child {
            margin-bottom: 0;
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
        .elx-nav__dropdown > a {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            white-space: nowrap;
        }
        .elx-nav__dropdown-menu {
            position: absolute;
            top: 100%;
            inset-inline-start: 50%;
            transform: translateX(-50%);
            min-width: 180px;
            padding: 0.5rem;
            padding-top: 0.85rem;
            border-radius: 16px;
            background: rgba(6, 15, 20, 0.97);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 16px 50px rgba(0,0,0,0.4);
            backdrop-filter: blur(20px);
            opacity: 0;
            pointer-events: none;
            transform: translateX(-50%) translateY(-4px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            z-index: 1050;
        }
        .elx-nav__dropdown-menu::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 0.85rem;
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
            margin: 0;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }
        .elx-nav__dropdown:hover > a .fa-chevron-down {
            transform: rotate(180deg);
        }

        /* ─── Theme & Lang Toggle Buttons ─── */
        .elx-nav__icon-btn {
            width: 40px;
            height: 40px;
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
            height: 40px;
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
                <li><a href="{{ route('packages.index') }}"
                        class="{{ request()->routeIs('packages.*') ? 'active' : '' }}">{{ __('shop.packages_title') }}</a></li>
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

            <div class="elx-nav__actions">
                <form action="{{ route('search.index') }}" method="GET" class="d-none d-lg-flex" style="margin-inline-end: 0.5rem;">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('shop.search_placeholder') }}"
                        style="width: 180px; padding: 0.45rem 0.9rem; border-radius: 999px; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #fff; font-size: 0.85rem;">
                </form>
                <div class="elx-nav__actions-cluster">
                @auth
                    <div class="elx-nav__notifications-wrapper" id="notificationsMenu">
                        <button type="button" class="elx-nav__notifications-trigger" id="notificationsTrigger" aria-expanded="false" aria-controls="notificationsDropdown">
                            <i class="fas fa-bell"></i>
                            @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="elx-nav__notifications-badge">{{ $unreadCount }}</span>
                            @endif
                        </button>
                        <div class="elx-nav__notifications-dropdown" id="notificationsDropdown" style="position: absolute; inset-inline-end: 0; top: calc(100% + 0.8rem); width: 320px; background: #13252d; border: 1px solid rgba(20, 204, 255, 0.15); border-radius: 24px; box-shadow: 0 24px 80px rgba(0,0,0,0.5); display: none; z-index: 1100; overflow: hidden; font-family: var(--elx-font);">
                            <div class="elx-nav__notifications-head" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border-bottom: 1px solid rgba(255,255,255,0.08); background: rgba(0,0,0,0.15);">
                                <strong style="color: #fff; font-size: 0.95rem;">{{ __('app.notifications') }}</strong>
                                @if($unreadCount > 0)
                                    <button onclick="markAllNotificationsAsRead(event)" class="elx-nav__notifications-clear" style="background: none; border: none; color: #4ac8f6; font-size: 0.8rem; cursor: pointer; padding: 0; font-weight: 500;">{{ __('app.mark_all_read') }}</button>
                                @endif
                            </div>
                            <div class="elx-nav__notifications-list" style="max-height: 350px; overflow-y: auto;">
                                @forelse(auth()->user()->notifications()->take(10)->get() as $notif)
                                    <div class="elx-nav__notifications-item {{ $notif->is_read ? '' : 'unread' }}"
                                         data-read-url="{{ route('notifications.read', $notif->id) }}"
                                         data-redirect-url="{{ $notif->url ?: route('home') }}"
                                         onclick="handleNotificationClick(event, this)"
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

                {{-- Language Toggle --}}
                @php $langQuery = request()->getQueryString(); @endphp
                @if(app()->getLocale() === 'ar')
                    <a href="{{ route('lang.switch', 'en') }}{{ $langQuery ? '?'.$langQuery : '' }}" class="elx-nav__icon-btn elx-nav__lang-btn" title="Switch to English" onclick="return preserveVendorOnboardingStep(this, event)">EN</a>
                @else
                    <a href="{{ route('lang.switch', 'ar') }}{{ $langQuery ? '?'.$langQuery : '' }}" class="elx-nav__icon-btn elx-nav__lang-btn" title="التبديل إلى العربية" onclick="return preserveVendorOnboardingStep(this, event)">ع</a>
                @endif

                <a href="{{ route('cart.index') }}" class="elx-nav__cart" title="Cart">
                    <i class="fas fa-shopping-bag"></i>
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span class="elx-nav__cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                </div>

                <div class="elx-nav__actions-user">
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
                </div>
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
                            <li><a href="{{ route('blogs.index') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">{{ __('home.footer_blog') }}</a>
                            </li>
                            <li><a href="{{ route('dxn-distributor.create') }}"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none; display: block; padding: 0.5rem 0;">{{ __('home.footer_join_dxn') }}</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Social Links Column --}}
                    <div class="elx-footer__nav-group">
                        <h4 style="color: #4ac8f6; font-size: 1rem; margin-bottom: 1.5rem; letter-spacing: 1px; text-transform: uppercase;">
                            {{ __('home.footer_follow') }}
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <a href="https://www.instagram.com/__elixira?igsh=bjl6d3FtMnk1a2V1"
                               target="_blank" rel="noopener"
                               style="display: inline-flex; align-items: center; gap: 0.6rem; color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;"
                               onmouseover="this.style.color='#4ac8f6'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">
                                <i class="fab fa-instagram" style="width:18px;"></i> Instagram
                            </a>
                            <a href="https://www.facebook.com/profile.php?id=61590957652478"
                               target="_blank" rel="noopener"
                               style="display: inline-flex; align-items: center; gap: 0.6rem; color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;"
                               onmouseover="this.style.color='#4ac8f6'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">
                                <i class="fab fa-facebook-f" style="width:18px;"></i> Facebook
                            </a>
                            <a href="https://wa.me/971545920050"
                               target="_blank" rel="noopener"
                               style="display: inline-flex; align-items: center; gap: 0.6rem; color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;"
                               onmouseover="this.style.color='#4ac8f6'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">
                                <i class="fab fa-whatsapp" style="width:18px;"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Bottom info --}}
                <div
                    style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 2rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; color: rgba(255,255,255,0.5); font-size: 0.85rem;">
                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem;">
                        <span>{{ __('home.footer_rights', ['year' => date('Y')]) }}</span>
                        <span style="opacity: 0.45;">|</span>
                        <button type="button" id="openCrCertificateModal"
                                style="background: none; border: none; padding: 0; color: #4ac8f6; cursor: pointer; font-size: inherit; text-decoration: underline;">
                            {{ __('home.footer_cr_label') }}: {{ config('company.commercial_registration_number') }}
                        </button>
                    </div>
                    <div style="display: flex; gap: 2rem;">
                        <button type="button" id="openPrivacyModal"
                                style="background: none; border: none; padding: 0; color: inherit; cursor: pointer; font-size: inherit; text-decoration: none;">
                            {{ __('home.footer_privacy') }}
                        </button>
                        <button type="button" id="openTermsModal"
                                style="background: none; border: none; padding: 0; color: inherit; cursor: pointer; font-size: inherit; text-decoration: none;">
                            {{ __('home.footer_terms') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div id="crCertificateModal" style="display:none; position:fixed; inset:0; z-index:3000; background:rgba(0,0,0,0.75); padding:1rem; align-items:center; justify-content:center;">
        <div style="background:#13252d; border:1px solid rgba(74,200,246,0.25); border-radius:20px; max-width:900px; width:100%; max-height:90vh; overflow:auto; padding:1rem 1rem 1.25rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:0.75rem;">
                <h3 style="margin:0; color:#fff; font-size:1.05rem;">{{ __('home.footer_cr_modal_title') }}</h3>
                <button type="button" id="closeCrCertificateModal" style="background:none; border:none; color:#fff; font-size:1.5rem; cursor:pointer; line-height:1;">&times;</button>
            </div>
            <img src="{{ asset(config('company.commercial_registration_image')) }}"
                 alt="{{ __('home.footer_cr_modal_title') }}"
                 style="width:100%; height:auto; border-radius:12px; display:block;">
        </div>
    </div>

    <div id="privacyModal" class="elx-legal-modal" style="display:none;">
        <div class="elx-legal-modal__dialog">
            <div class="elx-legal-modal__head">
                <h3>{{ __('home.footer_privacy_modal_title') }}</h3>
                <button type="button" class="elx-legal-modal__close" data-close-modal="privacyModal">&times;</button>
            </div>
            <div class="elx-legal-modal__body">
                @foreach(__('home.privacy_policy_clauses') as $clause)
                    <p>{{ $clause }}</p>
                @endforeach
            </div>
        </div>
    </div>

    <div id="termsModal" class="elx-legal-modal" style="display:none;">
        <div class="elx-legal-modal__dialog">
            <div class="elx-legal-modal__head">
                <h3>{{ __('home.footer_terms_modal_title') }}</h3>
                <button type="button" class="elx-legal-modal__close" data-close-modal="termsModal">&times;</button>
            </div>
            <div class="elx-legal-modal__body">
                @foreach(__('home.terms_of_service_clauses') as $clause)
                    <p>{{ $clause }}</p>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function () {
            const crModal = document.getElementById('crCertificateModal');
            const openCr = document.getElementById('openCrCertificateModal');
            const closeCr = document.getElementById('closeCrCertificateModal');

            if (openCr && crModal) {
                openCr.addEventListener('click', () => {
                    crModal.style.display = 'flex';
                });
            }

            if (closeCr && crModal) {
                closeCr.addEventListener('click', () => {
                    crModal.style.display = 'none';
                });
            }

            if (crModal) {
                crModal.addEventListener('click', (event) => {
                    if (event.target === crModal) {
                        crModal.style.display = 'none';
                    }
                });
            }

            const bindLegalModal = (openId, modalId) => {
                const openBtn = document.getElementById(openId);
                const modal = document.getElementById(modalId);

                if (!openBtn || !modal) {
                    return;
                }

                openBtn.addEventListener('click', () => {
                    modal.style.display = 'flex';
                });

                modal.querySelectorAll('[data-close-modal]').forEach((button) => {
                    button.addEventListener('click', () => {
                        modal.style.display = 'none';
                    });
                });

                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            };

            bindLegalModal('openPrivacyModal', 'privacyModal');
            bindLegalModal('openTermsModal', 'termsModal');
        })();
    </script>
    <script>
        const showInfoPopup = (message, icon = 'info') => {
            Swal.fire({
                icon,
                text: message,
                confirmButtonText: @json(__('popups.ok')),
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
                    title: @json(__('popups.success_title')),
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
                    title: @json(__('popups.error_title')),
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
                    title: @json(__('popups.confirm_title')),
                    text: form.dataset.confirm,
                    showCancelButton: true,
                    confirmButtonText: @json(__('popups.yes')),
                    cancelButtonText: @json(__('popups.cancel')),
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

        document.querySelectorAll('.elx-nav__dropdown').forEach((dropdown) => {
            let closeTimer = null;

            const openMenu = () => {
                if (closeTimer) {
                    clearTimeout(closeTimer);
                    closeTimer = null;
                }
                dropdown.classList.add('open');
            };

            const scheduleClose = () => {
                closeTimer = setTimeout(() => dropdown.classList.remove('open'), 350);
            };

            dropdown.addEventListener('mouseenter', openMenu);
            dropdown.addEventListener('mouseleave', scheduleClose);
            dropdown.addEventListener('focusin', openMenu);
            dropdown.addEventListener('focusout', (event) => {
                if (!dropdown.contains(event.relatedTarget)) {
                    scheduleClose();
                }
            });
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

        function handleNotificationClick(event, element) {
            event.preventDefault();
            event.stopPropagation();

            const readUrl = element.dataset.readUrl;
            const redirectUrl = element.dataset.redirectUrl;

            fetch(readUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            }).finally(() => {
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                } else {
                    window.location.reload();
                }
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

        // ─── Preserve vendor onboarding step when switching language ───
        function preserveVendorOnboardingStep(link, event) {
            if (!window.location.pathname.includes('/vendor/onboarding')) {
                return true;
            }

            event.preventDefault();
            const step = localStorage.getItem('vendor_onboarding_step') || new URLSearchParams(window.location.search).get('step') || '1';
            const url = new URL(link.href, window.location.origin);
            url.searchParams.set('step', step);
            window.location.href = url.toString();

            return false;
        }

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

    <a href="https://wa.me/971545920050" target="_blank" rel="noopener noreferrer" class="whatsapp-btn" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <style>
        .whatsapp-btn {
            position: fixed;
            bottom: 30px;
            {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 30px;
            background-color: #25D366;
            color: white;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            z-index: 2000;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .whatsapp-btn:hover {
            transform: scale(1.1);
            color: white;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }
    </style>
</body>

</html>
