@extends('layouts.framer')

@section('title', $item->name . ' — Elixira')

@section('head')
<style>
    .product-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: start;
    }
    .product-gallery {
        position: sticky;
        top: 100px;
    }
    .main-img-container {
        background: var(--elx-glass);
        backdrop-filter: blur(42px);
        border: 1px solid var(--elx-border);
        border-radius: var(--elx-radius-sm);
        padding: 2rem;
        overflow: hidden;
    }
    .main-img-container img {
        width: 100%;
        height: auto;
        border-radius: 15px;
        transition: 0.5s;
    }
    .main-img-container:hover img {
        transform: scale(1.05);
    }
    .stock-badge {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    .stock-in { background: rgba(74, 200, 246, 0.1); color: var(--elx-cyan); border: 1px solid rgba(74, 200, 246, 0.2); }
    .stock-out { background: rgba(220, 53, 69, 0.1); color: #ff8a8a; border: 1px solid rgba(220, 53, 69, 0.2); }
    
    .blog-section {
        margin-top: 6rem;
        padding: 5rem;
        background: var(--elx-glass);
        border-radius: var(--elx-radius-sm);
        border: 1px solid var(--elx-border);
        line-height: 1.8;
    }
    .blog-section h2 {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 2.5rem;
        margin-bottom: 2rem;
        color: var(--elx-accent);
    }
    .blog-content {
        color: var(--elx-gray);
        font-size: 1.1rem;
        white-space: pre-line;
    }

    @media (max-width: 991px) {
        .product-detail-grid { grid-template-columns: 1fr; gap: 2rem; }
        .product-gallery { position: relative; top: 0; }
        .blog-section { padding: 2rem; }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div class="product-detail-grid">
            {{-- Left: Image --}}
            <div class="product-gallery" data-animate>
                <div class="main-img-container">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" id="mainProductImage">
                    @else
                        <div style="aspect-ratio: 1/1; background: #1a2e38; display: flex; align-items: center; justify-content: center; color: var(--elx-cyan); font-size: 5rem;">
                            <i class="fas fa-seedling"></i>
                        </div>
                    @endif
                </div>
                
                @if($item->images->count() > 0)
                <div class="thumbnail-gallery" style="display: flex; gap: 1rem; margin-top: 1.5rem; overflow-x: auto; padding-bottom: 0.5rem;">
                    {{-- Main Image Thumbnail --}}
                    @if($item->image)
                    <div class="thumb-item" onclick="document.getElementById('mainProductImage').src='{{ asset('storage/' . $item->image) }}'" style="flex: 0 0 80px; height: 80px; border-radius: 12px; border: 2px solid var(--elx-cyan); cursor: pointer; overflow: hidden; background: var(--elx-glass);">
                        <img src="{{ asset('storage/' . $item->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    @endif
                    
                    {{-- Gallery Image Thumbnails --}}
                    @foreach($item->images as $img)
                    <div class="thumb-item" onclick="document.getElementById('mainProductImage').src='{{ asset('storage/' . $img->image) }}'" style="flex: 0 0 80px; height: 80px; border-radius: 12px; border: 1px solid var(--elx-border); cursor: pointer; overflow: hidden; background: var(--elx-glass);">
                        <img src="{{ asset('storage/' . $img->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Right: Info --}}
            <div class="product-info" data-animate>
                <div class="stock-badge {{ $item->stock > 0 ? 'stock-in' : 'stock-out' }}">
                    <i class="fas {{ $item->stock > 0 ? 'fa-check' : 'fa-times' }} me-1"></i>
                    {{ $item->stock > 0 ? 'In Stock (' . $item->stock . ')' : 'Out of Stock' }}
                </div>

                {{-- Meta Tags: Category, Brand, Points --}}
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem;">
                    @if($item->category)
                        <span style="background: rgba(74, 200, 246, 0.1); color: #4ac8f6; padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(74, 200, 246, 0.2);">
                            <i class="fas fa-layer-group" style="margin-right: 5px;"></i>{{ $item->category->name }}
                        </span>
                    @endif
                    @if($item->brand)
                        <span style="background: rgba(255, 215, 0, 0.1); color: #ffd700; padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(255, 215, 0, 0.2);">
                            <i class="fas fa-tag" style="margin-right: 5px;"></i>{{ $item->brand }}
                        </span>
                    @endif
                    @if($item->points > 0)
                        <span style="background: rgba(0, 255, 136, 0.1); color: #00ff88; padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(0, 255, 136, 0.2);">
                            <i class="fas fa-star" style="margin-right: 5px;"></i>{{ $item->points }} Points
                        </span>
                    @endif
                </div>
                
                <h1 class="elx-hero__title" style="font-size: 3.5rem; text-align: left; margin-bottom: 1rem;">
                    <span class="elx-hero__title-gradient">{{ $item->name }}</span>
                </h1>
                
                <div style="font-size: 2rem; font-weight: 700; color: var(--elx-white); margin-bottom: 2rem;">
                    SAR {{ number_format($item->price, 2) }}
                </div>

                <p style="color: var(--elx-gray); font-size: 1.1rem; margin-bottom: 3rem; line-height: 1.6;">
                    {{ $item->description }}
                </p>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    
                    <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; background: rgba(255,255,255,0.05); border: 1px solid var(--elx-border); border-radius: 100px; padding: 0.5rem 1rem;">
                            <button type="button" onclick="const i = this.nextElementSibling; if(i.value > 1) i.value--;" style="background: none; border: none; color: white; cursor: pointer; padding: 0 0.5rem;">&minus;</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $item->stock }}" style="width: 50px; text-align: center; background: none; border: none; color: white; font-weight: 700; outline: none;">
                            <button type="button" onclick="const i = this.previousElementSibling; if(i.value < {{ $item->stock }}) i.value++;" style="background: none; border: none; color: white; cursor: pointer; padding: 0 0.5rem;">+</button>
                        </div>
                        <span style="color: var(--elx-gray); font-size: 0.9rem;">Maximum {{ $item->stock }} units</span>
                    </div>

                    <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1.2rem; font-size: 1.2rem;" {{ $item->stock <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart"></i> {{ $item->stock > 0 ? 'Add to Cart' : 'Currently Unavailable' }}
                    </button>
                </form>

                <div style="margin-top: 3rem; display: flex; gap: 2rem;">
                    <div style="display: flex; align-items: center; gap: 0.7rem; color: var(--elx-cyan);">
                        <i class="fas fa-truck-fast"></i>
                        <span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Fast Delivery</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.7rem; color: var(--elx-accent);">
                        <i class="fas fa-shield-halved"></i>
                        <span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Organic Certified</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Blog Section --}}
        @if($item->long_description)
        <div class="blog-section" data-animate>
            <h2>The Ritual Insights</h2>
            <div class="blog-content">
                {{ $item->long_description }}
            </div>
        </div>
        @endif

        {{-- Related Products --}}
        @if(isset($relatedItems) && $relatedItems->count() > 0)
        <div class="elx-section" style="margin-top: 6rem;">
            <div class="elx-section__header" style="text-align: left; margin-bottom: 3rem;" data-animate>
                <h2 class="elx-section__title">✦ Related Rituals</h2>
            </div>
            
            <div class="elx-products__grid" data-animate>
                @foreach($relatedItems as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
