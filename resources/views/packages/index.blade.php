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
                <div class="product-item" data-animate>
                    @include('partials.package-card', ['package' => $package])
                </div>
            @empty
                <p style="color: var(--elx-gray); grid-column: 1 / -1; text-align: center;">{{ __('shop.packages_empty') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
