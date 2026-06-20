@php
    $privateQty = (int) ($privateOfferQuantities[$product->id] ?? 0);
    $hasPrivateAccess = $privateQty > 0;
    $isOutOfStock = $product->stock <= 0 && !$hasPrivateAccess;
    $pricingService = app(\App\Support\ItemPricingService::class);
    $cardCountries = $pricingService->availableCountryCodes($product);
    $cardCountry = $pricingService->resolveCountryCodeForItem($product, $selectedCountry ?? request('country')) ?? $pricingService->detectUserCountry();
    $canAddToCart = count($cardCountries) > 0 && !$isOutOfStock;
    $flags = $pricingService->countryFlags();
@endphp

<div class="elx-product-card" data-animate onclick="window.location='{{ route('menu.show', $product->id) }}'" style="cursor: pointer;">
    {{-- Top Div: Image container --}}
    <a href="{{ route('menu.show', $product->id) }}" class="elx-product-card__image-container" onclick="event.stopPropagation();">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->local_name }}" style="{{ $isOutOfStock ? 'filter: grayscale(0.8);' : '' }}">
        @else
            <div class="elx-product-card__no-img">
                <i class="fas fa-seedling"></i>
            </div>
        @endif
        
        @if($isOutOfStock)
            <div class="elx-product-card__badge" style="position: absolute; top: 1rem; right: 1rem; left: auto; background: #ff4d4d; padding: 0.3rem 0.8rem; border-radius: 100px; color: white; font-size: 0.7rem; font-weight: 700; z-index: 10;">
                <span>{{ __('shop.out_of_stock') }}</span>
            </div>
        @elseif($product->stock <= 0 && $hasPrivateAccess)
            <div class="elx-product-card__badge" style="position: absolute; top: 1rem; right: 1rem; left: auto; background: rgba(74, 200, 246, 0.2); border: 1px solid rgba(74, 200, 246, 0.4); padding: 0.3rem 0.8rem; border-radius: 100px; color: #4ac8f6; font-size: 0.7rem; font-weight: 700; z-index: 10;">
                <span>{{ __('shop.private_access') }}</span>
            </div>
        @elseif($product->stock <= 5)
            <div class="elx-product-card__badge" style="position: absolute; top: 1rem; right: 1rem; left: auto; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); padding: 0.3rem 0.8rem; border-radius: 100px; color: white; font-size: 0.7rem; font-weight: 700; z-index: 10;">
                <span>{{ __('shop.limited') }}</span>
            </div>
        @endif
    </a>

    {{-- Bottom Div: Info Split (Name/Price) with Gradient --}}
    <div class="elx-product-card__info">
        <div class="elx-product-card__header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 0.5rem;">
            <a href="{{ route('menu.show', $product->id) }}" style="text-decoration: none; color: inherit; flex-grow: 1; min-width: 0; word-wrap: break-word;" onclick="event.stopPropagation();">
                <h3 class="elx-product-card__name" style="margin: 0; font-size: 1.1rem; line-height: 1.3;">{{ $product->local_name }}</h3>
            </a>
            <div class="elx-product-card__price" style="flex-shrink: 0; text-align: right;">
                <x-product-pricing :item="$product" :selectedCountry="$cardCountry" :showSelector="false" align="flex-end" />
            </div>
        </div>

        {{-- Country Selector Form --}}
        <div class="elx-product-card__country-selector" style="margin-bottom: 0.75rem;">
            <x-product-pricing :item="$product" :showPrice="false" :hideCountryName="true" align="flex-start" />
        </div>

        {{-- Meta Info: Category, Brand, Points, Stock --}}
        <div class="elx-product-card__meta" style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 0.75rem;">
            @if($product->category)
                <span class="elx-product-card__tag" style="background: rgba(74, 200, 246, 0.1); color: #4ac8f6; padding: 0.15rem 0.55rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; border: 1px solid rgba(74, 200, 246, 0.2);">
                    <i class="fas fa-layer-group" style="margin-right: 2px;"></i>{{ $product->category->local_name }}
                </span>
            @endif
            @if($product->brandModel)
                <a href="{{ route('brands.show', $product->brandModel->slug) }}" onclick="event.stopPropagation();" style="display: inline-flex; align-items: center; gap: 4px; background: rgba(255, 215, 0, 0.1); color: #ffd700; padding: 0.15rem 0.55rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; border: 1px solid rgba(255, 215, 0, 0.2); text-decoration: none; transition: all 0.2s ease;">
                    @if($product->brandModel->logo)
                        <img src="{{ asset('storage/' . $product->brandModel->logo) }}" style="width: 16px; height: 16px; border-radius: 4px; object-fit: cover;" alt="">
                    @else
                        <i class="fas fa-store" style="margin-right: 2px;"></i>
                    @endif
                    {{ $product->brandModel->name }}
                </a>
            @elseif($product->brand)
                <span class="elx-product-card__tag" style="background: rgba(255, 215, 0, 0.1); color: #ffd700; padding: 0.15rem 0.55rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; border: 1px solid rgba(255, 215, 0, 0.2);">
                    <i class="fas fa-tag" style="margin-right: 2px;"></i>{{ $product->brand }}
                </span>
            @endif
            @if(($product->reward_points ?? 0) > 0)
                <span class="elx-product-card__tag" style="background: rgba(0, 255, 136, 0.1); color: #00ff88; padding: 0.15rem 0.55rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; border: 1px solid rgba(0, 255, 136, 0.2);">
                    <i class="fas fa-star" style="margin-right: 2px;"></i>{{ __('home.reward_points', ['count' => $product->reward_points]) }}
                </span>
            @endif
            <span class="elx-product-card__tag" style="background: {{ $isOutOfStock ? 'rgba(255,77,77,0.1)' : 'rgba(74,200,246,0.08)' }}; color: {{ $isOutOfStock ? '#ff4d4d' : 'rgba(255,255,255,0.5)' }}; padding: 0.15rem 0.55rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; border: 1px solid {{ $isOutOfStock ? 'rgba(255,77,77,0.2)' : 'rgba(255,255,255,0.1)' }};">
                <i class="fas fa-box" style="margin-right: 2px;"></i>{{ $isOutOfStock ? __('shop.out_of_stock') : ($product->stock > 0 ? __('shop.in_stock', ['count' => $product->stock]) : __('shop.private_access_short')) }}
            </span>
        </div>
        
        <p class="elx-product-card__desc" style="flex-grow: 1; margin-bottom: 1rem;">
            {{ Str::limit($product->local_description, 85) }}
        </p>

        @if(count($cardCountries) > 1)
            <div class="elx-product-card__country-selector" onclick="event.stopPropagation();" style="margin-bottom: 0.75rem; display: flex; gap: 0.35rem; flex-wrap: wrap;">
                @foreach($cardCountries as $code)
                    <button type="button"
                        class="product-card-country-btn"
                        data-product-id="{{ $product->id }}"
                        data-country="{{ $code }}"
                        onclick="selectProductCardCountry(this)"
                        style="display:inline-flex;align-items:center;gap:0.25rem;padding:0.2rem 0.55rem;border-radius:999px;border:1px solid {{ $cardCountry === $code ? 'rgba(74,200,246,0.8)' : 'rgba(255,255,255,0.15)' }};background:{{ $cardCountry === $code ? 'rgba(74,200,246,0.15)' : 'rgba(255,255,255,0.04)' }};color:#fff;cursor:pointer;font-size:0.72rem;font-weight:600;">
                        @if($flags[$code] ?? null)
                            <img src="{{ $flags[$code] }}" alt="" style="width:16px;height:11px;border-radius:1px;object-fit:cover;">
                        @endif
                        {{ $code }}
                    </button>
                @endforeach
            </div>
        @endif

        <div class="elx-product-card__cart-form" onclick="event.stopPropagation();" style="position: relative; z-index: 20;">
            @if($isOutOfStock)
                    <button type="button" class="elx-product-card__add-btn"
                        onclick="event.stopPropagation(); showSpecialRequestModal({{ $product->id }}, '{{ addslashes($product->local_name) }}')" style="position: relative; z-index: 20; background: rgba(255, 77, 77, 0.1); color: #ff4d4d; border-color: rgba(255, 77, 77, 0.3);">
                    <i class="fas fa-hand-holding-heart"></i> {{ __('home.private_order') }}
                </button>
            @elseif($canAddToCart)
                <form action="{{ route('cart.add') }}" method="POST" onclick="event.stopPropagation();" style="position: relative; z-index: 20;">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="country_code" value="{{ $cardCountry }}" id="product-country-{{ $product->id }}">
                    <button type="button" class="elx-product-card__add-btn" onclick="addToCartAjax(this, event);" style="position: relative; z-index: 20; width: 100%;">
                        <i class="fas fa-cart-plus"></i> {{ __('home.add_to_cart') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
