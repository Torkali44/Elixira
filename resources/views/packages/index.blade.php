@extends('layouts.framer')

@section('title', __('shop.packages_title'))

@section('head')
@include('partials.custom-country-dropdown-styles')
<style>
    .menu-empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 1rem;
        color: var(--elx-light);
    }
    .menu-products-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 3rem;
        justify-content: center;
        align-items: stretch;
    }
    @media (max-width: 1024px) {
        .menu-products-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 2rem; }
    }
    @media (max-width: 768px) {
        .menu-products-grid { grid-template-columns: 1fr; gap: 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title"><span class="elx-hero__title-gradient">{{ __('shop.packages_title') }}</span></h1>
            <p class="elx-hero__subtitle" style="margin-bottom: 0;">{{ __('shop.packages_subtitle') }}</p>
        </div>

        @php
            $pricingService = app(\App\Support\ItemPricingService::class);
            $countryFlags = $pricingService->countryFlags();
            $countryLabels = $pricingService->supportedCountries();
        @endphp

        <form method="GET" action="{{ route('packages.index') }}" class="menu-country-select text-center" data-animate style="margin: 2rem auto 3rem; max-width: 360px; position: relative; z-index: 10;">
            <label for="packages-country" class="menu-country-select__label">{{ __('shop.select_country') }}</label>

            <div class="custom-dropdown" data-country-dropdown>
                <div class="custom-dropdown__trigger">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        @if($countryFlags[$selectedCountry] ?? null)
                            <img src="{{ $countryFlags[$selectedCountry] }}" alt="" class="menu-country-select__flag" style="margin: 0; width: 24px; height: 16px; border-radius: 2px;">
                        @endif
                        <span>{{ $countryLabels[$selectedCountry] ?? $selectedCountry }}</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-arrow-icon" style="color: #4ac8f6; font-size: 0.85rem;"></i>
                </div>
                <div class="custom-dropdown__options">
                    @foreach($countryLabels as $code => $label)
                        <div class="custom-dropdown__option @if($selectedCountry === $code) active @endif" data-value="{{ $code }}">
                            @if($countryFlags[$code] ?? null)
                                <img src="{{ $countryFlags[$code] }}" alt="" style="width: 24px; height: 16px; border-radius: 2px; object-fit: cover;">
                            @endif
                            <span>{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="country" id="packages-country" data-country-input value="{{ $selectedCountry }}">
            </div>
        </form>

        <div class="elx-products__grid menu-products-grid" style="margin-top: 1rem;">
            @forelse($packages as $package)
                <div class="product-item" data-animate>
                    @include('partials.package-card', ['package' => $package, 'selectedCountry' => $selectedCountry])
                </div>
            @empty
                <div class="menu-empty-state" data-animate>
                    <i class="fas fa-globe-americas"></i>
                    <p>{{ __('shop.not_available_in_country') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('partials.custom-country-dropdown-script')
@endsection
