@php
    $selectedItemIds = $package?->items->pluck('id')->all() ?? [];
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.packages_page.name_en') }} *</label>
        <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $package?->name_en) }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('admin.packages_page.name_ar') }} *</label>
        <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $package?->name_ar) }}" dir="rtl" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">{{ __('admin.packages_page.description_en') }} *</label>
    <textarea name="description_en" class="form-control" rows="2" required>{{ old('description_en', $package?->description_en) }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">{{ __('admin.packages_page.description_ar') }} *</label>
    <textarea name="description_ar" class="form-control" rows="2" dir="rtl" required>{{ old('description_ar', $package?->description_ar) }}</textarea>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('admin.packages_page.stock') }} *</label>
        <input type="number" min="0" name="stock" class="form-control" value="{{ old('stock', $package?->stock ?? 0) }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('admin.packages_page.reward_points') }}</label>
        <input type="number" min="0" name="reward_points" class="form-control" value="{{ old('reward_points', $package?->reward_points ?? 0) }}">
    </div>
    <div class="col-md-4 mb-3 d-flex align-items-end">
        <p class="text-muted small mb-2">{{ __('admin.packages_page.base_price_auto_hint') }}</p>
    </div>
</div>
<input type="hidden" name="price" value="{{ old('price', $package?->price ?? 0) }}">

@include('partials.admin.package-country-prices', ['package' => $package])

<div class="mb-3">
    <label class="form-label">{{ __('admin.packages_page.long_description') }}</label>
    <textarea name="long_description" class="form-control" rows="6">{{ old('long_description', $package?->long_description) }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">{{ __('admin.packages_page.package_image') }}</label>
    @if($package?->image)
        <div class="mb-2"><img src="{{ asset('storage/'.$package->image) }}" alt="" style="max-height:120px;" class="rounded border"></div>
    @endif
    <input type="file" name="image" class="form-control" accept="image/*">
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">{{ __('admin.packages_page.included_products') }}</label>
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
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $package?->is_featured))>
    <label class="form-check-label" for="is_featured">{{ __('admin.packages_page.featured') }}</label>
</div>
<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $package?->is_active ?? true))>
    <label class="form-check-label" for="is_active">{{ __('admin.packages_page.visible_in_shop') }}</label>
</div>

@include('admin.partials.tags-input', ['selectedTags' => $selectedTags ?? '', 'tagSuggestions' => $tagSuggestions ?? []])
