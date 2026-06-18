@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1 fw-bold">{{ __('admin.blogs_page.new_blog') }}</h2>
        <p class="text-muted mb-0">{{ __('admin.blogs_page.write_subtitle') }}</p>
    </div>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> {{ __('admin.blogs_page.back') }}</a>
</div>

<form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">{{ __('admin.blogs_page.section_en') }}</h5>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.title_en') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                               value="{{ old('title_en') }}" placeholder="{{ __('admin.blogs_page.placeholder_t_en') }}" required>
                        @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.summary_en') }} <small class="text-muted">{{ __('admin.blogs_page.optional') }}</small></label>
                        <textarea name="summary_en" class="form-control" rows="2" placeholder="{{ __('admin.blogs_page.placeholder_s_en') }}">{{ old('summary_en') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.content_en') }} <span class="text-danger">*</span></label>
                        <textarea name="content_en" class="form-control @error('content_en') is-invalid @enderror"
                                  rows="10" placeholder="{{ __('admin.blogs_page.placeholder_c_en') }}" required>{{ old('content_en') }}</textarea>
                        @error('content_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-4">
                    <h5 class="fw-bold mb-4">{{ __('admin.blogs_page.section_ar') }} <small class="text-muted" style="font-size: 0.8rem;">(RTL)</small></h5>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.title_ar') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror"
                               value="{{ old('title_ar') }}" dir="rtl" placeholder="{{ __('admin.blogs_page.placeholder_t_ar') }}" required>
                        @error('title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.summary_ar') }} <small class="text-muted">{{ __('admin.blogs_page.optional') }}</small></label>
                        <textarea name="summary_ar" class="form-control" rows="2" dir="rtl" placeholder="{{ __('admin.blogs_page.placeholder_s_ar') }}">{{ old('summary_ar') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.content_ar') }} <span class="text-danger">*</span></label>
                        <textarea name="content_ar" class="form-control @error('content_ar') is-invalid @enderror"
                                  rows="10" dir="rtl" placeholder="{{ __('admin.blogs_page.placeholder_c_ar') }}" required>{{ old('content_ar') }}</textarea>
                        @error('content_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">{{ __('admin.blogs_page.settings') }}</h5>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.cover_image') }}</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">{{ __('admin.blogs_page.image_hint') }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.gallery_images') }}</label>
                        <input type="file" name="gallery[]" class="form-control @error('gallery.*') is-invalid @enderror" accept="image/*" multiple>
                        <div class="form-text">{{ __('admin.blogs_page.gallery_hint') }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.video_url') }}</label>
                        <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror"
                               value="{{ old('video_url') }}" placeholder="{{ __('admin.blogs_page.video_url_placeholder') }}">
                        @error('video_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">{{ __('admin.blogs_page.video_url_hint') }}</div>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1"
                               {{ old('is_published', 1) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_published">{{ __('admin.blogs_page.publish_immediately') }}</label>
                        <div class="form-text">{{ __('admin.blogs_page.publish_hint') }}</div>
                    </div>

                    @include('admin.partials.tags-input', ['selectedTags' => $selectedTags ?? '', 'tagSuggestions' => $tagSuggestions ?? []])

                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane me-2"></i> {{ __('admin.blogs_page.publish_btn') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

