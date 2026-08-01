@php
    $privateQty = (int) ($privateOfferQuantities[$product->id] ?? 0);
    $hasPrivateAccess = $privateQty > 0;
    $pricingService = app(\App\Support\ItemPricingService::class);
    $cardCountries = $pricingService->availableCountryCodes($product);
    $cardCountry = $pricingService->resolveCountryCodeForItem($product, $selectedCountry ?? request('country')) ?? $pricingService->detectUserCountry();
    $defaultVariant = $pricingService->resolveDefaultVariant($product, $cardCountry);
    $countryPriceId = $defaultVariant?->id;
    $countryStock = $pricingService->resolveStock($product, $cardCountry, $countryPriceId);
    $hasAnyCountryStock = $pricingService->hasStockInCountry($product, $cardCountry);
    $isOutOfStock = ! $hasAnyCountryStock && ! $hasPrivateAccess;
    $canAddToCart = count($cardCountries) > 0 && ! $isOutOfStock;
    $cardRewardPoints = $pricingService->resolveRewardPoints($product, $cardCountry, $countryPriceId);
    // Eager-load first 8 images (above the fold), lazy-load the rest
    $isAboveFold = ($cardIndex ?? 99) < 8;
    $imgLoading = $isAboveFold ? 'eager' : 'lazy';
    $imgFetchPriority = $isAboveFold ? 'high' : 'low';
@endphp

<div class="elx-product-card" data-animate onclick="window.location='{{ route('menu.show', $product->id) }}?country={{ $cardCountry }}{{ $countryPriceId ? '&country_price_id='.$countryPriceId : '' }}'">
    <a href="{{ route('menu.show', $product->id) }}?country={{ $cardCountry }}{{ $countryPriceId ? '&country_price_id='.$countryPriceId : '' }}" class="elx-product-card__image-container" onclick="event.stopPropagation();">
        @if($product->image)
            <img src="{{ $product->image_url }}" alt="{{ $product->local_name }}" width="400" height="400" loading="{{ $imgLoading }}" fetchpriority="{{ $imgFetchPriority }}" decoding="async" @class(['is-grayscale' => $isOutOfStock])>
        @else
            <div class="elx-product-card__no-img">
                <i class="fas fa-seedling"></i>
            </div>
        @endif

        @if($isOutOfStock)
            <div class="elx-product-card__badge elx-product-card__badge--danger">
                <span>{{ __('shop.out_of_stock') }}</span>
            </div>
        @elseif($countryStock <= 0 && $hasPrivateAccess)
            <div class="elx-product-card__badge elx-product-card__badge--private">
                <span>{{ __('shop.private_access') }}</span>
            </div>
        @elseif($countryStock <= 5 && $countryStock > 0)
            <div class="elx-product-card__badge">
                <span>{{ __('shop.limited') }}</span>
            </div>
        @endif
    </a>

    <div class="elx-product-card__info">
        <a href="{{ route('menu.show', $product->id) }}?country={{ $cardCountry }}{{ $countryPriceId ? '&country_price_id='.$countryPriceId : '' }}" class="elx-product-card__name-link" onclick="event.stopPropagation();">
            <h3 class="elx-product-card__name">{{ $product->local_name }}</h3>
        </a>

        <div class="elx-product-card__price">
            <x-product-pricing :item="$product" :selectedCountry="$cardCountry" :countryPriceId="$countryPriceId" :showSelector="false" size="1.05rem" smallSize="0.8rem" />
        </div>

        <div class="elx-product-card__meta">
            @if($product->category)
                <span class="elx-product-card__tag elx-product-card__tag--category">
                    <i class="fas fa-layer-group"></i>{{ $product->category->local_name }}
                </span>
            @endif
            @if($product->brandModel)
                <a href="{{ route('brands.show', $product->brandModel->slug) }}" class="elx-product-card__tag elx-product-card__tag--brand" onclick="event.stopPropagation();">
                    @if($product->brandModel->logo)
                        <img src="{{ asset('storage/' . $product->brandModel->logo) }}" alt="">
                    @else
                        <i class="fas fa-store"></i>
                    @endif
                    {{ $product->brandModel->name }}
                </a>
            @elseif($product->brand)
                <span class="elx-product-card__tag elx-product-card__tag--brand">
                    <i class="fas fa-tag"></i>{{ $product->brand }}
                </span>
            @endif
            @if($cardRewardPoints > 0)
                <span class="elx-product-card__tag elx-product-card__tag--points">
                    <i class="fas fa-star"></i>{{ __('home.reward_points', ['count' => $cardRewardPoints]) }}
                </span>
            @endif
            <span class="elx-product-card__tag elx-product-card__tag--stock {{ $isOutOfStock ? 'is-out' : '' }}">
                <i class="fas fa-box"></i>{{ $isOutOfStock ? __('shop.out_of_stock') : ($countryStock > 0 ? __('shop.in_stock', ['count' => $countryStock]) : __('shop.private_access_short')) }}
            </span>
        </div>

        <p class="elx-product-card__desc">{{ Str::limit($product->local_description, 85) }}</p>

        <div class="elx-product-card__cart-form" onclick="event.stopPropagation();">
            @if($isOutOfStock)
                <button type="button" class="elx-product-card__add-btn elx-product-card__add-btn--private"
                    onclick="event.stopPropagation(); showSpecialRequestModal({{ $product->id }}, '{{ addslashes($product->local_name) }}')">
                    <i class="fas fa-hand-holding-heart"></i> {{ __('home.private_order') }}
                </button>
            @elseif($canAddToCart)
                <form action="{{ route('cart.add') }}" method="POST" onclick="event.stopPropagation();">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="country_code" value="{{ $cardCountry }}">
                    @if($countryPriceId)
                        <input type="hidden" name="country_price_id" value="{{ $countryPriceId }}">
                    @endif
                    <button type="button" class="elx-product-card__add-btn" onclick="addToCartAjax(this, event);">
                        <i class="fas fa-cart-plus"></i> {{ __('home.add_to_cart') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
