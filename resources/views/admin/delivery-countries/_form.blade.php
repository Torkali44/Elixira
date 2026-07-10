@php
    $country = $country ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('admin.delivery_zones.code') }} <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $country?->code) }}" maxlength="10" placeholder="KSA" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('admin.delivery_zones.currency_label_en') }} <span class="text-danger">*</span></label>
        <input type="text" name="currency_label_en" class="form-control @error('currency_label_en') is-invalid @enderror" value="{{ old('currency_label_en', $country?->currency_label_en ?? $country?->currency_code ?? 'SAR') }}" maxlength="20" placeholder="SAR" required>
        @error('currency_label_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('admin.delivery_zones.currency_label_ar') }} <span class="text-danger">*</span></label>
        <input type="text" name="currency_label_ar" class="form-control @error('currency_label_ar') is-invalid @enderror" value="{{ old('currency_label_ar', $country?->currency_label_ar) }}" maxlength="20" dir="rtl" placeholder="ريال" required>
        @error('currency_label_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('admin.delivery_zones.sort_order') }}</label>
        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $country?->sort_order ?? 0) }}" />
        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('admin.delivery_zones.name_en') }} <span class="text-danger">*</span></label>
        <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $country?->name_en) }}" required>
        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('admin.delivery_zones.name_ar') }} <span class="text-danger">*</span></label>
        <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $country?->name_ar) }}" dir="rtl" required>
        @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $country?->is_active ?? true))>
            <label class="form-check-label" for="is_active">{{ __('admin.delivery_zones.active') }}</label>
        </div>
    </div>
</div>
