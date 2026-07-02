@if(isset($faqs) && $faqs->count() > 0)
<section class="elx-section" style="margin-top: 4rem;">
    <div class="elx-section__header" data-animate>
        <h2 class="elx-section__title">{{ __('shop.faqs_title') }}</h2>
    </div>

    <div class="elx-faq-accordions" data-animate>
        @foreach($faqs as $faq)
            <div class="elx-detail-accordion">
                <button type="button" class="elx-detail-accordion__trigger" aria-expanded="false">
                    <span class="elx-detail-accordion__title">{{ $faq->question }}</span>
                    <i class="fas fa-chevron-down elx-detail-accordion__icon" aria-hidden="true"></i>
                </button>
                <div class="elx-detail-accordion__panel" hidden>
                    <div class="elx-detail-accordion__content">{!! nl2br(e($faq->answer)) !!}</div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
