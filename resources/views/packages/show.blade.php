@extends('layouts.framer')

@section('title', $package->local_name)

@section('head')
<style>
    .package-hero-section {
        background: linear-gradient(180deg, #0d1a20 0%, #000 100%);
        padding: 80px 0 60px;
    }
    .package-detail-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
        gap: 3rem;
        align-items: start;
    }
    .package-image-wrap {
        position: sticky;
        top: 100px;
        align-self: start;
        border-radius: 28px;
        overflow: visible;
        background: transparent;
        border: none;
        box-shadow: none;
        z-index: 2;
    }
    .package-image-wrap .pkg-badge {
        position: absolute;
        top: 1.25rem;
        {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 1.25rem;
        background: linear-gradient(135deg, #ffd700, #ffaa00);
        color: #000;
        padding: 0.4rem 1rem;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        box-shadow: 0 4px 15px rgba(255,215,0,0.4);
        z-index: 3;
    }
    .package-gallery-placeholder {
        aspect-ratio: 1/1;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--elx-cyan);
        font-size: 5rem;
        opacity: 0.4;
        border-radius: 24px;
        border: 1px dashed rgba(74, 200, 246, 0.25);
        background: rgba(255,255,255,0.02);
    }
    .package-info-panel {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 24px;
        padding: 2.5rem;
        backdrop-filter: blur(10px);
        min-width: 0;
    }
    .package-includes-list {
        list-style: none;
        padding: 0;
        margin: 0 0 2rem;
        display: grid;
        gap: 0.75rem;
    }
    .package-includes-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 14px;
        padding: 0.85rem 1rem;
        transition: background 0.2s ease;
    }
    .package-includes-item:hover {
        background: rgba(74,200,246,0.06);
        border-color: rgba(74,200,246,0.15);
    }
    .package-cta-row {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
        margin-top: 0.5rem;
    }
    .product-size-select-wrap {
        margin: 0 0 1.25rem;
    }
    .product-size-select-wrap label {
        display: block;
        margin-bottom: 0.45rem;
        font-size: 0.9rem;
        color: rgba(255,255,255,0.7);
        font-weight: 600;
    }
    .product-size-select {
        width: 100%;
        max-width: 320px;
        font-size: 1.05rem;
        background: #1a232b;
        color: #fff;
        border: 1px solid rgba(74, 200, 246, 0.35);
        border-radius: 12px;
        padding: 0.7rem 0.9rem;
    }
    [dir="rtl"] .product-size-select {
        text-align: right;
    }
    .product-size-select:focus {
        outline: none;
        border-color: #4ac8f6;
        box-shadow: 0 0 0 3px rgba(74, 200, 246, 0.15);
    }
    .product-size-select option {
        background: #1a232b;
        color: #fff;
    }
    .elx-product-pricing__price-line--guest,
    .elx-product-pricing__price-line--guest .elx-product-pricing__amount,
    .elx-product-pricing__price-line--guest .elx-product-pricing__currency {
        color: rgba(255, 255, 255, 0.42) !important;
        text-decoration: line-through;
        font-weight: 600;
    }
    @media (max-width: 991px) {
        .package-detail-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .package-image-wrap {
            position: relative;
            top: 0;
        }
        .package-info-panel { padding: 1.75rem; }
        .package-cta-row { flex-direction: column; }
        .package-cta-row .elx-btn { width: 100%; min-width: unset; justify-content: center; }
        .product-size-select { max-width: 100%; }
    }
    @media (max-width: 640px) {
        .package-hero-section { padding: 64px 0 36px; }
        .package-info-panel { padding: 1.15rem; }
        .package-image-wrap { margin-inline-end: 0; }
        .elx-image-gallery__thumbs-wrap { padding-inline-end: 3.25rem; }
        .package-cta-row > div { width: 100%; justify-content: space-between; }
    }
</style>
@endsection

