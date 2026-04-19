@extends('layouts.framer')

@section('title', 'Our Philosophy - Elixira')

@section('content')
<div class="page-content" style="padding-top: 0;">
    {{-- Section 1: Philosophy (Full Header) --}}
    <section style="background: linear-gradient(180deg, #13252d 0%, #000000 100%); padding: 120px 0 80px;">
        <div class="elx-container">
            <div class="elx-section__header" data-animate>
                <h1 class="elx-hero__title" style="margin-bottom: 1.5rem;">
                    <span class="elx-hero__title-gradient">Our Philosophy</span>
                </h1>
                <p class="elx-hero__subtitle">Clean, potent, and beautifully crafted formulas</p>
            </div>
        </div>
    </section>

    {{-- Section 2: Story (Different Texture/Background) --}}
    <section class="elx-section" style="background: radial-gradient(circle at top right, rgba(74, 200, 246, 0.05) 0%, transparent 50%), #0d1a21; padding: 100px 0;">
        <div class="elx-container">
            <div class="elx-insights" style="background: transparent; padding: 0;">
                <div class="elx-insights__grid d-flex flex-wrap align-items-center" data-animate style="gap: 4rem;">
                    <div class="elx-insights__text" style="flex: 1; min-width: 320px;">
                        <span class="elx-insights__label" style="color: var(--elx-cyan); letter-spacing: 3px;">OUR STORY</span>
                        <h2 style="font-size: 3rem; margin: 1.5rem 0; color: white;">Rooted in Nature</h2>
                        <p style="opacity: 0.8; line-height: 1.8; font-size: 1.1rem; margin-bottom: 1.5rem;">At Elixira, we believe that true beauty and wellbeing stem from nature. We feature products crafted from pure natural ingredients, with a special focus on superfoods and body‑care essentials.</p>
                        <p style="opacity: 0.75; line-height: 1.8; font-size: 1.1rem; margin-bottom: 2.5rem;">Each formula is developed with clear ingredient lists and honest claims—so you always know what touches your skin. Whether you are building a minimal routine or layering serums and SPF, Elixira is here to help you shop with confidence.</p>
                        <a href="{{ route('menu.index') }}" class="elx-btn elx-btn--primary">Shop the Collection</a>
                    </div>
                    <div style="flex: 1; min-width: 320px; border-radius: 40px; overflow: hidden; height: 500px; box-shadow: 0 30px 60px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.05);">
                        <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=1000&q=80" 
                             alt="Natural Ingredients" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 3: Values (Gradient Variation) --}}
    <section class="elx-section" style="background: linear-gradient(180deg, #0d1a21 0%, #13252d 100%); padding: 120px 0;">
        <div class="elx-container">
            <div class="elx-section__header" data-animate style="text-align: center; margin-bottom: 5rem;">
                <h2 class="elx-section__title">Our Core Values</h2>
            </div>
            
            <div class="elx-insights__stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;" data-animate>
                <div class="elx-stat-card" style="padding: 3rem 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(74, 200, 246, 0.1);">
                    <div style="margin-bottom: 2rem;">
                        <i class="fas fa-leaf" style="font-size: 3rem; color: #4ac8f6; background: linear-gradient(135deg, #4ac8f6, #000); -webkit-background-clip: text; color: transparent;"></i>
                    </div>
                    <h3 style="font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: 1rem;">Natural</h3>
                    <p style="color: rgba(255,255,255,0.6); line-height: 1.6;">Pure ingredients respecting your skin and the environment. No hidden fillers, just pure performance.</p>
                </div>
                
                <div class="elx-stat-card" style="padding: 3rem 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(74, 200, 246, 0.1);">
                    <div style="margin-bottom: 2rem;">
                        <i class="fas fa-vial" style="font-size: 3rem; color: #4ac8f6; background: linear-gradient(135deg, #4ac8f6, #000); -webkit-background-clip: text; color: transparent;"></i>
                    </div>
                    <h3 style="font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: 1rem;">Potent</h3>
                    <p style="color: rgba(255,255,255,0.6); line-height: 1.6;">A meticulous blend of superfoods and science to restore glow and optimize vitality from within.</p>
                </div>
                
                <div class="elx-stat-card" style="padding: 3rem 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(74, 200, 246, 0.1);">
                    <div style="margin-bottom: 2rem;">
                        <i class="fas fa-hand-sparkles" style="font-size: 3rem; color: #4ac8f6; background: linear-gradient(135deg, #4ac8f6, #000); -webkit-background-clip: text; color: transparent;"></i>
                    </div>
                    <h3 style="font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: 1rem;">Luxury</h3>
                    <p style="color: rgba(255,255,255,0.6); line-height: 1.6;">Transforming daily routines into luxurious self-care rituals that celebrate your unique beauty.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
