@php
    $privateQty = (int) ($privateOfferQuantities[$product->id] ?? 0);
    $hasPrivateAccess = $privateQty > 0;
    $isOutOfStock = $product->stock <= 0 && !$hasPrivateAccess;
@endphp

<div class="elx-product-card" data-animate onclick="window.location='{{ route('menu.show', $product->id) }}'" style="cursor: pointer;">
    {{-- Top Div: Image container --}}
    <a href="{{ route('menu.show', $product->id) }}" class="elx-product-card__image-container" onclick="event.stopPropagation();">
        @if($product->image)
            <img src="{{ storage_public_url($product->image) }}" alt="{{ $product->name }}" style="{{ $isOutOfStock ? 'filter: grayscale(0.8);' : '' }}">
        @else
            <div class="elx-product-card__no-img">
                <i class="fas fa-seedling"></i>
            </div>
        @endif
        
        @if($isOutOfStock)
            <div class="elx-product-card__badge" style="position: absolute; top: 1rem; right: 1rem; background: #ff4d4d; padding: 0.3rem 0.8rem; border-radius: 100px; color: white; font-size: 0.7rem; font-weight: 700; z-index: 10;">
                <span>Out of Stock</span>
            </div>
        @elseif($product->stock <= 0 && $hasPrivateAccess)
            <div class="elx-product-card__badge" style="position: absolute; top: 1rem; right: 1rem; background: rgba(74, 200, 246, 0.2); border: 1px solid rgba(74, 200, 246, 0.4); padding: 0.3rem 0.8rem; border-radius: 100px; color: #4ac8f6; font-size: 0.7rem; font-weight: 700; z-index: 10;">
                <span>Private Access</span>
            </div>
        @elseif($product->stock <= 5)
            <div class="elx-product-card__badge" style="position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); padding: 0.3rem 0.8rem; border-radius: 100px; color: white; font-size: 0.7rem; font-weight: 700; z-index: 10;">
                <span>Limited</span>
            </div>
        @endif
    </a>

    {{-- Bottom Div: Info Split (Name/Price) with Gradient --}}
    <div class="elx-product-card__info">
        <div class="elx-product-card__header">
            <a href="{{ route('menu.show', $product->id) }}" style="text-decoration: none; color: inherit;" onclick="event.stopPropagation();">
                <h3 class="elx-product-card__name">{{ $product->name }}</h3>
            </a>
            <span class="elx-product-card__price">﷼ {{ number_format($product->price, 2) }}</span>
        </div>

        {{-- Meta Info: Category, Brand, Points, Stock --}}
        <div class="elx-product-card__meta" style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 0.5rem;">
            @if($product->category)
                <span class="elx-product-card__tag" style="background: rgba(74, 200, 246, 0.1); color: #4ac8f6; padding: 0.15rem 0.55rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; border: 1px solid rgba(74, 200, 246, 0.2);">
                    <i class="fas fa-layer-group" style="margin-right: 2px;"></i>{{ $product->category->name }}
                </span>
            @endif
            @if($product->brand)
                <span class="elx-product-card__tag" style="background: rgba(255, 215, 0, 0.1); color: #ffd700; padding: 0.15rem 0.55rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; border: 1px solid rgba(255, 215, 0, 0.2);">
                    <i class="fas fa-tag" style="margin-right: 2px;"></i>{{ $product->brand }}
                </span>
            @endif
            @if($product->points > 0)
                <span class="elx-product-card__tag" style="background: rgba(0, 255, 136, 0.1); color: #00ff88; padding: 0.15rem 0.55rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; border: 1px solid rgba(0, 255, 136, 0.2);">
                    <i class="fas fa-star" style="margin-right: 2px;"></i>{{ $product->points }} pts
                </span>
            @endif
            <span class="elx-product-card__tag" style="background: {{ $isOutOfStock ? 'rgba(255,77,77,0.1)' : 'rgba(74,200,246,0.08)' }}; color: {{ $isOutOfStock ? '#ff4d4d' : 'rgba(255,255,255,0.5)' }}; padding: 0.15rem 0.55rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; border: 1px solid {{ $isOutOfStock ? 'rgba(255,77,77,0.2)' : 'rgba(255,255,255,0.1)' }};">
                <i class="fas fa-box" style="margin-right: 2px;"></i>{{ $isOutOfStock ? 'Out of stock' : ($product->stock > 0 ? $product->stock . ' in stock' : 'Private access') }}
            </span>
        </div>
        
        <p class="elx-product-card__desc">
            {{ $product->description }}
        </p>

        <div class="elx-product-card__cart-form" onclick="event.stopPropagation();" style="position: relative; z-index: 20;">
            @if($isOutOfStock)
                    <button type="button" class="elx-product-card__add-btn"
                        onclick="event.stopPropagation(); showSpecialRequestModal({{ $product->id }}, '{{ addslashes($product->name) }}')" style="position: relative; z-index: 20; background: rgba(255, 77, 77, 0.1); color: #ff4d4d; border-color: rgba(255, 77, 77, 0.3);">
                    <i class="fas fa-hand-holding-heart"></i> Private order
                </button>
            @else
                <form action="{{ route('cart.add') }}" method="POST" onclick="event.stopPropagation();" style="position: relative; z-index: 20;">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="button" class="elx-product-card__add-btn" onclick="addToCartAjax(this, event);" style="position: relative; z-index: 20;">
                        <i class="fas fa-cart-plus"></i> {{ $product->stock > 0 ? 'Add to Cart' : 'Special Item' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
