@extends('layouts.framer')

@section('title', __('about.page_title'))

@section('content')
<div class="page-content" style="padding-top: 0;">
    <section style="background: linear-gradient(180deg, #13252d 0%, #000000 100%); padding: 120px 0 80px;">
        <div class="elx-container">
            <div class="elx-section__header" data-animate>
                <h1 class="elx-hero__title" style="margin-bottom: 1.5rem;">
                    <span class="elx-hero__title-gradient">{{ __('about.hero_title') }}</span>
                </h1>
                <p class="elx-hero__subtitle">{{ __('about.hero_subtitle') }}</p>
            </div>
        </div>
    </section>

    <section class="elx-section" style="background: radial-gradient(circle at top right, rgba(74, 200, 246, 0.05) 0%, transparent 50%), #0d1a21; padding: 100px 0;">
        <div class="elx-container">
            <div class="elx-insights" style="background: transparent; padding: 0;">
                <div class="elx-insights__grid d-flex flex-wrap align-items-center" data-animate style="gap: 4rem;">
                    <div class="elx-insights__text" style="flex: 1; min-width: 320px;">
                        <span class="elx-insights__label" style="color: var(--elx-cyan); letter-spacing: 3px;">{{ __('about.story_label') }}</span>
                        <h2 style="font-size: 3rem; margin: 1.5rem 0; color: white;">{{ __('about.story_title') }}</h2>
                        <p style="opacity: 0.8; line-height: 1.8; font-size: 1.1rem; margin-bottom: 1.5rem;">{{ __('about.story_p1') }}</p>
                        <p style="opacity: 0.75; line-height: 1.8; font-size: 1.1rem; margin-bottom: 2.5rem;">{{ __('about.story_p2') }}</p>
                        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                            <a href="{{ route('menu.index') }}" class="elx-btn elx-btn--primary">{{ __('about.shop_collection') }}</a>
                            <a href="{{ route('dxn-team.create') }}" class="elx-btn elx-btn--glass">{{ __('about.join_dxn') }}</a>
                        </div>
                    </div>
                    <div style="flex: 1; min-width: 320px; border-radius: 40px; overflow: hidden; height: 500px; box-shadow: 0 30px 60px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.05);">
                        <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=1000&q=80"
                             alt="Natural Ingredients" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="elx-section" style="background: linear-gradient(180deg, #0d1a21 0%, #13252d 100%); padding: 120px 0;">
        <div class="elx-container">
            <div class="elx-section__header" data-animate style="text-align: center; margin-bottom: 5rem;">
                <h2 class="elx-section__title">{{ __('about.values_title') }}</h2>
            </div>

            <div class="elx-insights__stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;" data-animate>
                <div class="elx-stat-card" style="padding: 3rem 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(74, 200, 246, 0.1);">
                    <div style="margin-bottom: 2rem;">
                        <i class="fas fa-leaf" style="font-size: 3rem; color: #4ac8f6; background: linear-gradient(135deg, #4ac8f6, #000); -webkit-background-clip: text; color: transparent;"></i>
                    </div>
                    <h3 style="font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: 1rem;">{{ __('about.value_natural_title') }}</h3>
                    <p style="color: rgba(255,255,255,0.6); line-height: 1.6;">{{ __('about.value_natural_desc') }}</p>
                </div>

                <div class="elx-stat-card" style="padding: 3rem 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(74, 200, 246, 0.1);">
                    <div style="margin-bottom: 2rem;">
                        <i class="fas fa-vial" style="font-size: 3rem; color: #4ac8f6; background: linear-gradient(135deg, #4ac8f6, #000); -webkit-background-clip: text; color: transparent;"></i>
                    </div>
                    <h3 style="font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: 1rem;">{{ __('about.value_potent_title') }}</h3>
                    <p style="color: rgba(255,255,255,0.6); line-height: 1.6;">{{ __('about.value_potent_desc') }}</p>
                </div>

                <div class="elx-stat-card" style="padding: 3rem 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(74, 200, 246, 0.1);">
                    <div style="margin-bottom: 2rem;">
                        <i class="fas fa-hand-sparkles" style="font-size: 3rem; color: #4ac8f6; background: linear-gradient(135deg, #4ac8f6, #000); -webkit-background-clip: text; color: transparent;"></i>
                    </div>
                    <h3 style="font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: 1rem;">{{ __('about.value_luxury_title') }}</h3>
                    <p style="color: rgba(255,255,255,0.6); line-height: 1.6;">{{ __('about.value_luxury_desc') }}</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
