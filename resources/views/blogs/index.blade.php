@extends('layouts.framer')

@section('title', __('blogs_page.page_title'))

@section('content')
<div class="page-content" style="padding-top: 0;">
    {{-- Header --}}
    <section style="background: linear-gradient(180deg, #13252d 0%, #000000 100%); padding: 120px 0 60px;">
        <div class="elx-container">
            <div class="elx-section__header" data-animate>
                <h1 class="elx-hero__title" style="margin-bottom: 1.5rem;">
                    <span class="elx-hero__title-gradient">{{ __('blogs_page.hero_title') }}</span>
                </h1>
                <p class="elx-hero__subtitle">{{ __('blogs_page.hero_subtitle') }}</p>
            </div>
        </div>
    </section>

    {{-- Blogs Grid --}}
    <section class="elx-section" style="background: #0d1a21; padding: 60px 0 100px;">
        <div class="elx-container">
            @if($blogs->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(330px, 1fr)); gap: 2.5rem;" data-animate>
                    @foreach($blogs as $blog)
                        <div class="blog-card" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 28px; overflow: hidden; display: flex; flex-direction: column; transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1); box-shadow: 0 10px 30px rgba(0,0,0,0.25);">
                            <a href="{{ route('blogs.show', $blog->slug) }}" style="display: block; height: 220px; overflow: hidden; position: relative;">
                                @if($blog->image)
                                    <img class="blog-card-img" src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                @else
                                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, rgba(74, 200, 246, 0.15), rgba(183, 215, 208, 0.15)); display: flex; align-items: center; justify-content: center; color: var(--elx-cyan);">
                                        <i class="fas fa-newspaper fa-3x"></i>
                                    </div>
                                @endif
                            </a>
                            <div style="padding: 1.75rem; display: flex; flex-direction: column; flex-grow: 1;">
                                <div style="display: flex; gap: 1rem; align-items: center; color: var(--elx-cyan); font-size: 0.8rem; font-weight: 600; margin-bottom: 1rem; letter-spacing: 1px; text-transform: uppercase;">
                                    <span><i class="far fa-calendar-alt me-1"></i> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                                    <span>•</span>
                                    <span><i class="far fa-user me-1"></i> {{ __('blogs_page.by_admin') }}</span>
                                </div>
                                <h3 style="font-size: 1.35rem; font-weight: 700; color: white; margin-bottom: 1rem; line-height: 1.4; text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};">
                                    <a href="{{ route('blogs.show', $blog->slug) }}" style="color: white; text-decoration: none; transition: color 0.2s;">{{ $blog->title }}</a>
                                </h3>
                                <p style="color: rgba(255,255,255,0.65); font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.75rem; flex-grow: 1; text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};">
                                    {{ Str::limit(strip_tags($blog->summary), 120) }}
                                </p>
                                <a href="{{ route('blogs.show', $blog->slug) }}" class="elx-btn elx-btn--glass" style="padding: 0.6rem 1.5rem; font-size: 0.9rem; align-self: flex-start; gap: 0.5rem;">
                                    {{ __('blogs_page.read_more') }} <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}" style="font-size: 0.8rem;"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top: 4rem; display: flex; justify-content: center;">
                    {{ $blogs->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 6rem 2rem; color: rgba(255,255,255,0.4);" data-animate>
                    <i class="fas fa-folder-open" style="font-size: 4.5rem; margin-bottom: 2rem; opacity: 0.3; color: var(--elx-cyan);"></i>
                    <p style="font-size: 1.15rem;">{{ __('blogs_page.empty') }}</p>
                </div>
            @endif
        </div>
    </section>
</div>

<style>
    .blog-card:hover {
        transform: translateY(-8px);
        border-color: rgba(74, 200, 246, 0.4);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 20px rgba(74, 200, 246, 0.15);
    }
    .blog-card:hover .blog-card-img {
        transform: scale(1.05);
    }
</style>
@endsection
