@extends('layouts.framer')

@section('title', $package->local_name)

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
            <div>
                @if($package->image)
                    <img src="{{ asset('storage/'.$package->image) }}" alt="{{ $package->local_name }}" style="width: 100%; border-radius: 24px; border: 1px solid rgba(255,255,255,0.08);">
                @endif
            </div>
            <div>
                <span style="color: #ffd700; font-weight: 700; text-transform: uppercase; font-size: 0.8rem;">{{ __('shop.package_badge') }}</span>
                <h1 class="elx-hero__title" style="text-align: left; margin: 0.5rem 0 1rem;">
                    <span class="elx-hero__title-gradient">{{ $package->local_name }}</span>
                </h1>
                <x-package-pricing :package="$package" :selected-country="$selectedCountry" align="flex-start" size="2rem" smallSize="1rem" />
                <p style="color: var(--elx-gray); margin: 1.5rem 0 2rem; line-height: 1.6;">{{ $package->local_description }}</p>

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

                <div style="margin-top: 2rem;">
                    <form action="{{ route('cart.add-package') }}" method="POST" style="display:flex; gap:1rem; flex-wrap:wrap;">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                        <input type="hidden" name="country_code" value="{{ $selectedCountry }}">
                        <input type="number" name="quantity" value="1" min="1" max="{{ max(1, $package->stock) }}" class="form-input" style="width:90px; margin:0;">
                        <button type="submit" class="elx-btn elx-btn--primary" style="flex:1; min-width:180px; justify-content:center;">
                            <i class="fas fa-shopping-cart"></i> {{ __('home.add_to_cart') }}
                        </button>
                        <button type="submit" name="buy_now" value="1" class="elx-btn elx-btn--glass" style="flex:1; min-width:180px; justify-content:center;">
                            <i class="fas fa-bolt"></i> {{ __('home.buy_now') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if($package->long_description)
            <div style="margin-top: 4rem; color: rgba(255,255,255,0.8); line-height: 1.7;">
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
