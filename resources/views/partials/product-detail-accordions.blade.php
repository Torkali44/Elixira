@php
    $sections = $model->localizedDetailSections();
@endphp

@if(count($sections) > 0)
<div class="elx-detail-accordions" data-animate>
    @foreach($sections as $index => $section)
        <div class="elx-detail-accordion {{ $index === 0 ? 'is-open' : '' }}">
            <button type="button" class="elx-detail-accordion__trigger" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="detail-section-{{ $section['id'] }}">
                <span class="elx-detail-accordion__title">{{ $section['title'] }}</span>
                <i class="fas fa-chevron-down elx-detail-accordion__icon" aria-hidden="true"></i>
            </button>
            <div id="detail-section-{{ $section['id'] }}" class="elx-detail-accordion__panel" @if($index !== 0) hidden @endif>
                <div class="elx-detail-accordion__content">{!! nl2br(e($section['content'])) !!}</div>
            </div>
        </div>
    @endforeach
</div>
@endif
