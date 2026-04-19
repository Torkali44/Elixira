@php
    $cards = [];
    if ($section->body) {
        try {
            $decoded = json_decode($section->body, true, 512, JSON_THROW_ON_ERROR);
            $cards = is_array($decoded) ? $decoded : [];
        } catch (\Throwable $e) {
            $cards = [];
        }
    }
@endphp
<section class="elx-section elx-icon-cards" data-elx-animate>
    <div class="container">
        @if($section->title)
            <h2 class="text-center mb-5">{{ $section->title }}</h2>
        @endif
        <div class="row g-4">
            @forelse($cards as $card)
                <div class="col-md-4">
                    <div class="elx-glass-card h-100">
                        <div class="icon-round">
                            <i class="fa-solid {{ $card['icon'] ?? 'fa-spa' }}"></i>
                        </div>
                        @if(!empty($card['title']))
                            <h3 class="h5">{{ $card['title'] }}</h3>
                        @endif
                        @if(!empty($card['text']))
                            <p class="text-muted mb-0">{{ $card['text'] }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">Add JSON cards in the admin body field for this section.</p>
            @endforelse
        </div>
    </div>
</section>
