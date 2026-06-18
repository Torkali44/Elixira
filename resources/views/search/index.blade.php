@extends('layouts.framer')

@section('title', __('shop.search_title'))

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title"><span class="elx-hero__title-gradient">{{ __('shop.search_title') }}</span></h1>
            <form action="{{ route('search.index') }}" method="GET" style="max-width: 640px; margin: 2rem auto 0; display: flex; gap: 0.75rem;">
                <input type="search" name="q" value="{{ $query }}" placeholder="{{ __('shop.search_placeholder') }}" class="form-input" style="flex: 1; margin: 0;">
                <button type="submit" class="elx-btn elx-btn--primary">{{ __('shop.search_button') }}</button>
            </form>
        </div>

        @if($query === '')
            <p style="text-align: center; color: var(--elx-gray);">{{ __('shop.search_hint') }}</p>
        @else
            @if($items->isNotEmpty())
                <section style="margin-top: 3rem;">
                    <h2 style="color: #fff; margin-bottom: 1.5rem;">{{ __('shop.search_products') }}</h2>
                    <div class="elx-products__grid menu-products-grid">
                        @foreach($items as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                </section>
            @endif

            @if($blogs->isNotEmpty())
                <section style="margin-top: 3rem;">
                    <h2 style="color: #fff; margin-bottom: 1.5rem;">{{ __('shop.search_blogs') }}</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
                        @foreach($blogs as $blog)
                            <a href="{{ route('blogs.show', $blog->slug) }}" style="text-decoration: none; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; overflow: hidden;">
                                @if($blog->image)
                                    <img src="{{ asset('storage/'.$blog->image) }}" alt="{{ $blog->title }}" style="width: 100%; height: 160px; object-fit: cover;">
                                @endif
                                <div style="padding: 1.1rem 1.2rem 1.3rem;">
                                    <h3 style="color: #fff; font-size: 1.05rem; margin: 0;">{{ $blog->title }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($faqs->isNotEmpty())
                <section style="margin-top: 3rem;">
                    <h2 style="color: #fff; margin-bottom: 1.5rem;">{{ __('shop.search_faqs') }}</h2>
                    <div style="display: grid; gap: 1rem;">
                        @foreach($faqs as $faq)
                            <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 1.2rem 1.4rem;">
                                <h3 style="color: #4ac8f6; font-size: 1rem; margin: 0 0 0.5rem;">{{ $faq->question }}</h3>
                                <p style="color: rgba(255,255,255,0.75); margin: 0;">{{ Str::limit($faq->answer, 220) }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($items->isEmpty() && $blogs->isEmpty() && $faqs->isEmpty())
                <p style="text-align: center; color: var(--elx-gray); margin-top: 3rem;">{{ __('shop.search_no_results', ['query' => $query]) }}</p>
            @endif
        @endif
    </div>
</div>
@endsection
