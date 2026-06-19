@php
    $selectedCountry = app(\App\Support\PackagePricingService::class)->getPriceBreakdown($package, auth()->user(), request('country'))['country_code'];
@endphp

<div class="elx-product-card" data-animate onclick="window.location='{{ route('packages.show', $package) }}'" style="cursor: pointer;">
    <a href="{{ route('packages.show', $package) }}" class="elx-product-card__image-container" onclick="event.stopPropagation();">
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
        <div class="elx-product-card__header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 0.5rem;">
            <a href="{{ route('packages.show', $package) }}" style="text-decoration: none; color: inherit; flex-grow: 1; min-width: 0;" onclick="event.stopPropagation();">
                <h3 class="elx-product-card__name" style="margin: 0; font-size: 1.1rem; line-height: 1.3;">{{ $package->local_name }}</h3>
            </a>
            <div class="elx-product-card__price" style="flex-shrink: 0; text-align: right;">
                <x-package-pricing :package="$package" :selected-country="$selectedCountry" :showSelector="false" align="flex-end" />
            </div>
        </div>

        {{-- Country Selector Form --}}
        <div class="elx-product-card__country-selector" style="margin-bottom: 0.75rem;">
            <x-package-pricing :package="$package" :selected-country="$selectedCountry" :showPrice="false" :hideCountryName="true" align="flex-start" />
        </div>

        <div class="elx-product-card__meta" style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 0.75rem;">
            @if(($package->reward_points ?? 0) > 0)
                <span class="elx-product-card__tag" style="background: rgba(0, 255, 136, 0.1); color: #00ff88; padding: 0.15rem 0.55rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; border: 1px solid rgba(0, 255, 136, 0.2);">
                    <i class="fas fa-star"></i> {{ __('home.reward_points', ['count' => $package->reward_points]) }}
                </span>
            @endif
        </div>
        <p class="elx-product-card__desc" style="flex-grow: 1; margin-bottom: 1rem;">{{ Str::limit($package->local_description, 85) }}</p>
        <div onclick="event.stopPropagation();">
            <form action="{{ route('cart.add-package') }}" method="POST">
                @csrf
                <input type="hidden" name="package_id" value="{{ $package->id }}">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="country_code" value="{{ $selectedCountry }}">
                <button type="submit" class="elx-product-card__add-btn" style="width:100%;">
                    <i class="fas fa-cart-plus"></i> {{ __('home.add_to_cart') }}
                </button>
            </form>
        </div>
    </div>
</div>
