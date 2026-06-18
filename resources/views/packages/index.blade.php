@extends('layouts.framer')

@section('title', __('shop.packages_title'))

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title"><span class="elx-hero__title-gradient">{{ __('shop.packages_title') }}</span></h1>
            <p class="elx-hero__subtitle" style="margin-bottom: 0;">{{ __('shop.packages_subtitle') }}</p>
        </div>

        <div class="elx-products__grid menu-products-grid" style="margin-top: 3rem;">
            @forelse($packages as $package)
                <a href="{{ route('packages.show', $package) }}" class="product-item" data-animate style="text-decoration: none;">
                    <div class="elx-product-card">
                        <div class="elx-product-card__img">
                            @if($package->image)
                                <img src="{{ asset('storage/'.$package->image) }}" alt="{{ $package->local_name }}">
                            @else
                                <div style="height: 100%; display:flex; align-items:center; justify-content:center; background: rgba(255,255,255,0.05); color: #4ac8f6;">
                                    <i class="fas fa-box-open fa-2x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="elx-product-card__body">
                            <span style="color: #ffd700; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">{{ __('shop.package_badge') }}</span>
                            <h3>{{ $package->local_name }}</h3>
                            <p style="color: rgba(255,255,255,0.65); font-size: 0.9rem;">{{ Str::limit($package->local_description, 90) }}</p>
                            <div style="color: #4ac8f6; font-weight: 700;">﷼ {{ number_format($package->display_price, 2) }}</div>
                        </div>
                    </div>
                </a>
            @empty
                <p style="color: var(--elx-gray); grid-column: 1 / -1; text-align: center;">{{ __('shop.packages_empty') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
