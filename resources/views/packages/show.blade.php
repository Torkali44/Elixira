@extends('layouts.framer')

@section('title', $package->local_name)

@section('head')
<style>
    .package-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: start;
    }
    .package-gallery img {
        width: 100%;
        border-radius: 24px;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .package-gallery-placeholder {
        aspect-ratio: 1/1;
        border-radius: 24px;
        border: 1px solid var(--elx-border);
        background: var(--elx-glass);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--elx-cyan);
        font-size: 4rem;
    }
    @media (max-width: 991px) {
        .package-detail-grid { grid-template-columns: 1fr; gap: 2rem; }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div class="package-detail-grid">
            <div class="package-gallery" data-animate>
                @if($package->image)
                    <img src="{{ asset('storage/'.$package->image) }}" alt="{{ $package->local_name }}">
                @else
                    <div class="package-gallery-placeholder"><i class="fas fa-box-open"></i></div>
                @endif
            </div>

            <div data-animate>
                <span style="color: #ffd700; font-weight: 700; text-transform: uppercase; font-size: 0.8rem;">{{ __('shop.package_badge') }}</span>
                <h1 class="elx-hero__title" style="text-align: left; margin: 0.5rem 0 1rem;">
                    <span class="elx-hero__title-gradient">{{ $package->local_name }}</span>
                </h1>
                <x-package-pricing :package="$package" :selected-country="$selectedCountry" align="flex-start" size="2rem" smallSize="1rem" />
                <p style="color: var(--elx-gray); margin: 1.5rem 0 2rem; line-height: 1.6;">{{ $package->local_description }}</p>

                @if(($package->reward_points ?? 0) > 0)
                    <div style="margin-bottom: 1.5rem;">
                        <span style="background: rgba(0,255,136,0.1); color:#00ff88; padding:0.35rem 0.85rem; border-radius:999px; font-size:0.85rem; font-weight:600; border:1px solid rgba(0,255,136,0.2);">
                            <i class="fas fa-star"></i> {{ __('home.reward_points', ['count' => $package->reward_points]) }}
                        </span>
                    </div>
                @endif

                @if($package->items->isNotEmpty())
                    <h3 style="color: #fff; margin-bottom: 1rem;">{{ __('shop.package_includes') }}</h3>
                    <ul style="list-style: none; padding: 0; margin: 0 0 2rem; display: grid; gap: 0.75rem;">
                        @foreach($package->items as $included)
                            <li style="display:flex; align-items:center; gap:0.75rem; background: rgba(255,255,255,0.04); border-radius: 12px; padding: 0.75rem 1rem;">
                                @if($included->image)
                                    <img src="{{ asset('storage/'.$included->image) }}" alt="" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover;">
                                @endif
                                <div>
                                    <a href="{{ route('menu.show', $included) }}" style="color: #4ac8f6; text-decoration: none; font-weight: 600;">{{ $included->local_name }}</a>
                                    <div style="color: rgba(255,255,255,0.55); font-size: 0.85rem;">x{{ $included->pivot->quantity }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if((int) $package->stock > 0)
                    <form action="{{ route('cart.add-package') }}" method="POST" id="package-show-form" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:center;">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                        <input type="hidden" name="country_code" value="{{ $selectedCountry }}" id="package-show-country">
                        <input type="number" name="quantity" value="1" min="1" max="{{ max(1, $package->stock) }}" class="form-input" style="width:90px; margin:0;">
                        <button type="submit" class="elx-btn elx-btn--primary" style="flex:1; min-width:180px; justify-content:center;">
                            <i class="fas fa-shopping-cart"></i> {{ __('home.add_to_cart') }}
                        </button>
                        <button type="submit" name="buy_now" value="1" class="elx-btn elx-btn--glass" style="flex:1; min-width:180px; justify-content:center;">
                            <i class="fas fa-bolt"></i> {{ __('home.buy_now') }}
                        </button>
                    </form>
                @else
                    <p style="color:#ff8a8a;">{{ __('shop.package_out_of_stock') }}</p>
                @endif
            </div>
        </div>

        @if($package->long_description)
            <div style="margin-top: 4rem; padding: 2rem; background: var(--elx-glass); border: 1px solid var(--elx-border); border-radius: var(--elx-radius-sm); color: rgba(255,255,255,0.8); line-height: 1.7;">
                {!! nl2br(e($package->long_description)) !!}
            </div>
        @endif

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
