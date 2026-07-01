<li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('app.home') }}</a></li>
<li class="elx-nav__dropdown">
    <a href="{{ route('menu.index') }}"
        class="{{ request()->routeIs('menu.*') || request()->routeIs('packages.*') ? 'active' : '' }}">{{ __('app.shop') }} <i class="fas fa-chevron-down"></i></a>
    <div class="elx-nav__dropdown-menu">
        <a href="{{ route('menu.index') }}" class="{{ request()->routeIs('menu.*') ? 'active' : '' }}">{{ __('shop.all_products') }}</a>
        <a href="{{ route('packages.index') }}" class="{{ request()->routeIs('packages.*') ? 'active' : '' }}">{{ __('shop.packages_title') }}</a>
    </div>
</li>
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
<li>
    @auth
        <a href="{{ route('profile.orders.index') }}"
            class="{{ request()->routeIs('profile.orders.*') ? 'active' : '' }}">{{ __('app.orders') }}</a>
    @else
        <a href="{{ route('login') }}"
            class="{{ request()->routeIs('login') ? 'active' : '' }}">{{ __('app.orders') }}</a>
    @endauth
</li>
