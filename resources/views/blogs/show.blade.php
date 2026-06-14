@extends('layouts.framer')

@section('title', $blog->title . ' - Elixira')

@section('content')
<div class="page-content" style="padding-top: 0;">
    {{-- Header Banner --}}
    @if($blog->image)
        <div style="width: 100%; height: 50vh; min-height: 400px; position: relative; overflow: hidden; background: #000;">
            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.65;">
            <div style="position: absolute; inset: 0; background: linear-gradient(180deg, transparent 40%, #0d1a21 100%);"></div>
        </div>
    @else
        <section style="background: linear-gradient(180deg, #13252d 0%, #0d1a21 100%); padding: 140px 0 60px;">
            <div class="elx-container">
                <div style="max-width: 800px; margin: 0 auto; text-align: center;">
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--elx-cyan); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 1.5rem; letter-spacing: 1px;">
                        <span><i class="far fa-calendar-alt"></i> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                        <span>•</span>
                        <span>{{ __('By Admin') }}</span>
                    </div>
                    <h1 class="elx-hero__title" style="font-size: clamp(2rem, 5vw, 3.2rem); line-height: 1.3; color: white;">{{ $blog->title }}</h1>
                </div>
            </div>
        </section>
    @endif

    {{-- Content --}}
    <section class="elx-section" style="background: #0d1a21; padding: 40px 0 100px;">
        <div class="elx-container">
            <div style="max-width: 800px; margin: 0 auto;" data-animate>
                @if($blog->image)
                    <div style="margin-bottom: 2.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--elx-cyan); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 1.2rem; letter-spacing: 1px;">
                            <span><i class="far fa-calendar-alt"></i> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                            <span>•</span>
                            <span>{{ __('By Admin') }}</span>
                        </div>
                        <h1 style="font-size: clamp(2rem, 5vw, 3.2rem); line-height: 1.3; color: white; margin-bottom: 2rem; text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};">{{ $blog->title }}</h1>
                    </div>
                @endif

                <div class="blog-body-content" style="color: rgba(255,255,255,0.8); line-height: 1.9; font-size: 1.15rem; font-family: var(--elx-font); text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};">
                    {!! nl2br($blog->content) !!}
                </div>

                {{-- Embedded Video --}}
                @if($blog->embed_video_url)
                    <div style="margin-top: 3rem;">
                        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08);">
                            <iframe src="{{ $blog->embed_video_url }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" frameborder="0" allowfullscreen loading="lazy"></iframe>
                        </div>
                    </div>
                @endif

                {{-- Image Gallery --}}
                @if($blog->images->count() > 0)
                    <div style="margin-top: 3rem;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem;">
                            @foreach($blog->images as $img)
                                <a href="{{ asset('storage/' . $img->image) }}" target="_blank" style="display: block; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.08); aspect-ratio: 4/3;">
                                    <img src="{{ asset('storage/' . $img->image) }}"
                                         alt="{{ $blog->title }}"
                                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.35s ease;"
                                         onmouseover="this.style.transform='scale(1.05)'"
                                         onmouseout="this.style.transform='scale(1)'">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div style="margin-top: 5rem; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 2.5rem; display: flex; justify-content: {{ app()->getLocale() === 'ar' ? 'flex-end' : 'flex-start' }};">
                    <a href="{{ route('blogs.index') }}" class="elx-btn elx-btn--glass" style="gap: 0.5rem; display: inline-flex; align-items: center;">
                        <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i> {{ __('blogs_page.back') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

