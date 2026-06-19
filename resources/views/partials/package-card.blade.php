@php
    $pricingService = app(\App\Support\PackagePricingService::class);
    $itemPricing = app(\App\Support\ItemPricingService::class);
    $availableCountries = $pricingService->availableCountryCodes($package);
    $selectedCountry = $itemPricing->resolveCountryCodeForPackage($package, request('country')) ?? $itemPricing->detectUserCountry();
    $flags = $itemPricing->countryFlags();
    $canAddToCart = count($availableCountries) > 0 && (int) $package->stock > 0;
@endphp

<div class="elx-product-card" data-animate style="cursor: default;">
    <a href="{{ route('packages.show', $package) }}" class="elx-product-card__image-container">
        @if($package->image)
            <img src="{{ asset('storage/'.$package->image) }}" alt="{{ $package->local_name }}">
        @else
            <div class="elx-product-card__no-img"><i class="fas fa-box-open"></i></div>
        @endif
        <div class="elx-product-card__badge" style="position:absolute; top:1rem; right:1rem; left:auto; background:#ffd700; color:#000; padding:0.3rem 0.8rem; border-radius:100px; font-size:0.7rem; font-weight:700; z-index:10;">
            {{ __('shop.package_badge') }}
        </div>
    </a>
    <div class="elx-product-card__info">
        <div class="elx-product-card__header" style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:0.5rem;">
            <a href="{{ route('packages.show', $package) }}" style="text-decoration:none; color:inherit; flex-grow:1; min-width:0;">
                <h3 class="elx-product-card__name" style="margin:0; font-size:1.1rem; line-height:1.3;">{{ $package->local_name }}</h3>
            </a>
            <div class="elx-product-card__price" style="flex-shrink:0; text-align:right;">
                <x-package-pricing :package="$package" :selected-country="$selectedCountry" :showSelector="false" align="flex-end" />
            </div>
        </div>

        @if(count($availableCountries) > 0)
            <div style="margin-bottom:0.75rem; display:flex; gap:0.35rem; flex-wrap:wrap;">
                @foreach($availableCountries as $code)
                    <button type="button"
                        class="package-card-country-btn"
                        data-package-id="{{ $package->id }}"
                        data-country="{{ $code }}"
                        onclick="selectPackageCardCountry(this)"
                        style="display:inline-flex;align-items:center;gap:0.25rem;padding:0.2rem 0.55rem;border-radius:999px;border:1px solid {{ $selectedCountry === $code ? 'rgba(74,200,246,0.8)' : 'rgba(255,255,255,0.15)' }};background:{{ $selectedCountry === $code ? 'rgba(74,200,246,0.15)' : 'rgba(255,255,255,0.04)' }};color:#fff;cursor:pointer;font-size:0.72rem;font-weight:600;">
                        @if($flags[$code] ?? null)
                            <img src="{{ $flags[$code] }}" alt="" style="width:16px;height:11px;border-radius:1px;object-fit:cover;">
                        @endif
                        {{ $code }}
                    </button>
                @endforeach
            </div>
        @endif

        <div class="elx-product-card__meta" style="display:flex; flex-wrap:wrap; gap:0.35rem; margin-bottom:0.75rem;">
            @if(($package->reward_points ?? 0) > 0)
                <span class="elx-product-card__tag" style="background:rgba(0,255,136,0.1); color:#00ff88; padding:0.15rem 0.55rem; border-radius:50px; font-size:0.7rem; font-weight:600; border:1px solid rgba(0,255,136,0.2);">
                    <i class="fas fa-star"></i> {{ __('home.reward_points', ['count' => $package->reward_points]) }}
                </span>
            @endif
            <span class="elx-product-card__tag" style="background:rgba(74,200,246,0.08); color:rgba(255,255,255,0.55); padding:0.15rem 0.55rem; border-radius:50px; font-size:0.7rem; font-weight:600;">
                <i class="fas fa-box"></i> {{ (int) $package->stock > 0 ? __('shop.in_stock', ['count' => $package->stock]) : __('shop.out_of_stock') }}
            </span>
        </div>

        <p class="elx-product-card__desc" style="flex-grow:1; margin-bottom:1rem;">{{ Str::limit($package->local_description, 85) }}</p>

        @if($canAddToCart)
            <form action="{{ route('cart.add-package') }}" method="POST">
                @csrf
                <input type="hidden" name="package_id" value="{{ $package->id }}">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="country_code" value="{{ $selectedCountry }}" id="package-country-{{ $package->id }}">
                <button type="submit" class="elx-product-card__add-btn" style="width:100%;">
                    <i class="fas fa-cart-plus"></i> {{ __('home.add_to_cart') }}
                </button>
            </form>
        @endif
    </div>
</div>
