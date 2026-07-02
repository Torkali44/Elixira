@php
    $galleryId = $galleryId ?? 'product-gallery';
    $images = collect($images ?? [])->filter(fn ($img) => ! empty($img['url']))->values();
    $hasMultiple = $images->count() > 1;
@endphp

@if($images->isNotEmpty())
<div class="elx-image-gallery" id="{{ $galleryId }}" data-gallery>
    <div class="elx-image-gallery__main">
        @if($hasMultiple)
            <button type="button" class="elx-image-gallery__nav elx-image-gallery__nav--prev" aria-label="{{ __('shop.gallery_prev') }}" data-gallery-prev>
                <i class="fas fa-chevron-left"></i>
            </button>
        @endif

        <div class="elx-image-gallery__stage">
            <img src="{{ $images->first()['url'] }}" alt="{{ $alt ?? '' }}" id="{{ $galleryId }}-main" data-gallery-main width="800" height="800" fetchpriority="high" decoding="async">
        </div>

        @if($hasMultiple)
            <button type="button" class="elx-image-gallery__nav elx-image-gallery__nav--next" aria-label="{{ __('shop.gallery_next') }}" data-gallery-next>
                <i class="fas fa-chevron-right"></i>
            </button>
        @endif
    </div>

    @if($hasMultiple)
        <div class="elx-image-gallery__thumbs-wrap">
            <button type="button" class="elx-image-gallery__thumb-nav elx-image-gallery__thumb-nav--prev" aria-label="{{ __('shop.gallery_prev') }}" data-thumb-prev>
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="elx-image-gallery__thumbs-viewport" data-thumb-viewport>
                <div class="elx-image-gallery__thumbs-track" data-thumb-track>
                    @foreach($images as $index => $image)
                        <button type="button"
                            class="elx-image-gallery__thumb {{ $index === 0 ? 'is-active' : '' }}"
                            data-gallery-index="{{ $index }}"
                            data-gallery-src="{{ $image['url'] }}"
                            aria-label="{{ __('shop.gallery_image', ['number' => $index + 1]) }}">
                            <img src="{{ $image['url'] }}" alt="" width="72" height="72" loading="lazy" decoding="async">
                        </button>
                    @endforeach
                </div>
            </div>

            <button type="button" class="elx-image-gallery__thumb-nav elx-image-gallery__thumb-nav--next" aria-label="{{ __('shop.gallery_next') }}" data-thumb-next>
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    @endif
</div>
@endif
