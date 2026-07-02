@php
    $model = $model ?? null;
    $prefix = $prefix ?? 'detail-sections';
@endphp

<div class="mb-4">
    <h5 class="fw-semibold mb-3">{{ __('admin.items_page.detail_sections') }}</h5>
    <p class="text-muted small mb-3">{{ __('admin.items_page.detail_sections_hint') }}</p>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#{{ $prefix }}-benefits-en">{{ __('admin.items_page.benefits_en') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#{{ $prefix }}-benefits-ar">{{ __('admin.items_page.benefits_ar') }}</a>
        </li>
    </ul>
    <div class="tab-content mb-3">
        <div class="tab-pane fade show active" id="{{ $prefix }}-benefits-en">
            <textarea class="form-control @error('benefits_en') is-invalid @enderror" name="benefits_en" rows="4" placeholder="Product benefits in English">{{ old('benefits_en', $model?->benefits_en) }}</textarea>
            @error('benefits_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="tab-pane fade" id="{{ $prefix }}-benefits-ar">
            <textarea class="form-control @error('benefits_ar') is-invalid @enderror" name="benefits_ar" rows="4" dir="rtl" placeholder="فوائد المنتج بالعربي">{{ old('benefits_ar', $model?->benefits_ar) }}</textarea>
            @error('benefits_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#{{ $prefix }}-ingredients-en">{{ __('admin.items_page.ingredients_en') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#{{ $prefix }}-ingredients-ar">{{ __('admin.items_page.ingredients_ar') }}</a>
        </li>
    </ul>
    <div class="tab-content mb-3">
        <div class="tab-pane fade show active" id="{{ $prefix }}-ingredients-en">
            <textarea class="form-control @error('ingredients_en') is-invalid @enderror" name="ingredients_en" rows="4" placeholder="Ingredients in English">{{ old('ingredients_en', $model?->ingredients_en) }}</textarea>
            @error('ingredients_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="tab-pane fade" id="{{ $prefix }}-ingredients-ar">
            <textarea class="form-control @error('ingredients_ar') is-invalid @enderror" name="ingredients_ar" rows="4" dir="rtl" placeholder="المكونات بالعربي">{{ old('ingredients_ar', $model?->ingredients_ar) }}</textarea>
            @error('ingredients_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#{{ $prefix }}-usage-en">{{ __('admin.items_page.usage_en') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#{{ $prefix }}-usage-ar">{{ __('admin.items_page.usage_ar') }}</a>
        </li>
    </ul>
    <div class="tab-content mb-3">
        <div class="tab-pane fade show active" id="{{ $prefix }}-usage-en">
            <textarea class="form-control @error('usage_instructions_en') is-invalid @enderror" name="usage_instructions_en" rows="4" placeholder="How to use in English">{{ old('usage_instructions_en', $model?->usage_instructions_en) }}</textarea>
            @error('usage_instructions_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="tab-pane fade" id="{{ $prefix }}-usage-ar">
            <textarea class="form-control @error('usage_instructions_ar') is-invalid @enderror" name="usage_instructions_ar" rows="4" dir="rtl" placeholder="طريقة الاستخدام بالعربي">{{ old('usage_instructions_ar', $model?->usage_instructions_ar) }}</textarea>
            @error('usage_instructions_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#{{ $prefix }}-warnings-en">{{ __('admin.items_page.warnings_en') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#{{ $prefix }}-warnings-ar">{{ __('admin.items_page.warnings_ar') }}</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="{{ $prefix }}-warnings-en">
            <textarea class="form-control @error('warnings_en') is-invalid @enderror" name="warnings_en" rows="4" placeholder="Warnings in English">{{ old('warnings_en', $model?->warnings_en) }}</textarea>
            @error('warnings_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="tab-pane fade" id="{{ $prefix }}-warnings-ar">
            <textarea class="form-control @error('warnings_ar') is-invalid @enderror" name="warnings_ar" rows="4" dir="rtl" placeholder="التحذيرات بالعربي">{{ old('warnings_ar', $model?->warnings_ar) }}</textarea>
            @error('warnings_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
    </div>
</div>
