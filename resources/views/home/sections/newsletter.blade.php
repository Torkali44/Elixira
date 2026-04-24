<section class="elx-section elx-newsletter" data-elx-animate>
    @if($section->title)
        <h2>{{ $section->title }}</h2>
    @endif
    @if($section->subtitle)
        <p>{{ $section->subtitle }}</p>
    @endif
    @if($section->button_label)
        @php
            $href = $section->button_url && $section->button_url !== '#'
                ? (\Illuminate\Support\Str::startsWith($section->button_url, ['http://', 'https://']) ? $section->button_url : url($section->button_url))
                : route('contact');
        @endphp
        <a href="{{ $href }}" class="btn btn-lg rounded-pill px-5" style="background: var(--secondary-color); color: #000; font-weight: 700; border: none;">
            {{ $section->button_label }}
        </a>
    @endif
</section>
