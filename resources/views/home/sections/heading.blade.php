<section class="elx-section elx-heading-block" data-elx-animate>
    @if($section->title)
        <h2>{{ $section->title }}</h2>
    @endif
    @if($section->subtitle)
        <p class="lead text-muted mb-0">{{ $section->subtitle }}</p>
    @endif
</section>
