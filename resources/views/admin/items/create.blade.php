@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">{{ __('admin.items_page.add_product') }}</h2>
    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> {{ __('admin.items_page.back') }}</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            @include('partials.admin.item-bilingual-fields', ['item' => null])

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">{{ __('admin.items_page.category') }} <span class="text-danger">*</span></label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                        <option value="">{{ __('admin.items_page.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->local_name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="brand_id" class="form-label">{{ __('admin.items_page.brand_vendor') }}</label>
                    <select class="form-select" id="brand_id" name="brand_id">
                        <option value="">— {{ __('admin.items_page.no_brand') }} —</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->id }}" {{ old('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="brand" class="form-label">{{ __('admin.items_page.brand_text') }}</label>
                    <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand') }}" placeholder="e.g. DXN">
                    <small class="text-muted">{{ __('admin.items_page.brand_fallback') }}</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="reward_points" class="form-label">{{ __('admin.items_page.reward_points') }}</label>
                    <input type="number" min="0" class="form-control @error('reward_points') is-invalid @enderror" id="reward_points" name="reward_points" value="{{ old('reward_points', 0) }}">
                    <small class="text-muted">{{ __('admin.items_page.reward_points_hint') }}</small>
                    @error('reward_points')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">{{ __('admin.items_page.stock') }} <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}">
                    @error('stock')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            @include('partials.admin.item-country-prices', ['item' => null])

            @include('partials.admin.bilingual-long-description', ['model' => null, 'prefix' => 'item-long-desc'])

            <div class="mb-3">
                <label for="image" class="form-label">{{ __('admin.items_page.main_image') }}</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="images" class="form-label">{{ __('admin.items_page.gallery_images') }}</label>
                <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                <small class="text-muted">{{ __('admin.items_page.gallery_hint') }}</small>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_featured">{{ __('admin.items_page.featured') }}</label>
            </div>

            @include('admin.partials.tags-input', ['selectedTags' => $selectedTags ?? '', 'tagSuggestions' => $tagSuggestions ?? []])

            <button type="submit" class="btn btn-primary px-4">{{ __('admin.items_page.save_product') }}</button>
        </form>
    </div>
</div>
@endsection
