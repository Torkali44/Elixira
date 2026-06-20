@extends('layouts.framer')

@section('title', __('shop.page_title'))

@section('content')
<div class="page-content">
    <div class="elx-container">
        {{-- Section Header --}}
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
            
            <div class="custom-dropdown" id="customMenuCountryDropdown">
                <div class="custom-dropdown__trigger">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        @if($countryFlags[$selectedCountry] ?? null)
                            <img src="{{ $countryFlags[$selectedCountry] }}" alt="" class="menu-country-select__flag" style="margin: 0; width: 24px; height: 16px; border-radius: 2px;">
                        @endif
                        <span>{{ $countryLabels[$selectedCountry] ?? $selectedCountry }}</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-arrow-icon" style="color: #4ac8f6; font-size: 0.85rem; transition: transform 0.3s ease;"></i>
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
                <input type="hidden" name="country" id="hiddenCountryInput" value="{{ $selectedCountry }}">
            </div>
        </form>

        {{-- Category Filter --}}
        <div class="menu-filter text-center mb-5" data-animate style="margin-bottom: 4rem;">
            <button class="filter-btn active" data-filter="all">{{ __('shop.filter_all') }}</button>
            @foreach($categories as $category)
                <button class="filter-btn" data-filter=".cat-{{ $category->id }}">{{ $category->local_name }}</button>
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
<script>
    // Simple filter script
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filter = btn.dataset.filter;
            const items = document.querySelectorAll('.product-item');
            
            items.forEach(item => {
                if (filter === 'all' || item.classList.contains(filter.substring(1))) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const dropdown = document.getElementById('customMenuCountryDropdown');
        if (!dropdown) return;
        
        const trigger = dropdown.querySelector('.custom-dropdown__trigger');
        const optionsMenu = dropdown.querySelector('.custom-dropdown__options');
        const arrow = dropdown.querySelector('.dropdown-arrow-icon');
        const hiddenInput = document.getElementById('hiddenCountryInput');
        const form = dropdown.closest('form');
        
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = optionsMenu.style.display === 'block';
            optionsMenu.style.display = isOpen ? 'none' : 'block';
            if (arrow) {
                arrow.style.transform = isOpen ? 'none' : 'rotate(180deg)';
            }
        });
        
        dropdown.querySelectorAll('.custom-dropdown__option').forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                const val = option.dataset.value;
                hiddenInput.value = val;
                optionsMenu.style.display = 'none';
                if (arrow) arrow.style.transform = 'none';
                form.submit();
            });
        });
        
        document.addEventListener('click', () => {
            optionsMenu.style.display = 'none';
            if (arrow) arrow.style.transform = 'none';
        });
    });
</script>
<style>
    .menu-country-select__label {
        display: block;
        margin-bottom: 0.75rem;
        color: var(--elx-light);
        font-size: 0.9rem;
        font-weight: 600;
    }
    .custom-dropdown {
        position: relative;
        text-align: start;
        cursor: pointer;
        user-select: none;
    }
    .custom-dropdown__trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.65rem 1.25rem;
        border-radius: 50px;
        background: var(--elx-glass);
        border: 1px solid var(--elx-border);
        backdrop-filter: blur(10px);
        color: var(--elx-white);
        font-family: 'Istok Web', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .custom-dropdown__trigger:hover {
        border-color: rgba(74, 200, 246, 0.5);
        box-shadow: 0 0 10px rgba(74, 200, 246, 0.15);
    }
    .custom-dropdown__options {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        background: #13252d;
        border: 1px solid rgba(74, 200, 246, 0.3);
        border-radius: 16px;
        overflow: hidden;
        z-index: 1000;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .custom-dropdown__option {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.25rem;
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
        transition: background 0.2s, color 0.2s;
        border-bottom: 1px solid rgba(255,255,255,0.03);
    }
    .custom-dropdown__option:last-child {
        border-bottom: none;
    }
    .custom-dropdown__option:hover {
        background: rgba(74, 200, 246, 0.15) !important;
        color: #4ac8f6;
    }
    .custom-dropdown__option.active {
        background: rgba(74, 200, 246, 0.2) !important;
        color: #4ac8f6;
    }
    .menu-country-select__control {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 1rem;
        border-radius: 50px;
        background: var(--elx-glass);
        border: 1px solid var(--elx-border);
        backdrop-filter: blur(10px);
    }
    .menu-country-select__flag {
        width: 28px;
        height: 18px;
        border-radius: 3px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .menu-country-select__dropdown {
        flex: 1;
        background: transparent;
        border: none;
        color: var(--elx-white);
        font-family: 'Istok Web', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        outline: none;
        cursor: pointer;
        appearance: none;
        padding-right: 1.5rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8' fill='none'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%234ac8f6' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right center;
    }
    .menu-country-select__dropdown option {
        color: #13252d;
        background: #fff;
    }
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
    }
    .filter-btn.active, .filter-btn:hover {
        background: linear-gradient(135deg, var(--elx-cyan), var(--elx-accent));
        color: var(--elx-dark);
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(74, 200, 246, 0.3);
    }
    .category-section {
        margin-bottom: 6rem !important;
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
    }
</style>
@endsection
