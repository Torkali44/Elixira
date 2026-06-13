@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1 fw-bold">{{ __('admin.blogs_page.edit_blog') }}</h2>
        <p class="text-muted mb-0">{{ __('admin.blogs_page.update_subtitle') }}</p>
    </div>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> {{ __('admin.blogs_page.back') }}</a>
</div>

<form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">{{ __('admin.blogs_page.section_en') }}</h5>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.title_en') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                               value="{{ old('title_en', $blog->title_en) }}" required>
                        @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.summary_en') }} <small class="text-muted">{{ __('admin.blogs_page.optional') }}</small></label>
                        <textarea name="summary_en" class="form-control" rows="2">{{ old('summary_en', $blog->summary_en) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.content_en') }} <span class="text-danger">*</span></label>
                        <textarea name="content_en" class="form-control @error('content_en') is-invalid @enderror"
                                  rows="10" required>{{ old('content_en', $blog->content_en) }}</textarea>
                        @error('content_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-4">
                    <h5 class="fw-bold mb-4">{{ __('admin.blogs_page.section_ar') }} <small class="text-muted" style="font-size: 0.8rem;">(RTL)</small></h5>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.title_ar') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror"
                               value="{{ old('title_ar', $blog->title_ar) }}" dir="rtl" required>
                        @error('title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.summary_ar') }} <small class="text-muted">{{ __('admin.blogs_page.optional') }}</small></label>
                        <textarea name="summary_ar" class="form-control" rows="2" dir="rtl">{{ old('summary_ar', $blog->summary_ar) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.blogs_page.content_ar') }} <span class="text-danger">*</span></label>
                        <textarea name="content_ar" class="form-control @error('content_ar') is-invalid @enderror"
                                  rows="10" dir="rtl" required>{{ old('content_ar', $blog->content_ar) }}</textarea>
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
                        @if($blog->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ __('admin.blogs_page.current_cover') }}" class="rounded border w-100" style="max-height: 150px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">{{ __('admin.blogs_page.image_keep_hint') }}</div>
                    </div>
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1"
                               {{ old('is_published', $blog->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_published">{{ __('admin.blogs_page.status_published') }}</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i> {{ __('admin.blogs_page.update_btn') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
