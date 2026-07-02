@extends('layouts.framer')

@section('title', __('faqs_page.page_title'))

@section('content')
<div class="page-content" style="padding-top: 0;">
    <section style="background: linear-gradient(180deg, #13252d 0%, #000000 100%); padding: 120px 0 60px;">
        <div class="elx-container">
            <div class="elx-section__header" data-animate>
                <h1 class="elx-hero__title" style="margin-bottom: 1.5rem;">
                    <span class="elx-hero__title-gradient">{{ __('faqs_page.hero_title') }}</span>
                </h1>
                <p class="elx-hero__subtitle">{{ __('faqs_page.hero_subtitle') }}</p>
            </div>
        </div>
    </section>

    <section class="elx-section" style="background: var(--elx-darker); padding: 60px 0 100px;">
        <div class="elx-container">
            @if(($faqs ?? collect())->isEmpty())
                <div style="text-align: center; padding: 4rem 2rem; color: rgba(255,255,255,0.4);">
                    <i class="fas fa-question-circle" style="font-size: 3rem; margin-bottom: 1.5rem; opacity: 0.3;"></i>
                    <p>{{ __('faqs_page.empty') }}</p>
                </div>
            @else
                @include('partials.faq-accordions', ['faqs' => $faqs])
            @endif
        </div>
    </section>
</div>
@endsection
