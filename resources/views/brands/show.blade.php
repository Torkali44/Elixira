@extends('layouts.framer')

@section('title', $brand->name . ' - Elixira')

@section('head')
<style>
    .brand-hero {
        position: relative;
        padding: 2rem 0 3rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .brand-header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        margin-bottom: 1.5rem;
        padding: 0 1rem;
    }

    .brand-title-wrap {
        flex: 1;
        display: flex;
        align-items: center;
    }

    .brand-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 3.5rem;
        color: #fff;
        text-shadow: 0 0 15px rgba(74, 200, 246, 0.6), 0 0 30px rgba(74, 200, 246, 0.4);
        margin: 0;
        letter-spacing: 4px;
        font-weight: 700;
    }

    .brand-logo-right {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .brand-countries-inline {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .country-flag-icon {
        width: 28px;
        height: 20px;
        object-fit: cover;
        border-radius: 3px;
        box-shadow: 0 0 0 1px rgba(255,255,255,0.2);
    }

    .brand-logo-wrap {
        /* As per image, logo might not have a box if it's transparent, but let's keep it clean */
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .brand-logo-wrap img {
        height: 100%;
        width: auto;
        object-fit: contain;
    }

    .brand-rating-centered {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        margin-bottom: 1.5rem;
    }

    .brand-rating-label {
        color: var(--elx-gray);
        font-size: 0.95rem;
        letter-spacing: 1px;
    }

    .brand-rating__stars {
        display: flex;
        gap: 0.3rem;
        font-size: 1.2rem;
    }

    .brand-rating__count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid var(--elx-cyan);
        color: var(--elx-cyan);
        font-size: 0.85rem;
        font-weight: 700;
    }

    @keyframes brand-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .brand-vendor-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        max-width: 1000px;
        padding: 1.2rem 2rem;
        border-radius: 16px;
        background: rgba(0, 0, 0, 0.4);
        border: 1px solid rgba(74, 200, 246, 0.2);
        box-shadow: 0 4px 20px rgba(0,0,0,0.5), inset 0 0 15px rgba(74, 200, 246, 0.05);
        margin: 0 auto 2rem;
        transition: border-color 0.35s ease, box-shadow 0.35s ease, background 0.35s ease;
    }

    .brand-vendor-bar:hover {
        border-color: rgba(74, 200, 246, 0.55);
        background: rgba(0, 0, 0, 0.52);
        box-shadow:
            0 0 28px rgba(74, 200, 246, 0.25),
            0 8px 32px rgba(0, 0, 0, 0.55),
            inset 0 0 24px rgba(74, 200, 246, 0.12);
    }

    .brand-vendor-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .brand-vendor-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid var(--elx-cyan);
        box-shadow: 0 0 10px rgba(74, 200, 246, 0.4);
    }

    .brand-vendor-name {
        color: var(--elx-cyan);
        font-weight: 700;
        font-size: 1.15rem;
        letter-spacing: 1px;
    }

    .verification-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--elx-cyan);
        font-size: 1.05rem;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .brand-social-links {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .brand-social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        color: var(--elx-light);
        transition: var(--elx-transition);
        font-size: 1.15rem;
    }

    .brand-social-link:hover {
        background: rgba(74, 200, 246, 0.2);
        color: var(--elx-cyan);
        box-shadow: 0 0 14px rgba(74, 200, 246, 0.35);
        animation: brand-float 1.1s ease-in-out infinite;
    }

    .brand-store-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--elx-cyan);
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        margin-right: 1rem;
        transition: color 0.3s ease, text-shadow 0.3s ease;
    }

    .brand-store-link:hover {
        text-shadow: 0 0 10px rgba(74, 200, 246, 0.65);
    }

    .brand-description {
        color: var(--elx-gray);
        font-size: 1rem;
        line-height: 1.7;
        margin: 0 auto 3rem;
        max-width: 1000px;
        text-align: left;
        width: 100%;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .brand-section-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 2rem;
        text-align: center;
        color: var(--elx-white);
        margin-bottom: 2.5rem;
        padding-top: 2rem;
        border-top: 1px solid var(--elx-border);
    }

    .similar-brands-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    .similar-brand-card {
        padding: 1.5rem;
        border-radius: 20px;
        background: var(--elx-glass);
        border: 1px solid var(--elx-border);
        text-align: center;
        transition: var(--elx-transition);
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .similar-brand-card:hover {
        border-color: var(--elx-cyan);
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(74, 200, 246, 0.1);
    }

    .similar-brand-logo {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        object-fit: cover;
        margin: 0 auto 1rem;
        display: block;
        border: 1px solid var(--elx-border);
    }

    .similar-brand-name {
        font-weight: 700;
        color: var(--elx-white);
        margin-bottom: 0.3rem;
    }

    .similar-brand-count {
        font-size: 0.8rem;
        color: var(--elx-light);
    }

    @media (max-width: 768px) {
        .brand-header-top { flex-direction: column; gap: 1.5rem; text-align: center; }
        .brand-title { font-size: 2.2rem; }
        .brand-vendor-bar { flex-direction: column; gap: 1.5rem; align-items: stretch; text-align: center; }
        .brand-vendor-info { flex-direction: column; }
        .brand-social-links { justify-content: center; margin-top: 1rem; }
        .similar-brands-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        {{-- Brand Hero (Header + Rating + Vendor Bar) --}}
        <div class="brand-hero" data-animate>
            
            <div class="brand-header-top">
                <div class="brand-title-wrap">
                    <h1 class="brand-title">{{ $brand->name }}</h1>
                </div>

                <div class="brand-logo-right">
                    @if($brand->service_countries)
                        <div class="brand-countries-inline">
                            @foreach($brand->service_countries as $country)
                                @php
                                    $flagSrc = '';
                                    if (stripos($country, 'Saudi') !== false || stripos($country, 'KSA') !== false) {
                                        $flagSrc = asset('images/sa.png');
                                    } elseif (stripos($country, 'Emirates') !== false || stripos($country, 'UAE') !== false) {
                                        $flagSrc = asset('images/AE.png');
                                    }
                                @endphp
                                @if($flagSrc)
                                    <img src="{{ $flagSrc }}" alt="{{ $country }}" class="country-flag-icon" title="{{ $country }}">
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <div class="brand-logo-wrap">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}">
                        @else
                            <div class="brand-logo-placeholder"><i class="fas fa-store"></i></div>
                        @endif
                    </div>
                </div>
            </div>

            @php
                $displayRating = $brand->average_rating ?: 0;
            @endphp
            <div class="brand-rating-centered">
                <span class="brand-rating-label">{{ __('brands_page.rating_label') }}</span>
                <div class="brand-rating__stars" style="margin-right: 0.5rem;">
                    @for($i = 1; $i <= 5; $i++)
                        @if($displayRating >= $i)
                            <i class="fas fa-star" style="color: #00e5ff;"></i>
                        @elseif($displayRating >= $i - 0.5)
                            <i class="fas fa-star-half-alt" style="color: #00e5ff;"></i>
                        @else
                            <i class="far fa-star" style="color: rgba(255,255,255,0.2);"></i>
                        @endif
                    @endfor
                </div>
                <span class="brand-rating__count" style="margin-right: 1rem;">{{ number_format($displayRating, 1) }}</span>
                
                @auth
                    <button type="button" class="glow-rate-btn" onclick="document.getElementById('rateBrandModal').classList.add('show')">
                        <i class="fas fa-star me-1"></i> {{ __('brands_page.rate') }}
                    </button>
                @else
                    <a href="{{ route('login') }}" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #ccc; padding: 0.3rem 1rem; border-radius: 50px; font-size: 0.85rem; text-decoration: none;">
                        {{ __('brands_page.login_to_rate') }}
                    </a>
                @endauth
            </div>

            {{-- Vendor Info Bar --}}
            <div class="brand-vendor-bar">
                <div class="brand-vendor-info">
                    @if($brand->vendorProfile && $brand->vendorProfile->user)
                        <div class="brand-vendor-avatar">
                            <x-user-avatar :user="$brand->vendorProfile->user" size="64" />
                        </div>
                        <span class="brand-vendor-name">{{ $brand->vendorProfile->user->name }}</span>
                        <x-phone-flag :phone="$brand->vendorProfile->user->phone" :show-phone="false" />
                    @endif
                </div>

                @if($brand->vendorProfile && $brand->vendorProfile->user && $brand->vendorProfile->user->user_code)
                    <div class="verification-badge">
                        <i class="fas fa-shield-check"></i>
                        <span style="font-family: monospace; font-size: 1.1rem;">{{ $brand->vendorProfile->user->user_code }}</span>
                    </div>
                @endif

                <div class="brand-social-links">
                    @if($brand->store_link)
                        <a href="{{ $brand->store_link }}" target="_blank" class="brand-store-link">
                            <i class="fas fa-link"></i> {{ __('brands_page.visit_shop') }}
                        </a>
                    @endif

                    @if($brand->instagram_link)
                        <a href="{{ $brand->instagram_link }}" target="_blank" class="brand-social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if($brand->tiktok_link)
                        <a href="{{ $brand->tiktok_link }}" target="_blank" class="brand-social-link" title="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    @endif
                    @if($brand->snapchat_link)
                        <a href="{{ $brand->snapchat_link }}" target="_blank" class="brand-social-link" title="Snapchat">
                            <i class="fab fa-snapchat-ghost"></i>
                        </a>
                    @endif
                    @if($brand->twitter_link)
                        <a href="{{ $brand->twitter_link }}" target="_blank" class="brand-social-link" title="X / Twitter">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Brand Description --}}
            @if($brand->description)
                <div class="brand-description" data-animate>
                    {{ $brand->description }}
                </div>
            @endif
        </div>

        {{-- Products Section --}}
        <h2 class="brand-section-title" data-animate>{{ __('brands_page.our_products') }}</h2>

        @if($products->count() > 0)
            <div class="elx-products__grid menu-products-grid" data-animate>
                @foreach($products as $product)
                    <div class="product-item" data-animate>
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 3rem; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 4rem 1rem; color: var(--elx-light);">
                <i class="fas fa-box-open" style="font-size: 3rem; color: rgba(255,255,255,0.15); margin-bottom: 1rem; display: block;"></i>
                <p>{{ __('brands_page.no_products') }}</p>
            </div>
        @endif

        {{-- Similar Brands --}}
        @if($similarBrands->count() > 0)
        <div style="margin-top: 5rem;" data-animate>
            <h2 class="brand-section-title">{{ __('brands_page.similar_brands') }}</h2>
            <div class="similar-brands-grid">
                @foreach($similarBrands as $sBrand)
                    <a href="{{ route('brands.show', $sBrand->slug) }}" class="similar-brand-card">
                        @if($sBrand->logo)
                            <img src="{{ asset('storage/' . $sBrand->logo) }}" alt="{{ $sBrand->name }}" class="similar-brand-logo">
                        @else
                            <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(74,200,246,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--elx-cyan); font-size: 1.5rem;">
                                <i class="fas fa-store"></i>
                            </div>
                        @endif
                        <div class="similar-brand-name">{{ $sBrand->name }}</div>
                        <div class="similar-brand-count">{{ __('brands_page.products_count', ['count' => $sBrand->items_count]) }}</div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
        {{-- Reviews Section --}}
        <div class="mt-5 mb-5">
            <div class="reviews-header mb-4">
                <h3 class="text-white" style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 2rem;">{{ __('brands_page.customer_reviews') }}</h3>
                <div style="height: 3px; width: 60px; background: var(--elx-cyan); border-radius: 3px; margin-top: 10px;"></div>
            </div>

            @if($brand->ratings->count() > 0)
                <div class="reviews-grid">
                    @foreach($brand->ratings as $rating)
                        @php
                            $isReviewExpandable = \Illuminate\Support\Str::length((string) $rating->comment) > 180;
                        @endphp
                        <div class="review-card"
                            data-expandable="{{ $isReviewExpandable ? '1' : '0' }}"
                            data-review-name="{{ $rating->user ? $rating->user->name : __('brands_page.guest') }}"
                            data-review-rating="{{ $rating->rating }}"
                            data-review-content="{{ e($rating->comment) }}">
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
                                            {{ $rating->user ? $rating->user->name : __('brands_page.guest') }}
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
                                    <p class="brand-review-message">{{ $rating->comment }}</p>
                                    @if($isReviewExpandable)
                                        <div class="brand-review-read-more">{{ __('brands_page.tap_read_review') }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-reviews-box">
                    <i class="fas fa-comment-slash" style="font-size: 3rem; color: rgba(255,255,255,0.1); margin-bottom: 1rem;"></i>
                    <p style="color: #ccc; margin: 0;">{{ __('brands_page.no_reviews') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Rating Modal --}}
<div class="custom-modal" id="rateBrandModal">
    <div class="custom-modal-dialog">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">{{ __('brands_page.rate_brand', ['name' => $brand->name]) }}</h5>
                <button type="button" class="custom-modal-close" onclick="document.getElementById('rateBrandModal').classList.remove('show')">&times;</button>
            </div>
            <form action="{{ route('ratings.store') }}" method="POST">
                @csrf
                <div class="custom-modal-body">
                    <input type="hidden" name="rateable_id" value="{{ $brand->id }}">
                    <input type="hidden" name="rateable_type" value="App\Models\Brand">
                    
                    <div class="rating-stars-container">
                        <label class="rating-label">{{ __('brands_page.your_rating') }}</label>
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
                        <label for="comment" class="rating-label">{{ __('brands_page.comment_optional') }}</label>
                        <textarea class="rating-textarea" name="comment" id="comment" rows="3" placeholder="{{ __('brands_page.comment_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="custom-modal-footer">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('rateBrandModal').classList.remove('show')">{{ __('popups.cancel') }}</button>
                    <button type="submit" class="btn-submit">{{ __('brands_page.submit_rating') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    .menu-products-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 3rem;
    }
    @media (max-width: 1024px) {
        .menu-products-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 2rem; }
    }
    @media (max-width: 768px) {
        .menu-products-grid { grid-template-columns: 1fr; gap: 1.5rem; }
    }

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
        padding: 0.3rem 1.2rem;
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
        70% { box-shadow: 0 0 0 5px rgba(0, 229, 255, 0); }
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

    .review-card[data-expandable="1"] {
        cursor: pointer;
    }

    .review-card:hover {
        transform: translateY(-5px);
        border-color: rgba(0, 229, 255, 0.2);
    }

    .brand-review-message {
        display: -webkit-box;
        -webkit-line-clamp: 6;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .brand-review-read-more {
        margin-top: 0.75rem;
        text-align: center;
        color: #4ac8f6;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const escapeHtml = (unsafeText) => {
            const div = document.createElement('div');
            div.textContent = unsafeText;
            return div.innerHTML;
        };

        document.querySelectorAll('.review-card[data-expandable="1"]').forEach((card) => {
            card.addEventListener('click', () => {
                const reviewerName = card.dataset.reviewName || @json(__('popups.customer'));
                const reviewContent = card.dataset.reviewContent || '';
                const reviewRating = Number(card.dataset.reviewRating || 0);
                const stars = '★'.repeat(reviewRating) + '☆'.repeat(Math.max(0, 5 - reviewRating));

                Swal.fire({
                    title: escapeHtml(reviewerName),
                    html: `
                        <div style="text-align: start;">
                            <div style="color:#4ac8f6; font-size:1.1rem; letter-spacing:2px; margin-bottom:12px;">${stars}</div>
                            <div style="line-height:1.9; color:#eaf4f8; font-size:1rem; white-space:pre-wrap;">${escapeHtml(reviewContent)}</div>
                        </div>
                    `,
                    background: '#0d1a20',
                    color: '#eaf4f8',
                    width: 640,
                    confirmButtonText: @json(__('brands_page.close')),
                    confirmButtonColor: '#4ac8f6',
                });
            });
        });
    });
</script>
@endsection
