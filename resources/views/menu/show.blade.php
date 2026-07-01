@extends('layouts.framer')

@section('title', $item->local_name . ' - Elixira')

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
        position: relative;
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
@php
    $privateQty = (int) ($privateOfferQuantities[$item->id] ?? 0);
    $hasPrivateAccess = $privateQty > 0;
    $availableQty = $item->stock + $privateQty;
@endphp
<div class="page-content">
    <div class="elx-container">
        <div class="product-detail-grid">
            {{-- Left: Image --}}
            <div class="product-gallery" data-animate>
                <div class="main-img-container">

                    @if($item->image)
                        <img src="{{ $item->image_url }}" alt="{{ $item->local_name }}" id="mainProductImage">
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
                <div class="stock-badge {{ ($item->stock > 0 || $hasPrivateAccess) ? 'stock-in' : 'stock-out' }}">
                    <i class="fas {{ ($item->stock > 0 || $hasPrivateAccess) ? 'fa-check' : 'fa-times' }} me-1"></i>
                    {{ $item->stock > 0 ? __('shop.in_stock_label', ['count' => $item->stock]) : ($hasPrivateAccess ? __('shop.private_access_available') : __('shop.out_of_stock')) }}
                </div>

                {{-- Meta Tags: Category, Brand, Points --}}
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem;">
                    @if($item->category)
                        <span style="background: rgba(74, 200, 246, 0.1); color: #4ac8f6; padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(74, 200, 246, 0.2);">
                            <i class="fas fa-layer-group" style="margin-right: 5px;"></i>{{ $item->category->local_name }}
                        </span>
                    @endif
                    @if($item->brandModel)
                        <a href="{{ route('brands.show', $item->brandModel->slug) }}" style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 215, 0, 0.1); color: #ffd700; padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(255, 215, 0, 0.2); text-decoration: none; transition: all 0.2s ease;">
                            @if($item->brandModel->logo)
                                <img src="{{ asset('storage/' . $item->brandModel->logo) }}" style="width: 20px; height: 20px; border-radius: 5px; object-fit: cover;" alt="">
                            @else
                                <i class="fas fa-store" style="margin-right: 3px;"></i>
                            @endif
                            {{ $item->brandModel->name }}
                        </a>
                    @elseif($item->brand)
                        <span style="background: rgba(255, 215, 0, 0.1); color: #ffd700; padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(255, 215, 0, 0.2);">
                            <i class="fas fa-tag" style="margin-right: 5px;"></i>{{ $item->brand }}
                        </span>
                    @endif

                    {{-- Brand Service Countries --}}
                    @if($item->brandModel && $item->brandModel->service_countries)
                        @foreach($item->brandModel->service_countries as $country)
                            @php
                                $flagSrc = '';
                                if (stripos($country, 'Saudi') !== false || stripos($country, 'KSA') !== false) {
                                    $flagSrc = asset('images/sa.png');
                                } elseif (stripos($country, 'Emirates') !== false || stripos($country, 'UAE') !== false) {
                                    $flagSrc = asset('images/AE.png');
                                }
                            @endphp
                            @if($flagSrc)
                                <img src="{{ $flagSrc }}" style="width: 24px; height: 16px; border-radius: 3px; box-shadow: 0 2px 8px rgba(0,0,0,0.3); margin-top: 5px;" alt="{{ $country }}" title="{{ $country }}">
                            @endif
                        @endforeach
                    @endif

                    @if(($item->reward_points ?? 0) > 0)
                        <span style="background: rgba(0, 255, 136, 0.1); color: #00ff88; padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(0, 255, 136, 0.2);">
                            <i class="fas fa-star" style="margin-right: 5px;"></i>{{ __('home.reward_points', ['count' => $item->reward_points]) }}
                        </span>
                    @endif
                </div>
                
                <h1 class="elx-hero__title" style="font-size: 3.5rem; text-align: left; margin-bottom: 0.5rem;">
                    <span class="elx-hero__title-gradient">{{ $item->local_name }}</span>
                </h1>

                @php
                    $itemRating = $item->average_rating ?: 0;
                @endphp
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="color: #00e5ff; font-size: 1.2rem;">
                        @for($i = 1; $i <= 5; $i++)
                            @if($itemRating >= $i)
                                <i class="fas fa-star"></i>
                            @elseif($itemRating >= $i - 0.5)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star" style="color: rgba(255,255,255,0.2);"></i>
                            @endif
                        @endfor
                    </div>
                    <span style="color: var(--elx-cyan); font-weight: 700;">{{ number_format($itemRating, 1) }}</span>
                    
                    @auth
                        <button type="button" class="glow-rate-btn" onclick="document.getElementById('rateItemModal').classList.add('show')">
                            <i class="fas fa-star me-1"></i> Rate
                        </button>
                    @else
                        <a href="{{ route('login') }}" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #ccc; padding: 0.2rem 0.8rem; border-radius: 50px; font-size: 0.8rem; text-decoration: none;">
                            Login to rate
                        </a>
                    @endauth
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <x-product-pricing :item="$item" :selected-country="$selectedCountry ?? null" align="flex-start" size="2rem" smallSize="1rem" />
                </div>

                <p style="color: var(--elx-gray); font-size: 1.1rem; margin-bottom: 3rem; line-height: 1.6;">
                    {{ $item->local_description }}
                </p>

                @if($item->stock <= 0 && !$hasPrivateAccess)
                    <button type="button" class="elx-btn" style="width: 100%; justify-content: center; padding: 1.2rem; font-size: 1.2rem; background: rgba(255, 77, 77, 0.1); color: #ff4d4d; border: 1px solid rgba(255, 77, 77, 0.3);" onclick="showSpecialRequestModal({{ $item->id }}, '{{ addslashes($item->local_name) }}')">
                        <i class="fas fa-hand-holding-heart"></i> {{ __('home.private_order') }}
                    </button>
                @else
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <input type="hidden" name="country_code" value="{{ $selectedCountry ?? app(\App\Support\ItemPricingService::class)->detectUserCountry() }}">
                        
                        <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 2rem;">
                            <div style="display: flex; align-items: center; background: rgba(255,255,255,0.05); border: 1px solid var(--elx-border); border-radius: 100px; padding: 0.5rem 1rem;">
                                <button type="button" onclick="const i = this.nextElementSibling; if(i.value > 1) i.value--;" style="background: none; border: none; color: white; cursor: pointer; padding: 0 0.5rem;">&minus;</button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $availableQty }}" style="width: 50px; text-align: center; background: none; border: none; color: white; font-weight: 700; outline: none;">
                                <button type="button" onclick="const i = this.previousElementSibling; if(i.value < {{ $availableQty }}) i.value++;" style="background: none; border: none; color: white; cursor: pointer; padding: 0 0.5rem;">+</button>
                            </div>
                            <span style="color: var(--elx-gray); font-size: 0.9rem;">{{ __('shop.maximum_units', ['count' => $availableQty]) }}</span>
                        </div>

                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <button type="submit" class="elx-btn elx-btn--primary" style="flex: 1; min-width: 180px; justify-content: center; padding: 1.2rem; font-size: 1.1rem;">
                                <i class="fas fa-shopping-cart"></i> {{ __('home.add_to_cart') }}
                            </button>
                            <button type="submit" name="buy_now" value="1" class="elx-btn elx-btn--glass" style="flex: 1; min-width: 180px; justify-content: center; padding: 1.2rem; font-size: 1.1rem; border-color: rgba(74, 200, 246, 0.4); color: #4ac8f6;">
                                <i class="fas fa-bolt"></i> {{ __('home.buy_now') }}
                            </button>
                        </div>
                    </form>
                @endif

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

                @if($otherSellers->count() > 0)
                    <div style="margin-top: 3rem; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--elx-border); border-radius: var(--elx-radius-sm); padding: 2rem;">
                        <h4 style="color: #fff; margin-bottom: 1.5rem; font-family: 'Bricolage Grotesque', sans-serif;">
                            <i class="fas fa-store-alt text-cyan me-2"></i> Other Sellers for this Product
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 1.2rem;">
                            @foreach($otherSellers as $sellerItem)
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; padding-bottom: 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        @if($sellerItem->brandModel && $sellerItem->brandModel->logo)
                                            <img src="{{ asset('storage/' . $sellerItem->brandModel->logo) }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;" alt="">
                                        @else
                                            <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff;">
                                                <i class="fas fa-store"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong style="color: #fff; display: block;">
                                                @if($sellerItem->brandModel)
                                                    <a href="{{ route('brands.show', $sellerItem->brandModel->slug) }}" style="color: var(--elx-cyan); text-decoration: none;">
                                                        {{ $sellerItem->brandModel->name }}
                                                    </a>
                                                @else
                                                    {{ $sellerItem->brand }}
                                                @endif
                                            </strong>
                                            
                                            {{-- Country Flag --}}
                                            @if($sellerItem->brandModel && $sellerItem->brandModel->service_countries)
                                                <div style="display: flex; gap: 4px; margin-top: 4px;">
                                                    @foreach($sellerItem->brandModel->service_countries as $country)
                                                        @php
                                                            $flagSrc = '';
                                                            if (stripos($country, 'Saudi') !== false || stripos($country, 'KSA') !== false) {
                                                                $flagSrc = asset('images/sa.png');
                                                            } elseif (stripos($country, 'Emirates') !== false || stripos($country, 'UAE') !== false) {
                                                                $flagSrc = asset('images/AE.png');
                                                            }
                                                        @endphp
                                                        @if($flagSrc)
                                                            <img src="{{ $flagSrc }}" style="width: 16px; height: 10px; border-radius: 1px;" alt="{{ $country }}" title="{{ $country }}">
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div style="text-align: right;">
                                        <div style="font-size: 1.15rem; font-weight: 700; color: #fff;">
                                            ﷼ {{ number_format($sellerItem->price, 2) }}
                                        </div>
                                        @if($sellerItem->points > 0)
                                            <small style="color: #00ff88; font-weight: 600;">
                                                <i class="fas fa-star"></i> +{{ $sellerItem->points }} Points
                                            </small>
                                        @endif
                                        <div style="font-size: 0.75rem; color: #aaa; margin-top: 2px;">
                                            {{ $sellerItem->stock > 0 ? $sellerItem->stock . ' units left' : 'Out of stock' }}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        @if($sellerItem->stock > 0)
                                            <form action="{{ route('cart.add') }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $sellerItem->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="glow-rate-btn" style="background: rgba(74, 200, 246, 0.1); border: 1px solid rgba(74, 200, 246, 0.3); color: var(--elx-cyan); animation: none; padding: 0.4rem 1rem;">
                                                    Buy from Vendor <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span style="color: #ff8a8a; font-size: 0.85rem; font-weight: 600;">Out of Stock</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Blog Section --}}
        @if($item->local_long_description)
        <div class="blog-section" data-animate>
            <h2>The Ritual Insights</h2>
            <div class="blog-content">
                {{ $item->local_long_description }}
            </div>
        </div>
        @endif

        {{-- Related Products --}}
        @if(isset($relatedItems) && $relatedItems->count() > 0)
        <div class="elx-section" style="margin-top: 6rem;">
            <div class="elx-section__header" style="text-align: left; margin-bottom: 3rem;" data-animate>
                <h2 class="elx-section__title">{{ __('shop.related_products') }}</h2>
            </div>
            
            <div class="elx-products__grid menu-products-grid" data-animate>
                @foreach($relatedItems as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
        @endif

        {{-- Related Blogs --}}
        @if(isset($relatedBlogs) && $relatedBlogs->count() > 0)
        <div class="elx-section" style="margin-top: 4rem;">
            <div class="elx-section__header" style="text-align: left; margin-bottom: 2rem;" data-animate>
                <h2 class="elx-section__title">{{ __('shop.related_blogs') }}</h2>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;" data-animate>
                @foreach($relatedBlogs as $blog)
                    <a href="{{ route('blogs.show', $blog->slug) }}"
                       style="display: block; text-decoration: none; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; overflow: hidden; transition: transform 0.2s ease;">
                        @if($blog->image)
                            <img src="{{ asset('storage/'.$blog->image) }}" alt="{{ $blog->title }}" style="width: 100%; height: 160px; object-fit: cover;">
                        @endif
                        <div style="padding: 1.1rem 1.2rem 1.3rem;">
                            <div style="color: var(--elx-cyan); font-size: 0.75rem; font-weight: 700; margin-bottom: 0.5rem;">
                                {{ $blog->published_at?->format('M d, Y') ?? $blog->created_at->format('M d, Y') }}
                            </div>
                            <h3 style="color: #fff; font-size: 1.05rem; line-height: 1.45; margin: 0;">{{ $blog->title }}</h3>
                            <p style="color: rgba(255,255,255,0.65); font-size: 0.88rem; margin: 0.75rem 0 0;">{{ Str::limit($blog->summary, 110) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Related Testimonials / Videos --}}
        @if(isset($relatedReviews) && $relatedReviews->count() > 0)
        <div class="elx-section" style="margin-top: 4rem;">
            <div class="elx-section__header" style="text-align: left; margin-bottom: 2rem;" data-animate>
                <h2 class="elx-section__title">{{ __('shop.related_testimonials') }}</h2>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;" data-animate>
                @foreach($relatedReviews as $review)
                    <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; overflow: hidden;">
                        @if($review->type === 'video')
                            @php $embed = \App\Support\YoutubeEmbed::fromUrl($review->content); @endphp
                            @if($embed)
                            <div style="position: relative; padding-top: 56.25%; background: #000;">
                                <iframe src="{{ $embed }}" title="YouTube video" style="position:absolute; inset:0; width:100%; height:100%; border:0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
                            </div>
                            @else
                            <div style="padding: 1.5rem; color: rgba(255,255,255,0.6); font-size: 0.9rem;">{{ __('shop.video_unavailable') }}</div>
                            @endif
                        @elseif($review->avatar)
                            <img src="{{ asset('storage/'.$review->avatar) }}" alt="" style="width: 100%; height: 220px; object-fit: cover;">
                        @endif
                        <div style="padding: 1rem 1.2rem 1.3rem;">
                            <span style="color: var(--elx-cyan); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                                {{ __('testimonials_page.tab_'.$review->type) }}
                            </span>
                            @if($review->content && $review->type !== 'video')
                                <p style="color: rgba(255,255,255,0.75); font-size: 0.9rem; margin: 0.75rem 0 0;">{{ Str::limit($review->content, 140) }}</p>
                            @endif
                            <a href="{{ route('testimonials.index', ['tab' => $review->type]) }}" style="display: inline-block; margin-top: 0.75rem; color: #4ac8f6; font-size: 0.85rem; text-decoration: none;">{{ __('shop.view_all_testimonials') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        {{-- Reviews Section --}}
        <div class="mt-5 mb-5">
            <div class="reviews-header mb-4">
                <h3 class="text-white" style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 2rem;">{{ __('shop.customer_reviews') }}</h3>
                <div style="height: 3px; width: 60px; background: var(--elx-cyan); border-radius: 3px; margin-top: 10px;"></div>
            </div>

            @if($item->ratings->count() > 0)
                <div class="reviews-grid">
                    @foreach($item->ratings as $rating)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="review-avatar">
                                        @if($rating->user && $rating->user->avatar_url)
                                            <img src="{{ $rating->user->avatar_url }}" alt="{{ $rating->user->name }}">
                                        @else
                                            <div class="review-avatar-placeholder">
                                                {{ $rating->user ? $rating->user->avatar_initials : 'G' }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="review-meta">
                                        <h5 class="review-author">
                                            {{ $rating->user ? $rating->user->name : 'Guest' }}
                                            @if($rating->user && $rating->user->phone)
                                                <span class="ms-1"><x-phone-flag :phone="$rating->user->phone" :show-phone="false" /></span>
                                            @else
                                                <span class="review-country">🇸🇦</span>
                                            @endif
                                        </h5>
                                        <span class="review-date">{{ $rating->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="review-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $rating->rating ? 'fas fa-star' : 'far fa-star' }}" style="color: #00e5ff;"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($rating->comment)
                                <div class="review-body">
                                    <p>{{ $rating->comment }}</p>
                                </div>
                            @endif
                            @if($rating->image)
                                <div style="margin-top: 0.75rem;">
                                    <img src="{{ asset('storage/'.$rating->image) }}" alt="" style="max-width: 100%; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-reviews-box">
                    <i class="fas fa-comment-slash" style="font-size: 3rem; color: rgba(255,255,255,0.1); margin-bottom: 1rem;"></i>
                    <p style="color: #ccc; margin: 0;">No reviews yet. Be the first to rate this product!</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Rating Modal --}}
<div class="custom-modal" id="rateItemModal">
    <div class="custom-modal-dialog">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">Rate {{ $item->name }}</h5>
                <button type="button" class="custom-modal-close" onclick="document.getElementById('rateItemModal').classList.remove('show')">&times;</button>
            </div>
            <form action="{{ route('ratings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="custom-modal-body">
                    <input type="hidden" name="rateable_id" value="{{ $item->id }}">
                    <input type="hidden" name="rateable_type" value="App\Models\Item">
                    
                    <div class="rating-stars-container">
                        <label class="rating-label">Your Rating</label>
                        <div class="rating-stars-input">
                            <input type="radio" name="rating" id="star5" value="5" required>
                            <label for="star5" class="fas fa-star"></label>
                            
                            <input type="radio" name="rating" id="star4" value="4">
                            <label for="star4" class="fas fa-star"></label>
                            
                            <input type="radio" name="rating" id="star3" value="3">
                            <label for="star3" class="fas fa-star"></label>
                            
                            <input type="radio" name="rating" id="star2" value="2">
                            <label for="star2" class="fas fa-star"></label>
                            
                            <input type="radio" name="rating" id="star1" value="1">
                            <label for="star1" class="fas fa-star"></label>
                        </div>
                    </div>
                    
                    <div class="rating-comment-container">
                        <label for="comment" class="rating-label">{{ __('shop.review_comment') }}</label>
                        <textarea class="rating-textarea" name="comment" id="comment" rows="3" placeholder="{{ __('shop.review_comment_placeholder') }}"></textarea>
                    </div>

                    <div class="rating-comment-container" style="margin-top: 1rem;">
                        <label for="rating_image" class="rating-label">{{ __('shop.review_image') }}</label>
                        <input type="file" name="image" id="rating_image" accept="image/*" class="form-control" style="background: rgba(255,255,255,0.05); border-color: var(--elx-border); color: #fff;">
                    </div>
                </div>
                <div class="custom-modal-footer">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('rateItemModal').classList.remove('show')">Cancel</button>
                    <button type="submit" class="btn-submit">Submit Rating</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Custom Modal CSS */
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .custom-modal.show {
        display: flex;
        opacity: 1;
    }

    .custom-modal-dialog {
        background: #0a1a22;
        border: 1px solid rgba(74, 200, 246, 0.3);
        border-radius: 20px;
        width: 90%;
        max-width: 450px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.5), inset 0 0 20px rgba(74, 200, 246, 0.05);
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }

    .custom-modal.show .custom-modal-dialog {
        transform: translateY(0);
    }

    .custom-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .custom-modal-title {
        color: #fff;
        margin: 0;
        font-size: 1.25rem;
        font-family: 'Bricolage Grotesque', sans-serif;
    }

    .custom-modal-close {
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.5);
        font-size: 1.8rem;
        line-height: 1;
        cursor: pointer;
        transition: color 0.2s;
    }

    .custom-modal-close:hover {
        color: #fff;
    }

    .custom-modal-body {
        padding: 1.5rem;
    }

    .rating-stars-container {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .rating-label {
        display: block;
        color: #fff;
        margin-bottom: 0.8rem;
        font-size: 0.95rem;
    }

    .rating-stars-input {
        display: inline-flex;
        flex-direction: row-reverse;
        font-size: 2.5rem;
        color: rgba(255,255,255,0.15);
        cursor: pointer;
    }

    .rating-stars-input input {
        display: none;
    }

    .rating-stars-input label {
        margin: 0 0.2rem;
        transition: color 0.2s, transform 0.2s;
        cursor: pointer;
    }

    .rating-stars-input label:hover,
    .rating-stars-input label:hover ~ label,
    .rating-stars-input input:checked ~ label {
        color: #00e5ff;
        text-shadow: 0 0 15px rgba(0, 229, 255, 0.4);
    }

    .rating-stars-input label:hover {
        transform: scale(1.1);
    }

    .rating-textarea {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
        border-radius: 12px;
        padding: 1rem;
        font-family: inherit;
        resize: vertical;
        transition: border-color 0.2s;
    }

    .rating-textarea:focus {
        outline: none;
        border-color: #00e5ff;
    }

    .custom-modal-footer {
        padding: 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.05);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-cancel {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: rgba(255,255,255,0.05);
    }

    .btn-submit {
        background: var(--elx-cyan);
        border: none;
        color: #000;
        font-weight: 700;
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 229, 255, 0.3);
    }

    /* Glow Rate Button */
    .glow-rate-btn {
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.5);
        color: #00e5ff;
        padding: 0.3rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        animation: pulse-glow 2s infinite;
        display: inline-flex;
        align-items: center;
        font-weight: 600;
    }

    .glow-rate-btn:hover {
        background: rgba(0, 229, 255, 0.2);
        transform: translateY(-2px);
        animation: none;
        box-shadow: 0 5px 15px rgba(0, 229, 255, 0.4);
    }

    @keyframes pulse-glow {
        0% { box-shadow: 0 0 0 0 rgba(0, 229, 255, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(0, 229, 255, 0); }
        100% { box-shadow: 0 0 0 0 rgba(0, 229, 255, 0); }
    }

    /* Reviews Section */
    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
        padding-top: 1rem;
    }

    .review-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        padding: 1.5rem;
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease, border-color 0.3s ease;
    }

    .review-card:hover {
        transform: translateY(-5px);
        border-color: rgba(0, 229, 255, 0.2);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .review-avatar img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(0, 229, 255, 0.2);
    }

    .review-avatar-placeholder {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.2), rgba(0, 229, 255, 0.05));
        color: #00e5ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        border: 1px solid rgba(0, 229, 255, 0.3);
    }

    .review-author {
        color: #fff;
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .review-country {
        font-size: 1rem;
    }

    .review-date {
        color: rgba(255, 255, 255, 0.4);
        font-size: 0.8rem;
    }

    .review-stars {
        font-size: 0.9rem;
    }

    .review-body p {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
    }

    .no-reviews-box {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 16px;
        border: 1px dashed rgba(255, 255, 255, 0.1);
    }
</style>
@endsection
