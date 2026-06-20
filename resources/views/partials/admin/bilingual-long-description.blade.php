@php
    $prefix = $prefix ?? 'long-desc';
    $model = $model ?? null;
    $hintKey = $hintKey ?? 'admin.items_page.long_description_hint';
@endphp

<div class="mb-3">
    <label class="form-label fw-semibold">{{ $label ?? __('admin.items_page.long_description') }}</label>
    <ul class="nav nav-tabs mb-2" role="tablist">
        <li class="nav-item">
            <a class="nav-link active @error('long_description_en') text-danger fw-bold @enderror" data-bs-toggle="tab" href="#{{ $prefix }}-en-tab">
                {{ __('admin.items_page.long_description_en') }}
                @error('long_description_en')<span class="badge bg-danger ms-1">!</span>@enderror
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @error('long_description_ar') text-danger fw-bold @enderror" data-bs-toggle="tab" href="#{{ $prefix }}-ar-tab">
                {{ __('admin.items_page.long_description_ar') }}
                @error('long_description_ar')<span class="badge bg-danger ms-1">!</span>@enderror
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="{{ $prefix }}-en-tab">
            <textarea class="form-control @error('long_description_en') is-invalid @enderror" id="{{ $prefix }}_en" name="long_description_en" rows="8" placeholder="Write detailed information in English...">{{ old('long_description_en', $model?->long_description_en) }}</textarea>
            @error('long_description_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="tab-pane fade" id="{{ $prefix }}-ar-tab">
            <textarea class="form-control @error('long_description_ar') is-invalid @enderror" id="{{ $prefix }}_ar" name="long_description_ar" rows="8" dir="rtl" placeholder="اكتب التفاصيل بالعربي...">{{ old('long_description_ar', $model?->long_description_ar) }}</textarea>
            @error('long_description_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
    </div>
    <small class="text-muted">{{ __($hintKey) }}</small>
</div>
