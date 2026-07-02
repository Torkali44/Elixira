@php
    $pricingService = app(\App\Support\PackagePricingService::class);
    $itemPricing = app(\App\Support\ItemPricingService::class);
    $availableCountries = $pricingService->availableCountryCodes($package);
    $selectedCountry = $itemPricing->resolveCountryCodeForPackage($package, $selectedCountry ?? request('country')) ?? $itemPricing->detectUserCountry();
    $canAddToCart = count($availableCountries) > 0 && (int) $package->stock > 0;
    $cardRewardPoints = $itemPricing->resolvePackageRewardPoints($package, $selectedCountry);
@endphp

<div class="elx-product-card" data-animate style="cursor: pointer;" onclick="if(!event.target.closest('button') && !event.target.closest('form') && !event.target.closest('a')) window.location.href='{{ route('packages.show', $package) }}'">
    <a href="{{ route('packages.show', $package) }}" class="elx-product-card__image-container">
        @if($package->image)
            <img src="{{ \App\Support\StorageUrl::asset($package->image) }}" alt="{{ $package->local_name }}" width="400" height="400" loading="lazy" decoding="async">
        @else
            <div class="elx-product-card__no-img"><i class="fas fa-box-open"></i></div>
        @endif
        <div class="elx-product-card__badge" style="position:absolute; top:1rem; right:1rem; left:auto; background:#ffd700; color:#000; padding:0.3rem 0.8rem; border-radius:100px; font-size:0.7rem; font-weight:700; z-index:10;">
            {{ __('shop.package_badge') }}
        </div>
    </a>
    <div class="elx-product-card__info">
        <a href="{{ route('packages.show', $package) }}" class="elx-product-card__name-link" onclick="event.stopPropagation();">
            <h3 class="elx-product-card__name">{{ $package->local_name }}</h3>
        </a>

        <div class="elx-product-card__price">
            <x-package-pricing :package="$package" :selected-country="$selectedCountry" :showSelector="false" />
        </div>

        <div class="elx-product-card__meta">
            @if($cardRewardPoints > 0)
                <span class="elx-product-card__tag elx-product-card__tag--points">
                    <i class="fas fa-star"></i> {{ __('home.reward_points', ['count' => $cardRewardPoints]) }}
                </span>
            @endif
            <span class="elx-product-card__tag elx-product-card__tag--stock">
                <i class="fas fa-box"></i> {{ (int) $package->stock > 0 ? __('shop.in_stock', ['count' => $package->stock]) : __('shop.out_of_stock') }}
            </span>
        </div>

        <p class="elx-product-card__desc">{{ Str::limit($package->local_description, 85) }}</p>

        @if($canAddToCart)
            <div class="elx-product-card__cart-form" onclick="event.stopPropagation();">
                <form action="{{ route('cart.add-package') }}" method="POST" onclick="event.stopPropagation();">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="country_code" value="{{ $selectedCountry }}" id="package-country-{{ $package->id }}">
                    <button type="button" class="elx-product-card__add-btn" onclick="addToCartAjax(this, event);">
                        <i class="fas fa-cart-plus"></i> {{ __('home.add_to_cart') }}
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
