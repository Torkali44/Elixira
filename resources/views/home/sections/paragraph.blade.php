<section class="elx-section elx-paragraph" data-elx-animate>
    @if($section->title)
        <h2>{{ $section->title }}</h2>
    @endif
    @if($section->subtitle)
        <p class="lead text-muted">{{ $section->subtitle }}</p>
    @endif
    @if($section->body)
        <div class="body-copy">{!! nl2br(e($section->body)) !!}</div>
    @endif
</section>
