@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1 fw-bold">{{ __('admin.faqs_page.edit_faq') }}</h2>
        <p class="text-muted mb-0">{{ __('admin.faqs_page.edit_subtitle') }}</p>
    </div>
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> {{ __('admin.faqs_page.back') }}</a>
</div>

<form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">{{ __('admin.faqs_page.section_en') }}</h5>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.faqs_page.question_en') }} <span class="text-danger">*</span></label>
                        <input type="text" name="question_en" class="form-control @error('question_en') is-invalid @enderror"
                               value="{{ old('question_en', $faq->question_en) }}" required>
                        @error('question_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.faqs_page.answer_en') }} <span class="text-danger">*</span></label>
                        <textarea name="answer_en" class="form-control @error('answer_en') is-invalid @enderror"
                                  rows="5" required>{{ old('answer_en', $faq->answer_en) }}</textarea>
                        @error('answer_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-4">
                    <h5 class="fw-bold mb-4">{{ __('admin.faqs_page.section_ar') }} <small class="text-muted" style="font-size: 0.8rem;">(RTL)</small></h5>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.faqs_page.question_ar') }} <span class="text-danger">*</span></label>
                        <input type="text" name="question_ar" class="form-control @error('question_ar') is-invalid @enderror"
                               value="{{ old('question_ar', $faq->question_ar) }}" dir="rtl" required>
                        @error('question_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.faqs_page.answer_ar') }} <span class="text-danger">*</span></label>
                        <textarea name="answer_ar" class="form-control @error('answer_ar') is-invalid @enderror"
                                  rows="5" dir="rtl" required>{{ old('answer_ar', $faq->answer_ar) }}</textarea>
                        @error('answer_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">{{ __('admin.faqs_page.settings') }}</h5>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('admin.faqs_page.col_order') }}</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $faq->sort_order) }}" min="0">
                        <div class="form-text">{{ __('admin.faqs_page.sort_order_hint') }}</div>
                    </div>
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1"
                               {{ old('is_published', $faq->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_published">{{ __('admin.faqs_page.status_published') }}</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i> {{ __('admin.faqs_page.update') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
