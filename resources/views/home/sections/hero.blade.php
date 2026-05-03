@php
    $style = '';
    if ($section->image) {
        $style = "background-image: url('" . e(asset('storage/' . $section->image)) . "');";
    }
@endphp
<section class="elx-section elx-hero {{ $section->image ? 'has-image' : '' }}" style="{{ $style }}" data-elx-animate>
    <div class="container py-5">
        @if($section->title)
            <h1>{{ $section->title }}</h1>
        @endif
        @if($section->subtitle)
            <p>{{ $section->subtitle }}</p>
        @endif
        @if($section->button_label && $section->button_url)
            <a href="{{ \Illuminate\Support\Str::startsWith($section->button_url, ['http://', 'https://']) ? $section->button_url : url($section->button_url) }}" class="btn btn-lg rounded-pill px-5" style="background: var(--secondary-color); color: #000; font-weight: 600; border: none;">
                {{ $section->button_label }}
            </a>
        @endif
    </div>
</section>
