@if(isset($relatedBlogs) && $relatedBlogs->count() > 0)
<div class="elx-section" style="margin-top: 4rem;">
    <div class="elx-section__header" style="text-align: left; margin-bottom: 2rem;" data-animate>
        <h2 class="elx-section__title">{{ __('shop.related_blogs') }}</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;" data-animate>
        @foreach($relatedBlogs as $blog)
            <a href="{{ route('blogs.show', $blog->slug) }}"
               style="display: block; text-decoration: none; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; overflow: hidden; transition: transform 0.2s ease;">
                @if($blog->image)
                    <img src="{{ asset('storage/'.$blog->image) }}" alt="{{ $blog->title }}" style="width: 100%; height: 160px; object-fit: cover;">
                @endif
                <div style="padding: 1.1rem 1.2rem 1.3rem;">
                    <div style="color: var(--elx-cyan); font-size: 0.75rem; font-weight: 700; margin-bottom: 0.5rem;">
                        {{ $blog->published_at?->format('M d, Y') ?? $blog->created_at->format('M d, Y') }}
                    </div>
                    <h3 style="color: #fff; font-size: 1.05rem; line-height: 1.45; margin: 0;">{{ $blog->title }}</h3>
                    <p style="color: rgba(255,255,255,0.65); font-size: 0.88rem; margin: 0.75rem 0 0;">{{ Str::limit($blog->summary, 110) }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

@if(isset($relatedReviews) && $relatedReviews->count() > 0)
<div class="elx-section" style="margin-top: 4rem;">
    <div class="elx-section__header" style="text-align: left; margin-bottom: 2rem;" data-animate>
        <h2 class="elx-section__title">{{ __('shop.related_testimonials') }}</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;" data-animate>
        @foreach($relatedReviews as $review)
            <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; overflow: hidden;">
                @if($review->type === 'video')
                    @php $embed = \App\Support\YoutubeEmbed::fromUrl($review->content); @endphp
                    @if($embed)
                    <div style="position: relative; padding-top: 56.25%; background: #000;">
                        <iframe src="{{ $embed }}" title="YouTube video" style="position:absolute; inset:0; width:100%; height:100%; border:0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
                    </div>
                    @else
                    <div style="padding: 1.5rem; color: rgba(255,255,255,0.6); font-size: 0.9rem;">{{ __('shop.video_unavailable') }}</div>
                    @endif
                @elseif($review->avatar)
                    <img src="{{ asset('storage/'.$review->avatar) }}" alt="" style="width: 100%; height: 220px; object-fit: cover;">
                @endif
                <div style="padding: 1rem 1.2rem 1.3rem;">
                    <span style="color: var(--elx-cyan); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                        {{ __('testimonials_page.tab_'.$review->type) }}
                    </span>
                    @if($review->content && $review->type !== 'video')
                        <p style="color: rgba(255,255,255,0.75); font-size: 0.9rem; margin: 0.75rem 0 0;">{{ Str::limit($review->content, 140) }}</p>
                    @endif
                    <a href="{{ route('testimonials.index', ['tab' => $review->type]) }}" style="display: inline-block; margin-top: 0.75rem; color: #4ac8f6; font-size: 0.85rem; text-decoration: none;">{{ __('shop.view_all_testimonials') }}</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

@if(isset($relatedPackages) && $relatedPackages->count() > 0)
<div class="elx-section" style="margin-top: 4rem;">
    <div class="elx-section__header" style="text-align: left; margin-bottom: 2rem;" data-animate>
        <h2 class="elx-section__title">{{ __('shop.related_packages') }}</h2>
    </div>

    <div class="elx-products__grid menu-products-grid" data-animate>
        @foreach($relatedPackages as $relatedPackage)
            @include('partials.package-card', ['package' => $relatedPackage])
        @endforeach
    </div>
</div>
@endif