@section('content')
@php
    $pricingService = app(\App\Support\ItemPricingService::class);
    $packagePricingService = app(\App\Support\PackagePricingService::class);
    $resolvedStock = $packagePricingService->resolveStock($package, $selectedCountry ?? null, $selectedCountryPriceId ?? null);
    $displayRewardPoints = $pricingService->resolvePackageRewardPoints($package, $selectedCountry ?? null, $selectedCountryPriceId ?? null);
    $hasSizeOptions = ($countryVariants ?? collect())->isNotEmpty()
        && (($countryVariants->count() > 1) || $countryVariants->contains(fn ($variant) => filled($variant->local_size)));
    $currentSizeLabel = $selectedVariant?->local_size ?: $package->local_size;
    $variantIsOutOfStock = $resolvedStock <= 0;
    $variantPayload = ($countryVariants ?? collect())->map(function ($variant) use ($package, $packagePricingService, $selectedCountry) {
        $stock = $packagePricingService->resolveStock($package, $selectedCountry, $variant->id);
        $member = (float) $variant->member_price;
        $guest = (float) $variant->guest_price;
        if ($guest <= 0) {
            $guest = $member;
        }

        return [
            'id' => $variant->id,
            'size' => $variant->local_size ?: __('shop.default_size'),
            'stock' => $stock,
            'max_qty' => $stock,
            'member_price' => min($member, $guest),
            'guest_price' => max($member, $guest),
            'points' => (int) ($variant->reward_points ?? 0),
            'out_of_stock' => $stock <= 0,
        ];
    })->values();
    $packageGalleryImages = $package->image
        ? collect([['url' => \App\Support\StorageUrl::asset($package->image)]])
        : collect();
