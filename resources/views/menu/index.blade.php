@extends('layouts.framer')

@section('title', __('shop.page_title'))

@section('head')
@include('partials.custom-country-dropdown-styles')
<style>
    .menu-empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 1rem;
        color: var(--elx-light);
    }
    .menu-empty-state i {
        font-size: 2.5rem;
        color: rgba(255, 255, 255, 0.2);
        margin-bottom: 1rem;
        display: block;
    }
    .filter-btn {
        background: var(--elx-glass);
        border: 1px solid var(--elx-border);
        color: var(--elx-white);
        padding: 0.7rem 2rem;
        margin: 0.5rem;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s;
        font-family: 'Istok Web', sans-serif;
        font-weight: 600;
        backdrop-filter: blur(10px);
        touch-action: manipulation;
    }
    .filter-btn.active, .filter-btn:hover {
        background: linear-gradient(135deg, var(--elx-cyan), var(--elx-accent));
        color: var(--elx-dark);
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(74, 200, 246, 0.3);
    }
    .menu-products-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 3rem;
        justify-content: center;
        align-items: stretch;
    }
    .menu-products-grid .product-item {
        width: 100%;
        min-width: 0;
    }
    @media (max-width: 1024px) {
        .menu-products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 2rem;
        }
    }
    @media (max-width: 768px) {
        .menu-products-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .filter-btn {
            padding: 0.65rem 1.25rem;
            margin: 0.35rem;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">{{ __('shop.hero_title') }}</span>
            </h1>
            <p class="elx-hero__subtitle" style="margin-bottom: 0;">{{ __('shop.hero_subtitle') }}</p>
        </div>

        @php
            $pricingService = app(\App\Support\ItemPricingService::class);
            $countryFlags = $pricingService->countryFlags();
            $countryLabels = $pricingService->supportedCountries();
        @endphp

        <form method="GET" action="{{ route('menu.index') }}" class="menu-country-select text-center" data-animate style="margin: 2rem auto 3rem; max-width: 360px; position: relative; z-index: 10;">
            <label for="menu-country" class="menu-country-select__label">{{ __('shop.select_country') }}</label>

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
                <input type="hidden" name="country" id="menu-country" data-country-input value="{{ $selectedCountry }}">
            </div>
        </form>

        <div class="menu-filter text-center mb-5" data-animate style="margin-bottom: 4rem;">
            <button type="button" class="filter-btn active" data-filter="all">{{ __('shop.filter_all') }}</button>
            @foreach($categories as $category)
                <button type="button" class="filter-btn" data-filter=".cat-{{ $category->id }}">{{ $category->local_name }}</button>
            @endforeach
        </div>

        <div class="elx-products__grid menu-products-grid" id="products-grid" style="margin-bottom: 6rem;">
            @forelse($items as $product)
                <div class="product-item cat-{{ $product->category_id }}" data-animate>
                    @include('partials.product-card', ['product' => $product, 'selectedCountry' => $selectedCountry])
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
<script>
    document.querySelectorAll('.filter-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-btn').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.filter;
            const items = document.querySelectorAll('.product-item');

            items.forEach((item) => {
                if (filter === 'all' || item.classList.contains(filter.substring(1))) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
