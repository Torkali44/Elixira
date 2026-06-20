@extends('layouts.framer')

@section('title', $package->local_name)

@section('head')
<style>
    .package-hero-section {
        background: linear-gradient(180deg, #0d1a20 0%, #000 100%);
        padding: 80px 0 60px;
    }
    .package-detail-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 4rem;
        align-items: start;
    }
    .package-image-wrap {
        position: relative;
        border-radius: 28px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(74,200,246,0.08), rgba(0,255,136,0.05));
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 30px 80px rgba(0,0,0,0.5), 0 0 60px rgba(74,200,246,0.08);
    }
    .package-image-wrap img {
        width: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
        display: block;
    }
    .package-image-wrap .pkg-badge {
        position: absolute;
        top: 1.25rem;
        {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 1.25rem;
        background: linear-gradient(135deg, #ffd700, #ffaa00);
        color: #000;
        padding: 0.4rem 1rem;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        box-shadow: 0 4px 15px rgba(255,215,0,0.4);
    }
    .package-gallery-placeholder {
        aspect-ratio: 1/1;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--elx-cyan);
        font-size: 5rem;
        opacity: 0.4;
    }
    .package-info-panel {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 24px;
        padding: 2.5rem;
        backdrop-filter: blur(10px);
    }
    .package-includes-list {
        list-style: none;
        padding: 0;
        margin: 0 0 2rem;
        display: grid;
        gap: 0.75rem;
    }
    .package-includes-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 14px;
        padding: 0.85rem 1rem;
        transition: background 0.2s ease;
    }
    .package-includes-item:hover {
        background: rgba(74,200,246,0.06);
        border-color: rgba(74,200,246,0.15);
    }
    .package-cta-row {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
        margin-top: 0.5rem;
    }
    @media (max-width: 991px) {
        .package-detail-grid { grid-template-columns: 1fr; gap: 2rem; }
        .package-info-panel { padding: 1.75rem; }
        .package-cta-row { flex-direction: column; }
        .package-cta-row .elx-btn { width: 100%; min-width: unset; justify-content: center; }
    }
</style>
@endsection