@endphp
<div class="page-content" style="padding-top: 0;">

    <section class="package-hero-section">
        <div class="elx-container">
            <div class="package-detail-grid">

                {{-- Image Column --}}
                <div class="package-image-wrap" data-animate>
                    @if($packageGalleryImages->isNotEmpty())
                        @include('partials.product-image-gallery', [
                            'images' => $packageGalleryImages,
                            'alt' => $package->local_name,
                            'galleryId' => 'package-gallery',
                        ])
                    @else
                        <div class="package-gallery-placeholder"><i class="fas fa-box-open"></i></div>
                    @endif
                    <span class="pkg-badge">{{ __('shop.package_badge') }}</span>
                </div>

                {{-- Info Column --}}
                <div class="package-info-panel" data-animate>
                    <div style="margin-bottom: 1.25rem;">
                        <span style="color: rgba(255,255,255,0.4); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em;">{{ __('shop.package_badge') }}</span>
                        <h1 style="font-size: clamp(1.6rem, 3.5vw, 2.25rem); font-weight: 800; margin: 0.4rem 0 0; background: linear-gradient(135deg, #fff 0%, #4ac8f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.2; text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};">
                            {{ $package->local_name }}
                        </h1>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <x-package-pricing
                            :package="$package"
                            :selected-country="$selectedCountry"
                            :country-price-id="$selectedCountryPriceId"
                            size="2rem"
                            smallSize="1rem"
                            countrySelector="dropdown"
                            countryInputId="package-show-country"
                        />
                    </div>

                    @if($hasSizeOptions)
                        <div class="product-size-select-wrap">
                            <label for="package-size-select">{{ __('shop.select_size') }}</label>
                            <select id="package-size-select" class="product-size-select">
                                @foreach($countryVariants as $variant)
                                    @php
                                        $variantStock = $packagePricingService->resolveStock($package, $selectedCountry, $variant->id);
                                    @endphp
                                    <option value="{{ $variant->id }}" @selected($selectedCountryPriceId === $variant->id)>
                                        {{ $variant->local_size ?: __('shop.default_size') }}
                                        @if($variantStock <= 0)
                                            — {{ __('shop.out_of_stock') }}
                                        @else
                                            ({{ __('shop.in_stock', ['count' => $variantStock]) }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @elseif(filled($currentSizeLabel))
                        <div class="product-detail__size" style="margin-bottom: 1rem;">
                            <span class="product-detail__size-label">{{ __('shop.size') }}:</span>
                            <span class="product-detail__size-value" id="package-size-display">{{ $currentSizeLabel }}</span>
                        </div>
                    @endif

                    @if($displayRewardPoints > 0 || (int) $resolvedStock > 0)
                    <div style="display: flex; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                        @if($displayRewardPoints > 0)
                            <span id="package-points-badge" style="background: rgba(0,255,136,0.1); color:#00ff88; padding:0.3rem 0.75rem; border-radius:999px; font-size:0.8rem; font-weight:600; border:1px solid rgba(0,255,136,0.2);">
                                <i class="fas fa-star"></i> <span id="package-points-text">{{ __('home.reward_points', ['count' => $displayRewardPoints]) }}</span>
                            </span>
                        @endif
                        <span id="package-stock-badge" style="background: rgba(74,200,246,0.1); color:#4ac8f6; padding:0.3rem 0.75rem; border-radius:999px; font-size:0.8rem; font-weight:600; border:1px solid rgba(74,200,246,0.2); {{ $resolvedStock > 0 ? '' : 'display:none;' }}">
                            <i class="fas fa-check-circle"></i> <span id="package-stock-text">{{ __('shop.in_stock', ['count' => $resolvedStock]) }}</span>
                        </span>
                    </div>
                    @endif

                    @if($package->items->isNotEmpty())
                        <div style="margin-bottom: 1.75rem;">
                            <h3 style="color: rgba(255,255,255,0.9); margin-bottom: 0.85rem; font-size: 0.95rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-box" style="color: #4ac8f6; font-size: 0.85rem;"></i>
                                {{ __('shop.package_includes') }}
                            </h3>
                            <ul class="package-includes-list">
                                @foreach($package->items as $included)
                                    <li class="package-includes-item">
                                        @if($included->image)
                                            <img src="{{ \App\Support\StorageUrl::asset($included->image) }}" alt="" loading="lazy" decoding="async" style="width: 44px; height: 44px; border-radius: 10px; object-fit: cover; flex-shrink: 0;">
                                        @else
                                            <div style="width:44px;height:44px;border-radius:10px;background:rgba(74,200,246,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-box" style="color:#4ac8f6;font-size:1.1rem;"></i></div>
                                        @endif
                                        <div style="min-width:0;">
                                            <a href="{{ route('menu.show', $included) }}" style="color: #4ac8f6; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $included->local_name }}</a>
                                            <div style="color: rgba(255,255,255,0.45); font-size: 0.78rem; margin-top: 0.15rem;"><i class="fas fa-times" style="font-size:0.65rem;"></i> {{ $included->pivot->quantity }}</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div id="package-purchase-oos" style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.25rem; {{ $variantIsOutOfStock ? '' : 'display:none;' }}">
                        <p id="package-oos-message" style="color:#ff8a8a; font-weight:600; margin:0;"><i class="fas fa-exclamation-circle"></i> {{ __('shop.package_out_of_stock') }}</p>
                    </div>

                    <div id="package-purchase-instock" style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.5rem; {{ $variantIsOutOfStock ? 'display:none;' : '' }}">
                        <form action="{{ route('cart.add-package') }}" method="POST" id="package-show-form">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <input type="hidden" name="country_code" value="{{ $selectedCountry }}" id="package-show-country">
                            <input type="hidden" name="country_price_id" value="{{ $selectedCountryPriceId }}" id="package-country-price-id">
                            <div class="package-cta-row">
                                <div style="display:flex; align-items:center; gap:0.5rem; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:0.5rem 0.75rem;">
                                    <label style="color:rgba(255,255,255,0.5); font-size:0.8rem; font-weight:600; white-space:nowrap;">{{ app()->getLocale() === 'ar' ? 'الكمية' : 'Qty' }}</label>
                                    <input type="number" name="quantity" id="package-quantity" value="1" min="1" max="{{ max(1, $resolvedStock) }}" style="width:60px; background:transparent; border:none; color:#fff; font-size:1rem; font-weight:700; outline:none; text-align:center;">
                                </div>
                                <button type="submit" class="elx-btn elx-btn--primary" style="flex:1; min-width:160px; justify-content:center;" onclick="addToCartAjax(this, event)">
                                    <i class="fas fa-shopping-cart"></i> {{ __('home.add_to_cart') }}
                                </button>
                                <button type="submit" name="buy_now" value="1" class="elx-btn elx-btn--glass" style="flex:1; min-width:160px; justify-content:center;">
                                    <i class="fas fa-bolt"></i> {{ __('home.buy_now') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    @include('partials.product-detail-accordions', ['model' => $package])
                </div>
            </div>
        </div>
    </section>

    <div class="elx-container" style="padding-bottom: 4rem;">
        @include('partials.tag-related-sections', [
            'relatedBlogs' => $relatedBlogs ?? collect(),
            'relatedReviews' => $relatedReviews ?? collect(),
            'relatedPackages' => $relatedPackages ?? collect(),
        ])
    </div>
</div>

@if($hasSizeOptions)
<script>
(function () {
    const sizeSelect = document.getElementById('package-size-select');
    if (!sizeSelect) return;

    const variants = @json($variantPayload);
    const variantMap = Object.fromEntries(variants.map((variant) => [String(variant.id), variant]));
    const currencySymbol = @json($pricingService->currencySymbol($selectedCountry ?? null));
    const isRtl = @json(app()->getLocale() === 'ar');
    const inStockTemplate = @json(__('shop.in_stock', ['count' => ':count']));
    const pointsTemplate = @json(__('home.reward_points', ['count' => ':count']));
    const outOfStockHint = @json(__('shop.package_out_of_stock'));
    const tryAnotherSize = @json(__('shop.try_another_size_hint'));

    function applyVariant(variantId) {
        const variant = variantMap[String(variantId)];
        if (!variant) return;

        const pricingRoot = document.querySelector('.package-info-panel .elx-product-pricing');
        const amounts = pricingRoot ? pricingRoot.querySelectorAll('.elx-product-pricing__amount') : [];
        if (amounts[0]) {
            amounts[0].textContent = Number(variant.member_price).toFixed(2);
        }
        const guestLine = pricingRoot ? pricingRoot.querySelector('.elx-product-pricing__price-line--guest') : null;
        if (guestLine) {
            if (variant.guest_price > variant.member_price) {
                const guestAmount = guestLine.querySelector('.elx-product-pricing__amount');
                if (guestAmount) guestAmount.textContent = Number(variant.guest_price).toFixed(2);
                guestLine.style.display = '';
            } else {
                guestLine.style.display = 'none';
            }
        }

        const hiddenId = document.getElementById('package-country-price-id');
        if (hiddenId) hiddenId.value = variant.id;

        const sizeDisplay = document.getElementById('package-size-display');
        if (sizeDisplay) sizeDisplay.textContent = variant.size;

        const stockBadge = document.getElementById('package-stock-badge');
        const stockText = document.getElementById('package-stock-text');
        if (stockBadge && stockText) {
            if (variant.stock > 0) {
                stockBadge.style.display = '';
                stockText.textContent = inStockTemplate.replace(':count', String(variant.stock));
            } else {
                stockBadge.style.display = 'none';
            }
        }

        const pointsText = document.getElementById('package-points-text');
        if (pointsText && variant.points > 0) {
            pointsText.textContent = pointsTemplate.replace(':count', String(variant.points));
        }

        const purchaseOos = document.getElementById('package-purchase-oos');
        const purchaseInstock = document.getElementById('package-purchase-instock');
        const oosMessage = document.getElementById('package-oos-message');
        const qtyInput = document.getElementById('package-quantity');
        const hasOtherInStock = variants.some((entry) => entry.id !== variant.id && entry.max_qty > 0);

        if (variant.out_of_stock) {
            if (purchaseOos) purchaseOos.style.display = '';
            if (purchaseInstock) purchaseInstock.style.display = 'none';
            if (oosMessage) {
                oosMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (hasOtherInStock ? tryAnotherSize : outOfStockHint);
            }
        } else {
            if (purchaseOos) purchaseOos.style.display = 'none';
            if (purchaseInstock) purchaseInstock.style.display = '';
            if (qtyInput) {
                qtyInput.max = String(Math.max(1, variant.max_qty));
                if (Number(qtyInput.value) > variant.max_qty) {
                    qtyInput.value = String(variant.max_qty);
                }
            }
        }

        const url = new URL(window.location.href);
        url.searchParams.set('country_price_id', String(variant.id));
        window.history.replaceState({}, '', url.toString());
    }

    sizeSelect.addEventListener('change', function () {
        applyVariant(sizeSelect.value);
    });
})();
</script>
@endif
@endsection
