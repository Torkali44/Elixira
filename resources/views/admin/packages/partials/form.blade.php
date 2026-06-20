@php
    $selectedItemIds = $package?->items->pluck('id')->all() ?? [];
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.packages_page.name_en') }} *</label>
        <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $package?->name_en) }}">
        @error('name_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.packages_page.name_ar') }} *</label>
        <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $package?->name_ar) }}" dir="rtl">
        @error('name_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">{{ __('admin.packages_page.description_en') }} *</label>
    <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="2">{{ old('description_en', $package?->description_en) }}</textarea>
    @error('description_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">{{ __('admin.packages_page.description_ar') }} *</label>
    <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror" rows="2" dir="rtl">{{ old('description_ar', $package?->description_ar) }}</textarea>
    @error('description_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.packages_page.stock') }} *</label>
        <input type="number" min="0" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $package?->stock ?? 0) }}">
        @error('stock')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.packages_page.reward_points') }}</label>
        <input type="number" min="0" name="reward_points" class="form-control @error('reward_points') is-invalid @enderror" value="{{ old('reward_points', $package?->reward_points ?? 0) }}">
        @error('reward_points')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
</div>

@include('partials.admin.package-country-prices', ['package' => $package])

@include('partials.admin.bilingual-long-description', [
    'model' => $package,
    'prefix' => 'package-long-desc',
    'label' => __('admin.packages_page.long_description'),
    'hintKey' => 'admin.packages_page.long_description_hint',
])

<div class="mb-3">
    <label class="form-label">{{ __('admin.packages_page.package_image') }}</label>
    @if($package?->image)
        <div class="mb-2"><img src="{{ asset('storage/'.$package->image) }}" alt="" style="max-height:120px;" class="rounded border"></div>
    @endif
    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
    @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">{{ __('admin.packages_page.included_products') }} @if($vendorMode ?? false)<span class="text-danger">*</span>@endif</label>
    <div class="border rounded p-3" style="max-height: 280px; overflow-y: auto;">
        @foreach($items as $item)
            @php
                $pivot = $package?->items->firstWhere('id', $item->id)?->pivot;
            @endphp
            <div class="d-flex align-items-center gap-2 mb-2">
                <input type="checkbox" name="package_items[{{ $item->id }}][selected]" value="1" id="pkg_item_{{ $item->id }}"
                    @checked(old("package_items.{$item->id}.selected", in_array($item->id, $selectedItemIds, true)))>
                <label for="pkg_item_{{ $item->id }}" class="mb-0 flex-grow-1">{{ $item->local_name }}</label>
                <input type="number" min="1" name="package_items[{{ $item->id }}][quantity]" class="form-control form-control-sm" style="width: 80px;"
                    value="{{ old("package_items.{$item->id}.quantity", $pivot?->quantity ?? 1) }}">
            </div>
        @endforeach
    </div>
    @error('package_items')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>

@if(!($vendorMode ?? false))
<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $package?->is_featured))>
    <label class="form-check-label" for="is_featured">{{ __('admin.packages_page.featured') }}</label>
</div>
<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $package?->is_active ?? true))>
    <label class="form-check-label" for="is_active">{{ __('admin.packages_page.visible_in_shop') }}</label>
</div>
@else
    <div class="alert alert-info py-2 small mb-3">{{ __('admin.packages_page.vendor_approval_hint') }}</div>
@endif

@include('admin.partials.tags-input', ['selectedTags' => $selectedTags ?? '', 'tagSuggestions' => $tagSuggestions ?? []])
