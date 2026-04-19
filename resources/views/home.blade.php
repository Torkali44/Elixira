@extends('layouts.framer')

@section('title', 'Elixira — Superfoods, Science & Self-Care')

@section('nav-class', '') {{-- في الهوم لا نريد خلفية سوداء فورية --}}

@section('content')
   
    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- HERO SECTION --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <section class="elx-hero" id="hero"
        style="position: relative; overflow: hidden; height: 100vh; display: flex; align-items: center; justify-content: center;">
        {{-- Main Background Image --}}
        <div class="elx-hero__bg"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -2; background-image: url('https://framerusercontent.com/images/8dBa4covRirMxiSicaHoAjMUTLw.jpeg?width=4000&height=1715'); background-size: cover; background-position: center;">
        </div>

        {{-- Vivid Overlays --}}
        <div class="elx-hero__overlay-vivid"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background: linear-gradient(180deg, rgba(19, 37, 45, 0.4) 0%, rgba(19, 37, 45, 0.9) 100%);">
        </div>
        <div class="elx-hero__overlay-blend"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background: #13252d; mix-blend-mode: overlay; opacity: 0.25;">
        </div>
        <div class="elx-hero__overlay-shadow"
            style="position: absolute; bottom: 0; left: 0; width: 100%; height: 15vh; z-index: -1; background: linear-gradient(to top, #13252d 0%, transparent 100%);">
        </div>

        <div class="elx-hero__content" data-animate style="text-align: center; max-width: 900px; padding: 2rem;">
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient"
                    style="background-image: linear-gradient(0deg, #4ac8f6 0%, #ffddbd 100%); -webkit-background-clip: text; color: transparent;">Welcome
                    to Elixira</span>
            </h1>
            <p class="elx-hero__subtitle"
                style="color: rgba(255,255,255,0.76); font-size: 1.25rem; line-height: 1.6; margin: 1.5rem 0 2rem;">
                A blend of<br>superfoods, science, and self‑care rituals that restores your glow,<br>balance your energy,
                and elevate your daily wellbeing…
            </p>
            <div class="elx-hero__actions" style="display: flex; gap: 1rem; justify-content: center;">
                <a href="{{ route('menu.index') }}" class="elx-btn elx-btn--primary">Enter Store</a>
                <a href="{{ route('cart.index') }}" class="elx-btn elx-btn--glass">Go Cart</a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- CATEGORIES SECTION --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <section class="elx-section elx-categories" id="categories" style="padding: 4rem 0;">
        <div class="elx-container">
            @php
                $categories = \App\Models\Category::withCount('items')->get();
            @endphp
            @if($categories->count() > 0)
                <div class="elx-categories__grid" data-animate
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    @foreach($categories as $category)
                        <a href="{{ route('menu.index') }}?category={{ $category->id }}" class="elx-category-pill">
                            <div class="elx-category-pill__img">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                                @else
                                    <div class="elx-category-pill__placeholder">
                                        <i class="fas fa-leaf"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="elx-category-pill__info">
                                <h3>{{ $category->name }}</h3>
                                <span>{{ $category->items_count }} products</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
    <div class="framer-1ij53gd" data-framer-appear-id="1ij53gd" data-framer-name="LogoString"
        style="opacity: 1; transform: none; will-change: transform">
        <div class="framer-8k1tyh" style="
                      overflow-x: clip;
                      display: flex;
                      position: absolute;
                      transform: translate(-50%, -50%);
                    ">
            <ul role="group" style="
                        display: flex;
                        position: relative;
                        list-style-type: none;
                        padding: 0px;
                        margin: 0px;
                        justify-content: flex-start;
                        flex-direction: row;
                        gap: 158px;
                        opacity: 0;
                        align-items: center;
                        width: 100%;
                        height: 100%;
                        max-height: 100%;
                        max-width: 100%;
                        transform: translateX(-158px);
                      ">
                <li class="ticker-item" aria-hidden="false" aria-posinset="1" aria-setsize="3" style="
                          flex-grow: 0;
                          flex-shrink: 0;
                          position: relative;
                          height: fit-content;
                          width: fit-content;
                          transform: none;
                        ">
                    <div class="framer-w6ja4x-container" data-framer-name="Logos" name="Logos">
                        <div name="Logos" class="framer-FB8H6 framer-g2nkge framer-v-1ke3efp" data-framer-name="alattar"
                            style="opacity: 1">
                            <div class="framer-17vdob9" style="opacity: 1">
                                <div class="framer-ou1wwp" data-framer-name="Images" style="opacity: 1">
                                    <div data-framer-background-image-wrapper="true" style="
                                    position: absolute;
                                    border-radius: inherit;
                                    corner-shape: inherit;
                                    inset: 0px;
                                  ">
                                        <img decoding="auto" width="199" height="133"
                                            src="https://framerusercontent.com/images/aXEmjQpai7hP0SiCn0wZkdKhSg.png?width=199&amp;height=133"
                                            alt="" style="
                                      display: block;
                                      width: 100%;
                                      height: 100%;
                                      border-radius: inherit;
                                      corner-shape: inherit;
                                      object-position: center center;
                                      object-fit: cover;
                                    " />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="ticker-item" aria-hidden="false" aria-posinset="2" aria-setsize="3" style="
                          flex-grow: 0;
                          flex-shrink: 0;
                          position: relative;
                          height: fit-content;
                          width: fit-content;
                          transform: none;
                        ">
                    <div class="framer-15cl2m6-container" data-framer-name="Logos" name="Logos">
                        <div name="Logos" class="framer-FB8H6 framer-g2nkge framer-v-1l595a" data-framer-name="dxn"
                            style="opacity: 1">
                            <div class="framer-17vdob9" style="opacity: 1">
                                <div class="framer-1vmyqip"
                                    data-framer-name="Dietary-supplement-lingzhi-mushroom-dxn-health-coffee-health-thumbnail"
                                    style="opacity: 1">
                                    <div data-framer-background-image-wrapper="true" style="
                                    position: absolute;
                                    border-radius: inherit;
                                    corner-shape: inherit;
                                    inset: 0px;
                                  ">
                                        <img decoding="auto" width="263" height="257"
                                            src="https://framerusercontent.com/images/nacEHk0iUMmtX9q6qNkhonBUxs.png?width=263&amp;height=257"
                                            alt="" style="
                                      display: block;
                                      width: 100%;
                                      height: 100%;
                                      border-radius: inherit;
                                      corner-shape: inherit;
                                      object-position: center center;
                                      object-fit: cover;
                                    " />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="ticker-item" aria-hidden="false" aria-posinset="3" aria-setsize="3" style="
                          flex-grow: 0;
                          flex-shrink: 0;
                          position: relative;
                          height: fit-content;
                          width: fit-content;
                          transform: none;
                        ">
                    <div class="framer-1x8ohte-container" data-framer-name="Logos" name="Logos">
                        <div name="Logos" class="framer-FB8H6 framer-g2nkge framer-v-1o3ee2z" data-framer-name="iherb"
                            style="opacity: 1">
                            <div class="framer-17vdob9" style="opacity: 1">
                                <div class="framer-j4udfp" data-framer-name="IHerb Logo" style="opacity: 1">
                                    <div data-framer-background-image-wrapper="true" style="
                                    position: absolute;
                                    border-radius: inherit;
                                    corner-shape: inherit;
                                    inset: 0px;
                                  ">
                                        <img decoding="auto" width="10240" height="3472" sizes="72px" srcset="
                                      https://framerusercontent.com/images/iw3pKLbklnQKnFKBuZ2LK3w7E.png?scale-down-to=512&amp;width=10240&amp;height=3472    512w,
                                      https://framerusercontent.com/images/iw3pKLbklnQKnFKBuZ2LK3w7E.png?scale-down-to=1024&amp;width=10240&amp;height=3472  1024w,
                                      https://framerusercontent.com/images/iw3pKLbklnQKnFKBuZ2LK3w7E.png?scale-down-to=2048&amp;width=10240&amp;height=3472  2048w,
                                      https://framerusercontent.com/images/iw3pKLbklnQKnFKBuZ2LK3w7E.png?scale-down-to=4096&amp;width=10240&amp;height=3472  4096w,
                                      https://framerusercontent.com/images/iw3pKLbklnQKnFKBuZ2LK3w7E.png?width=10240&amp;height=3472                        10240w
                                    " src="https://framerusercontent.com/images/iw3pKLbklnQKnFKBuZ2LK3w7E.png?width=10240&amp;height=3472"
                                            alt="" style="
                                      display: block;
                                      width: 100%;
                                      height: 100%;
                                      border-radius: inherit;
                                      corner-shape: inherit;
                                      object-position: center center;
                                      object-fit: cover;
                                    " />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- FEATURED PRODUCTS — NEW SECTION (Replaces Ticker) --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <section class="elx-section elx-products" id="products" style="background-color: #13252d; padding: 6rem 0;">
        <div class="elx-container">
            <div class="elx-section__header" data-animate style="text-align: center; margin-bottom: 4rem;">
                <h2 class="elx-section__title">Our Signature Selection</h2>
            </div>

            @if($featuredItems->count() > 0)
                <div class="elx-products__grid" data-animate
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 3rem;">
                    @foreach($featuredItems->take(4) as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            @else
                <div class="elx-products__empty" data-animate style="text-align: center; color: white;">
                    <div class="elx-products__empty-icon"><i class="fas fa-box-open"
                            style="font-size: 3rem; margin-bottom: 1rem;"></i></div>
                    <h3>Coming Soon</h3>
                    <p>Pure herbs and handcrafted blends are on their way.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- BRAND INSIGHTS --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <section class="elx-section elx-insights" id="insights">
        <div class="elx-container">
            <div class="elx-insights__grid" data-animate>
                <div class="elx-insights__text">
                    <span class="elx-insights__label">Brand Insights</span>
                    <h2>Explore</h2>
                    <p>Clean, potent, and beautifully crafted formulas — rooted in nature, guided by modern wellness.</p>
                    <a href="{{ route('menu.index') }}" class="elx-btn elx-btn--primary">Discover More</a>
                </div>
                <div class="elx-insights__stats">
                    <div class="elx-stat-card">
                        <span class="elx-stat-card__number">{{ \App\Models\Item::count() }}</span>
                        <span class="elx-stat-card__label">Products</span>
                    </div>
                    <div class="elx-stat-card">
                        <span class="elx-stat-card__number">{{ \App\Models\Category::count() }}</span>
                        <span class="elx-stat-card__label">Categories</span>
                    </div>
                    <div class="elx-stat-card">
                        <span class="elx-stat-card__number">5</span>
                        <span class="elx-stat-card__label">Star Rating</span>
                    </div>
                    <div class="elx-stat-card">
                        <span class="elx-stat-card__number">∞</span>
                        <span class="elx-stat-card__label">Wellness</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- NEWSLETTER --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <section class="elx-section elx-newsletter" id="newsletter">
        <div class="elx-container">
            <div class="elx-newsletter__box" data-animate>
                <h2>Unlock exclusive launches, curated tips, and members‑only offers.</h2>
                <p class="elx-newsletter__sub">No Spam, Good Stuff Only!</p>
                <div class="elx-newsletter__form">
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" style="display: flex; width: 100%; gap: 10px;">
                        @csrf
                        <input type="email" name="email" placeholder="Your email address" class="elx-newsletter__input" required style="flex: 1;">
                        <button type="submit" class="elx-newsletter__btn">Receive the Whisper</button>
                    </form>
                </div>
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- TESTIMONIALS SECTION --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @php
        $homeReviews = \App\Models\Review::where('status', 'approved')->where('type', 'direct')->latest()->take(6)->get();
    @endphp
    @if($homeReviews->isNotEmpty())
    <style>
        .testimonial-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .testimonial-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
            border-color: rgba(74, 200, 246, 0.3) !important;
            background: #11222b !important;
        }
        .testimonial-card:hover .avatar-container {
            box-shadow: 0 0 25px rgba(74, 200, 246, 0.5) !important;
            transform: rotate(5deg);
        }
    </style>
    <section class="elx-section" style="padding: 6rem 0; background-color: #0b161c;">
        <div class="elx-container">
            <h2 class="elx-section__title" style="text-align: center; color: #4ac8f6; margin-bottom: 4rem; font-family: 'Istok Web', sans-serif;">What Our Community Says</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 40px;">
                @foreach($homeReviews as $rev)
                    <div class="testimonial-card" style="background: #0d1a20; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 30px; padding: 40px; position: relative; box-shadow: 0 15px 40px rgba(0,0,0,0.4); display: flex; flex-direction: column; justify-content: space-between; min-height: 400px;">
                        
                        <!-- Header: Stars & Rating Circle -->
                        <div style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 20px;">
                            <div style="display: flex; gap: 5px; color: #4ac8f6; font-size: 1.2rem;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $rev->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                            <div style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid #4ac8f6; display: flex; align-items: center; justify-content: center; color: #4ac8f6; font-weight: bold; font-size: 1rem; box-shadow: 0 0 10px rgba(74, 200, 246, 0.3);">
                                {{ $rev->rating }}
                            </div>
                        </div>

                        <!-- Quote Icon Top Left -->
                        <i class="fas fa-quote-left" style="position: absolute; top: 30px; left: 30px; font-size: 2rem; color: rgba(255,255,255,0.05);"></i>

                        <!-- Content -->
                        <div style="text-align: center; flex-grow: 1; display: flex; align-items: center; padding: 20px 0;">
                            <p style="color: #eee; font-size: 1.2rem; line-height: 1.7; font-family: 'Istok Web', sans-serif; font-weight: 400; font-style: normal;">
                                {{ $rev->content }}
                            </p>
                        </div>

                        <!-- Quote Icon Bottom Right -->
                        <i class="fas fa-quote-right" style="position: absolute; bottom: 100px; right: 30px; font-size: 2rem; color: rgba(255,255,255,0.05);"></i>

                        <!-- Footer: User Info -->
                        <div style="display: flex; align-items: center; gap: 15px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05);">
                            <div class="avatar-container" style="width: 55px; height: 55px; border-radius: 50%; border: 2px solid #4ac8f6; padding: 2px; box-shadow: 0 0 15px rgba(74, 200, 246, 0.2); transition: all 0.3s ease;">
                                <img src="{{ $rev->avatar ?? 'https://framerusercontent.com/images/cTc7CUtNbTmlTgoiKuHSwOHME.png' }}" alt="{{ $rev->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <h4 style="margin: 0; color: #fff; font-size: 1.1rem; font-weight: 700;">{{ $rev->name }}</h4>
                                <div style="display: flex; gap: 15px; font-size: 0.85rem; color: #666; margin-top: 3px;">
                                    <span>Gen.: <strong style="color: #999;">{{ $rev->skin_type ?? 'N/A' }}</strong></span>
                                    <span>Age: <strong style="color: #999;">{{ $rev->age ?? 'N/A' }}</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div style="text-align: center; margin-top: 4rem;">
                <a href="{{ route('testimonials.index') }}" class="elx-btn elx-btn--glass" style="padding: 15px 40px; border-radius: 30px;">
                    Read More Reflections <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
                </a>
            </div>
        </div>
    </section>
    @endif
@endsection

@section('scripts')
    <script>
        document.body.classList.add('home-page');
        // منطق الهيدر الشفاف في الهوم فقط
        const nav = document.getElementById('elxNav');
        if (nav) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            });
        }
    </script>
@endsection