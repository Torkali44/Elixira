@php
    $item = $item ?? null;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name_en" class="form-label">{{ __('admin.items_page.name_en') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en"
            value="{{ old('name_en', $item?->name_en ?: $item?->name) }}" maxlength="255" placeholder="Product name in English">
        @error('name_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="name_ar" class="form-label">{{ __('admin.items_page.name_ar') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar"
            value="{{ old('name_ar', $item?->name_ar) }}" maxlength="255" dir="rtl" placeholder="اسم المنتج بالعربي">
        @error('name_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="size_en" class="form-label">{{ __('admin.items_page.size_en') }}</label>
        <input type="text" class="form-control @error('size_en') is-invalid @enderror" id="size_en" name="size_en"
            value="{{ old('size_en', $item?->size_en) }}" maxlength="120" placeholder="e.g. 500ml">
        @error('size_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="size_ar" class="form-label">{{ __('admin.items_page.size_ar') }}</label>
        <input type="text" class="form-control @error('size_ar') is-invalid @enderror" id="size_ar" name="size_ar"
            value="{{ old('size_ar', $item?->size_ar) }}" maxlength="120" dir="rtl" placeholder="مثال: 500 مل">
        @error('size_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label for="description_en" class="form-label">{{ __('admin.items_page.description_en') }} <span class="text-danger">*</span></label>
    <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3" placeholder="Short description in English">{{ old('description_en', $item?->description_en ?: $item?->description) }}</textarea>
    @error('description_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="description_ar" class="form-label">{{ __('admin.items_page.description_ar') }} <span class="text-danger">*</span></label>
    <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3" dir="rtl" placeholder="وصف مختصر بالعربي">{{ old('description_ar', $item?->description_ar) }}</textarea>
    @error('description_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>
