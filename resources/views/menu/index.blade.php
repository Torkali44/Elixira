@extends('layouts.framer')

@section('title', 'Our Collections - Elixira')

@section('content')
<div class="page-content">
    <div class="elx-container">
        {{-- Section Header --}}
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">Our Collections</span>
            </h1>
            <p class="elx-hero__subtitle" style="margin-bottom: 0;">Discover our pure and natural product ranges</p>
        </div>

        {{-- Category Filter --}}
        <div class="menu-filter text-center mb-5" data-animate style="margin-bottom: 4rem;">
            <button class="filter-btn active" data-filter="all">All</button>
            @foreach($categories as $category)
                <button class="filter-btn" data-filter=".cat-{{ $category->id }}">{{ $category->name }}</button>
            @endforeach
        </div>

        <div class="elx-products__grid" id="products-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2.5rem; margin-bottom: 6rem;">
            @foreach($items as $product)
                <div class="product-item cat-{{ $product->category_id }}" data-animate>
                    @include('partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Simple filter script
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filter = btn.dataset.filter;
            const items = document.querySelectorAll('.product-item');
            
            items.forEach(item => {
                if (filter === 'all' || item.classList.contains(filter.substring(1))) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
<style>
    .filter-btn {
        background: var(--elx-glass);
        border: 1px solid var(--elx-border);
        color: var(--elx-white);
        padding: 0.7rem 2rem;
        margin: 0.5rem;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s;
        font-family: 'Istok Web', sans-serif;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }
    .filter-btn.active, .filter-btn:hover {
        background: linear-gradient(135deg, var(--elx-cyan), var(--elx-accent));
        color: var(--elx-dark);
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(74, 200, 246, 0.3);
    }
    .category-section {
        margin-bottom: 6rem !important;
    }
</style>
@endsection