@section('content')
<div class="page-content" style="padding-top: 0;">

    <section class="package-hero-section">
        <div class="elx-container">
            <div class="package-detail-grid">

                {{-- Image Column --}}
                <div class="package-image-wrap" data-animate>
                    @if($package->image)
                        <img src="{{ asset('storage/'.$package->image) }}" alt="{{ $package->local_name }}">
                    @else
                        <div class="package-gallery-placeholder"><i class="fas fa-box-open"></i></div>
                    @endif
                    <span class="pkg-badge">{{ __('shop.package_badge') }}</span>
                </div>

                {{-- Info Column --}}
                <div class="package-info-panel" data-animate>
                    <div style="margin-bottom: 1.25rem;">
                        <span style="color: rgba(255,255,255,0.4); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em;">{{ __('shop.package_badge') }}</span>
                        <h1 style="font-size: clamp(1.6rem, 3.5vw, 2.25rem); font-weight: 800; margin: 0.4rem 0 0; background: linear-gradient(135deg, #fff 0%, #4ac8f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.2; text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};">
                            {{ $package->local_name }}
                        </h1>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <x-package-pricing :package="$package" :selected-country="$selectedCountry" align="{{ app()->getLocale() === 'ar' ? 'flex-end' : 'flex-start' }}" size="2rem" smallSize="1rem" />
                    </div>

                    @if(($package->reward_points ?? 0) > 0 || (int) $package->stock > 0)
                    <div style="display: flex; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                        @if(($package->reward_points ?? 0) > 0)
                            <span style="background: rgba(0,255,136,0.1); color:#00ff88; padding:0.3rem 0.75rem; border-radius:999px; font-size:0.8rem; font-weight:600; border:1px solid rgba(0,255,136,0.2);">
                                <i class="fas fa-star"></i> {{ __('home.reward_points', ['count' => $package->reward_points]) }}
                            </span>
                        @endif
                        @if((int) $package->stock > 0)
                            <span style="background: rgba(74,200,246,0.1); color:#4ac8f6; padding:0.3rem 0.75rem; border-radius:999px; font-size:0.8rem; font-weight:600; border:1px solid rgba(74,200,246,0.2);">
                                <i class="fas fa-check-circle"></i> {{ __('shop.in_stock', ['count' => $package->stock]) }}
                            </span>
                        @endif
                    </div>
                    @endif

                    @if($package->local_description)
                        <p style="color: rgba(255,255,255,0.6); margin: 0 0 1.5rem; line-height: 1.7; font-size: 0.95rem; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.25rem;">
                            {{ $package->local_description }}
                        </p>
                    @endif

                    @if($package->items->isNotEmpty())
                        <div style="margin-bottom: 1.75rem;">
                            <h3 style="color: rgba(255,255,255,0.9); margin-bottom: 0.85rem; font-size: 0.95rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-box" style="color: #4ac8f6; font-size: 0.85rem;"></i>
                                {{ __('shop.package_includes') }}
                            </h3>
                            <ul class="package-includes-list">
                                @foreach($package->items as $included)
                                    <li class="package-includes-item">
                                        @if($included->image)
                                            <img src="{{ asset('storage/'.$included->image) }}" alt="" style="width: 44px; height: 44px; border-radius: 10px; object-fit: cover; flex-shrink: 0;">
                                        @else
                                            <div style="width:44px;height:44px;border-radius:10px;background:rgba(74,200,246,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-box" style="color:#4ac8f6;font-size:1.1rem;"></i></div>
                                        @endif
                                        <div style="min-width:0;">
                                            <a href="{{ route('menu.show', $included) }}" style="color: #4ac8f6; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $included->local_name }}</a>
                                            <div style="color: rgba(255,255,255,0.45); font-size: 0.78rem; margin-top: 0.15rem;"><i class="fas fa-times" style="font-size:0.65rem;"></i> {{ $included->pivot->quantity }}</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if((int) $package->stock > 0)
                        <div style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.5rem;">
                            <form action="{{ route('cart.add-package') }}" method="POST" id="package-show-form">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $package->id }}">
                                <input type="hidden" name="country_code" value="{{ $selectedCountry }}" id="package-show-country">
                                <div class="package-cta-row">
                                    <div style="display:flex; align-items:center; gap:0.5rem; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:0.5rem 0.75rem;">
                                        <label style="color:rgba(255,255,255,0.5); font-size:0.8rem; font-weight:600; white-space:nowrap;">{{ app()->getLocale() === 'ar' ? 'الكمية' : 'Qty' }}</label>
                                        <input type="number" name="quantity" value="1" min="1" max="{{ max(1, $package->stock) }}" style="width:60px; background:transparent; border:none; color:#fff; font-size:1rem; font-weight:700; outline:none; text-align:center;">
                                    </div>
                                    <button type="submit" class="elx-btn elx-btn--primary" style="flex:1; min-width:160px; justify-content:center;" onclick="addToCartAjax(this, event)">
                                        <i class="fas fa-shopping-cart"></i> {{ __('home.add_to_cart') }}
                                    </button>
                                    <button type="submit" name="buy_now" value="1" class="elx-btn elx-btn--glass" style="flex:1; min-width:160px; justify-content:center;">
                                        <i class="fas fa-bolt"></i> {{ __('home.buy_now') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.25rem;">
                            <p style="color:#ff8a8a; font-weight:600; margin:0;"><i class="fas fa-exclamation-circle"></i> {{ __('shop.package_out_of_stock') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if($package->local_long_description)
        <section class="elx-section" style="background: var(--elx-darker); padding: 60px 0;">
            <div class="elx-container">
                <div data-animate style="max-width: 860px; margin: 0 auto; padding: 2.5rem; background: var(--elx-glass); border: 1px solid var(--elx-border); border-radius: var(--elx-radius-sm); color: rgba(255,255,255,0.8); line-height: 1.8; font-size: 1rem;">
                    {!! nl2br(e($package->local_long_description)) !!}
                </div>
            </div>
        </section>
    @endif

    <div class="elx-container" style="padding-bottom: 4rem;">
        @include('partials.tag-related-sections', [
            'relatedBlogs' => $relatedBlogs ?? collect(),
            'relatedReviews' => $relatedReviews ?? collect(),
            'relatedPackages' => $relatedPackages ?? collect(),
        ])
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.elx-product-pricing button[name="country"]').forEach((btn) => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        const country = this.value;
        const input = document.getElementById('package-show-country');
        if (input) {
            input.value = country;
        }
        const url = new URL(window.location.href);
        url.searchParams.set('country', country);
        window.location.href = url.toString();
    });
});
</script>
@endsection
