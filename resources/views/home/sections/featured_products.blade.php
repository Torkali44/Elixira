<section class="elx-section section-padding" data-elx-animate>
    <div class="container">
        @if($featuredItems->isEmpty())
            <p class="text-center text-muted mb-0">Featured products will appear here when your admin marks items as featured.</p>
        @else
            <div class="features-grid">
                @foreach($featuredItems as $item)
                    <div class="feature-card">
                        <div class="card-image">
                            <a href="{{ route('menu.show', $item) }}">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=400&q=80" alt="{{ $item->name }}">
                                @endif
                            </a>
                        </div>
                        <div class="card-content">
                            <a href="{{ route('menu.show', $item) }}"><h3>{{ $item->name }}</h3></a>
                            <p>{{ Str::limit($item->description, 90) }}</p>
                            <span class="price">${{ number_format($item->price, 2) }}</span>
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-sm w-100" style="background-color: var(--secondary-color); color: var(--nav-bg); font-weight: 600; border: none; padding: 12px;">Add to cart</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        @if($section->button_label && $section->button_url)
            <div class="elx-featured-cta mt-4">
                <a href="{{ \Illuminate\Support\Str::startsWith($section->button_url, ['http://', 'https://']) ? $section->button_url : url($section->button_url) }}" class="btn btn-outline-primary rounded-pill px-4">
                    {{ $section->button_label }}
                </a>
            </div>
        @endif
    </div>
</section>
