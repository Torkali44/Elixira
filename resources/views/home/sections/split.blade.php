<section class="elx-section elx-split" data-elx-animate>
    <div class="container elx-split-inner">
        <div>
            @if($section->title)
                <h2>{{ $section->title }}</h2>
            @endif
            @if($section->subtitle)
                <p class="lead text-muted">{{ $section->subtitle }}</p>
            @endif
            @if($section->body)
                <div class="body-copy">{!! nl2br(e($section->body)) !!}</div>
            @endif
            @if($section->button_label && $section->button_url)
                <a href="{{ \Illuminate\Support\Str::startsWith($section->button_url, ['http://', 'https://']) ? $section->button_url : url($section->button_url) }}" class="btn rounded-pill px-4 mt-3" style="background: var(--primary-color); color: var(--white); font-weight: 600;">
                    {{ $section->button_label }}
                </a>
            @endif
        </div>
        <div class="elx-glass-card p-0 overflow-hidden">
            @if($section->image)
                <img src="{{ asset('storage/' . $section->image) }}" alt="" class="w-100 rounded-3" style="border-radius: 20px; max-height: 420px; object-fit: cover;">
            @else
                <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="min-height: 320px;">
                    <span><i class="fa-solid fa-image me-2"></i>Add an image in admin</span>
                </div>
            @endif
        </div>
    </div>
</section>
