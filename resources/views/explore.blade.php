@extends('layouts.framer')

@section('title', 'Explore - Elixira')

@section('content')
<div class="page-content">
    <div class="elx-container">
        {{-- Section Header --}}
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">Explore the ritual</span>
            </h1>
            <p class="elx-hero__subtitle">Browse categories curated in your admin - tuned for discovery.</p>
        </div>

        <div class="elx-section" style="padding-top: 0;">
            <div class="elx-section__header" style="text-align: left; margin-bottom: 2rem;" data-animate>
                <h2 class="elx-section__title" style="font-size: 1.8rem; color: var(--elx-accent);">✧ Shop by Category</h2>
            </div>
            
            <div class="elx-categories__grid" data-animate>
                @foreach($categories as $category)
                <a href="{{ route('menu.index') }}?category={{ $category->id }}" class="elx-category-pill" style="padding: 1rem 2rem 1rem 1rem;">
                    <div class="elx-category-pill__img" style="width: 80px; height: 80px;">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->local_name }}">
                        @else
                            <div class="elx-category-pill__placeholder">
                                <i class="fas fa-leaf"></i>
                            </div>
                        @endif
                    </div>
                    <div class="elx-category-pill__info">
                        <h3 style="font-size: 1.2rem;">{{ $category->local_name }}</h3>
                        <span>{{ __('home.products_count', ['count' => $category->items_count]) }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        @if($featuredItems->isNotEmpty())
        <div class="elx-section">
            <div class="elx-section__header" style="text-align: left; margin-bottom: 2rem;" data-animate>
                <h2 class="elx-section__title" style="font-size: 1.8rem; color: var(--elx-accent);">✧ Featured Now</h2>
            </div>
            
            <div class="elx-products__grid" data-animate>
                @foreach($featuredItems as $item)
                <a href="{{ route('menu.show', $item->id) }}" class="elx-product-card">
                    <div class="elx-product-card__glow"></div>
                    <div class="elx-product-card__image">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->local_name }}" loading="lazy">
                        @else
                            <div class="elx-product-card__no-img">
                                <i class="fas fa-seedling"></i>
                            </div>
                        @endif
                    </div>
                    <div class="elx-product-card__badge">
                        <span>{{ $item->category->local_name }}</span>
                    </div>
                    <div class="elx-product-card__info">
                        <h3 class="elx-product-card__name" style="margin-bottom: 0.5rem;">{{ $item->local_name }}</h3>
                        <div class="elx-product-card__price-row" style="margin-bottom: 0.5rem;">
                            <x-product-pricing :item="$item" :showSelector="false" align="flex-start" />
                        </div>
                        <p class="elx-product-card__desc" style="flex-grow: 1; margin-bottom: 1rem;">{{ Str::limit($item->local_description, 85) }}</p>
                        @if(($item->reward_points ?? 0) > 0)
                            <p class="elx-product-card__desc" style="color: #00ff88; margin-top: 0.5rem;">
                                <i class="fas fa-star"></i> {{ __('home.reward_points', ['count' => $item->reward_points]) }}
                            </p>
                        @endif
                    </div>
                    <form action="{{ route('cart.add') }}" method="POST" class="elx-product-card__cart-form" onclick="event.stopPropagation();">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <button type="submit" class="elx-product-card__add-btn" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-plus"></i> {{ __('home.add_to_cart') }}
                        </button>
                    </form>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
