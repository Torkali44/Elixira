@props(['brand' => null, 'vendorProfiles' => collect()])

<div class="mb-4">
    <label for="vendor_profile_id" class="form-label fw-bold text-muted small text-uppercase">{{ __('admin.brands_page.link_vendor') }}</label>
    <select name="vendor_profile_id" id="vendor_profile_id" class="form-select @error('vendor_profile_id') is-invalid @enderror" style="border-radius: 8px;">
        <option value="">{{ __('admin.brands_page.no_vendor_link') }}</option>
        @foreach($vendorProfiles as $profile)
            <option value="{{ $profile->id }}" @selected(old('vendor_profile_id', $brand?->vendor_profile_id) == $profile->id)>
                {{ $profile->brand_name ?? $profile->user?->name ?? __('admin.brands_page.vendor_number', ['id' => $profile->id]) }}
            </option>
        @endforeach
    </select>
    @error('vendor_profile_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label fw-bold text-muted small text-uppercase">{{ __('admin.brands_edit.logo') }}</label>
    <div class="d-flex align-items-center gap-4 p-3 bg-light" style="border-radius: 12px; border: 1px dashed rgba(0,0,0,0.1);">
        <div class="logo-preview-wrapper" style="position: relative;">
            @if($brand?->logo)
                <img id="logoPreview" src="{{ asset('storage/' . $brand->logo) }}" class="rounded-circle shadow-sm border border-2 border-white" style="width: 80px; height: 80px; object-fit: cover;">
            @else
                <div id="logoPlaceholder" class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 80px; height: 80px; font-weight: bold; font-size: 1.5rem;">
                    {{ strtoupper(substr(old('name', $brand?->name ?? 'BR'), 0, 2)) }}
                </div>
            @endif
        </div>
        <div class="flex-grow-1">
            <input type="file" name="logo" id="logoInput" class="form-control form-control-sm mb-2" accept="image/*">
            <small class="text-muted d-block">{{ __('admin.brands_edit.logo_hint') }}</small>
        </div>
    </div>
</div>

<div class="mb-4">
    <label for="name" class="form-label fw-bold text-muted small text-uppercase">{{ __('admin.brands_edit.name') }}</label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $brand?->name) }}" required style="border-radius: 8px;">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="description" class="form-label fw-bold text-muted small text-uppercase">{{ __('admin.brands_edit.description') }}</label>
    <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="{{ __('admin.brands_edit.description_placeholder') }}" style="border-radius: 8px;">{{ old('description', $brand?->description) }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted small text-uppercase d-block">{{ __('admin.brands_edit.service_countries') }}</label>
        <div class="d-flex gap-4 mt-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="service_countries[]" value="Saudi Arabia" id="countryKsa"
                    {{ in_array('Saudi Arabia', old('service_countries', $brand?->service_countries ?? [])) ? 'checked' : '' }}>
                <label class="form-check-label fw-medium text-dark" for="countryKsa">
                    {{ __('admin.brands_edit.country_ksa') }}
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="service_countries[]" value="UAE" id="countryUae"
                    {{ in_array('UAE', old('service_countries', $brand?->service_countries ?? [])) ? 'checked' : '' }}>
                <label class="form-check-label fw-medium text-dark" for="countryUae">
                    {{ __('admin.brands_edit.country_uae') }}
                </label>
            </div>
        </div>
        @error('service_countries')
            <div class="text-danger small mt-2 d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted small text-uppercase d-block">{{ __('admin.brands_edit.active_status') }}</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $brand?->is_active ?? true) ? 'checked' : '' }} style="width: 40px; height: 20px;">
            <label class="form-check-label fw-medium text-dark ms-2" for="is_active">
                {{ __('admin.brands_edit.active_label') }}
            </label>
        </div>
    </div>
</div>

<hr class="my-4" style="opacity: 0.1;">

<h6 class="fw-bold mb-3 text-dark">{{ __('admin.brands_edit.social_links') }}</h6>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="instagram_link" class="form-label text-muted small"><i class="fab fa-instagram me-1 text-danger"></i> Instagram</label>
        <input type="url" name="instagram_link" id="instagram_link" class="form-control" placeholder="https://instagram.com/username" value="{{ old('instagram_link', $brand?->instagram_link) }}" style="border-radius: 8px;">
    </div>
    <div class="col-md-6">
        <label for="tiktok_link" class="form-label text-muted small"><i class="fab fa-tiktok me-1 text-dark"></i> TikTok</label>
        <input type="url" name="tiktok_link" id="tiktok_link" class="form-control" placeholder="https://tiktok.com/@username" value="{{ old('tiktok_link', $brand?->tiktok_link) }}" style="border-radius: 8px;">
    </div>
    <div class="col-md-6">
        <label for="snapchat_link" class="form-label text-muted small"><i class="fab fa-snapchat-ghost me-1 text-warning"></i> Snapchat</label>
        <input type="url" name="snapchat_link" id="snapchat_link" class="form-control" placeholder="https://snapchat.com/add/username" value="{{ old('snapchat_link', $brand?->snapchat_link) }}" style="border-radius: 8px;">
    </div>
    <div class="col-md-6">
        <label for="twitter_link" class="form-label text-muted small"><i class="fab fa-twitter me-1 text-primary"></i> Twitter / X</label>
        <input type="url" name="twitter_link" id="twitter_link" class="form-control" placeholder="https://twitter.com/username" value="{{ old('twitter_link', $brand?->twitter_link) }}" style="border-radius: 8px;">
    </div>
</div>

<hr class="my-4" style="opacity: 0.1;">

<h6 class="fw-bold mb-3 text-dark">{{ __('admin.brands_edit.external_store') }}</h6>
<div class="mb-3">
    <label for="store_link" class="form-label text-muted small"><i class="fas fa-link me-1 text-success"></i> {{ __('admin.brands_edit.store_url') }}</label>
    <input type="url" name="store_link" id="store_link" class="form-control" placeholder="{{ __('admin.brands_edit.store_url_placeholder') }}" value="{{ old('store_link', $brand?->store_link) }}" style="border-radius: 8px;">
</div>
<div class="mb-4">
    <label for="store_link_description" class="form-label text-muted small">{{ __('admin.brands_edit.store_button_desc') }}</label>
    <textarea name="store_link_description" id="store_link_description" rows="2" class="form-control" placeholder="{{ __('admin.brands_edit.store_button_placeholder') }}" style="border-radius: 8px;">{{ old('store_link_description', $brand?->store_link_description) }}</textarea>
</div>

@include('admin.partials.tags-input', ['selectedTags' => $selectedTags ?? '', 'tagSuggestions' => $tagSuggestions ?? []])
