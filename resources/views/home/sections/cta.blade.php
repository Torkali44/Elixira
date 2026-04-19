<section class="elx-section elx-cta-band" data-elx-animate>
    @if($section->title)
        <h2>{{ $section->title }}</h2>
    @endif
    @if($section->subtitle)
        <p class="text-muted">{{ $section->subtitle }}</p>
    @endif
    @if($section->button_label && $section->button_url)
        <a href="{{ \Illuminate\Support\Str::startsWith($section->button_url, ['http://', 'https://']) ? $section->button_url : url($section->button_url) }}" class="btn btn-lg rounded-pill px-5 mt-2" style="background: var(--secondary-color); color: #000; font-weight: 600; border: none;">
            {{ $section->button_label }}
        </a>
    @endif
</section>
