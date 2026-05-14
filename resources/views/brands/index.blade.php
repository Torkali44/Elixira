@extends('layouts.framer')

@section('title', 'Brands - Elixira')

@section('head')
<style>
    .brands-filter {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 3rem;
        flex-wrap: wrap;
    }
    .brands-filter a {
        padding: 0.6rem 1.8rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: var(--elx-transition);
        border: 1px solid var(--elx-border);
        background: var(--elx-glass);
        color: var(--elx-white);
    }
    .brands-filter a.active,
    .brands-filter a:hover {
        background: linear-gradient(135deg, var(--elx-cyan), var(--elx-accent));
        color: var(--elx-dark);
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(74, 200, 246, 0.3);
    }
    .brands-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }
    .brand-card {
        padding: 2rem;
        border-radius: 24px;
        background: var(--elx-glass);
        border: 1px solid var(--elx-border);
        text-align: center;
        transition: var(--elx-transition);
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .brand-card:hover {
        border-color: var(--elx-cyan);
        transform: translateY(-6px);
        box-shadow: 0 16px 50px rgba(74, 200, 246, 0.12);
    }
    .brand-card__logo {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        object-fit: cover;
        margin: 0 auto 1.2rem;
        display: block;
        border: 1px solid var(--elx-border);
    }
    .brand-card__name {
        font-weight: 700;
        color: var(--elx-white);
        font-size: 1.1rem;
        margin-bottom: 0.4rem;
    }
    .brand-card__count {
        font-size: 0.82rem;
        color: var(--elx-light);
    }
    .brand-card__countries {
        display: flex;
        gap: 0.3rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 0.75rem;
    }
    .brand-card__country {
        padding: 0.15rem 0.6rem;
        border-radius: 50px;
        font-size: 0.7rem;
        background: rgba(74, 200, 246, 0.1);
        color: var(--elx-cyan);
        border: 1px solid rgba(74, 200, 246, 0.2);
    }
    @media (max-width: 1024px) {
        .brands-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .brands-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">Our Brands</span>
            </h1>
            <p class="elx-hero__subtitle" style="margin-bottom: 0;">Explore trusted vendors and their natural product collections</p>
        </div>

        {{-- Country Filter --}}
        <div class="brands-filter" data-animate>
            <a href="{{ route('brands.index') }}" class="{{ !request('country') ? 'active' : '' }}">All</a>
            <a href="{{ route('brands.index', ['country' => 'UAE']) }}" class="{{ request('country') === 'UAE' ? 'active' : '' }}">UAE</a>
            <a href="{{ route('brands.index', ['country' => 'KSA']) }}" class="{{ request('country') === 'KSA' ? 'active' : '' }}">Saudi Arabia</a>
        </div>

        @if($brands->count() > 0)
            <div class="brands-grid" data-animate>
                @foreach($brands as $brand)
                    <a href="{{ route('brands.show', $brand->slug) }}" class="brand-card">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="brand-card__logo">
                        @else
                            <div style="width: 80px; height: 80px; border-radius: 20px; background: rgba(74,200,246,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem; color: var(--elx-cyan); font-size: 2rem;">
                                <i class="fas fa-store"></i>
                            </div>
                        @endif
                        <div class="brand-card__name">{{ $brand->name }}</div>
                        <div class="brand-card__count">{{ $brand->items_count }} products</div>
                        @if($brand->service_countries)
                        <div class="brand-card__countries">
                            @foreach($brand->service_countries as $country)
                                <span class="brand-card__country">{{ $country }}</span>
                            @endforeach
                        </div>
                        @endif
                    </a>
                @endforeach
            </div>

            <div style="margin-top: 3rem; display: flex; justify-content: center;">
                {{ $brands->withQueryString()->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 4rem 1rem; color: var(--elx-light);" data-animate>
                <i class="fas fa-store-slash" style="font-size: 3rem; color: rgba(255,255,255,0.15); margin-bottom: 1rem; display: block;"></i>
                <p>No brands found matching your filter.</p>
            </div>
        @endif
    </div>
</div>
@endsection
