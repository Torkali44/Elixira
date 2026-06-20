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
            <div style="max-width: 800px; margin: 0 auto;" data-animate>
                @forelse($faqs as $faq)
                    <div class="faq-item" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 20px; margin-bottom: 1.5rem; overflow: hidden; transition: all 0.3s ease;">
                        <button class="faq-trigger" style="width: 100%; padding: 1.5rem; background: none; border: none; color: white; display: flex; justify-content: space-between; align-items: center; text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }}; font-size: 1.15rem; font-weight: 600; cursor: pointer; outline: none; font-family: var(--elx-font);">
                            <span class="faq-question">{{ $faq->question }}</span>
                            <i class="fas fa-chevron-up faq-icon" style="transition: transform 0.3s ease; font-size: 0.9rem; color: var(--elx-cyan);"></i>
                        </button>
                        <div class="faq-content" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; padding: 0 1.5rem;">
                            <div style="padding-bottom: 1.5rem; color: rgba(255,255,255,0.7); line-height: 1.8; font-size: 1rem; font-family: var(--elx-font);">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 4rem 2rem; color: rgba(255,255,255,0.4);">
                        <i class="fas fa-question-circle" style="font-size: 3rem; margin-bottom: 1.5rem; opacity: 0.3;"></i>
                        <p>{{ __('faqs_page.empty') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.faq-trigger').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const item = trigger.closest('.faq-item');
                const content = item.querySelector('.faq-content');
                const icon = trigger.querySelector('.faq-icon');
                const isOpen = item.classList.contains('open');

                document.querySelectorAll('.faq-item.open').forEach(openItem => {
                    if (openItem !== item) {
                        openItem.classList.remove('open');
                        openItem.querySelector('.faq-content').style.maxHeight = null;
                        openItem.querySelector('.faq-icon').style.transform = null;
                        openItem.style.background = 'rgba(255, 255, 255, 0.03)';
                        openItem.style.borderColor = 'rgba(255,255,255,0.06)';
                    }
                });

                if (isOpen) {
                    item.classList.remove('open');
                    content.style.maxHeight = null;
                    icon.style.transform = null;
                    item.style.background = 'rgba(255, 255, 255, 0.03)';
                    item.style.borderColor = 'rgba(255,255,255,0.06)';
                } else {
                    item.classList.add('open');
                    content.style.maxHeight = content.scrollHeight + 'px';
                    icon.style.transform = 'rotate(180deg)';
                    item.style.background = 'rgba(74, 200, 246, 0.05)';
                    item.style.borderColor = 'rgba(74, 200, 246, 0.2)';
                }
            });
        });
    });
</script>
@endsection
