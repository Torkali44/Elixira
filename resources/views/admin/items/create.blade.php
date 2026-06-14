@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">{{ __('admin.items_page.add_product') }}</h2>
    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> {{ __('admin.items_page.back') }}</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Bilingual Name --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">{{ __('admin.items_page.product_name') }} <span class="text-danger">*</span></label>
                <ul class="nav nav-tabs mb-2" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#name-en-tab">English</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#name-ar-tab">العربية</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="name-en-tab">
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en') }}" maxlength="255" placeholder="Product name in English" required>
                        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="tab-pane fade" id="name-ar-tab">
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" maxlength="255" dir="rtl" placeholder="اسم المنتج بالعربي" required>
                        @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">{{ __('admin.items_page.category') }} <span class="text-danger">*</span></label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">{{ __('admin.items_page.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->local_name }}</option>
                        @endforeach
                    </select>
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

            {{-- Bilingual Description --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">{{ __('admin.items_page.description') }}</label>
                <ul class="nav nav-tabs mb-2" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#desc-en-tab">English</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#desc-ar-tab">العربية</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="desc-en-tab">
                        <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3" placeholder="Short description in English" required>{{ old('description_en') }}</textarea>
                        @error('description_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="tab-pane fade" id="desc-ar-tab">
                        <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3" dir="rtl" placeholder="وصف مختصر بالعربي" required>{{ old('description_ar') }}</textarea>
                        @error('description_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">{{ __('admin.items_page.base_price') }} <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                    <small class="text-muted">{{ __('admin.items_page.base_price_hint') }}</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">{{ __('admin.items_page.stock') }} <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                </div>
            </div>

            @include('partials.admin.item-country-prices', ['item' => null])

            <div class="mb-3">
                <label for="long_description" class="form-label">{{ __('admin.items_page.long_description') }}</label>
                <textarea class="form-control" id="long_description" name="long_description" rows="10" placeholder="Write detailed information about the product here...">{{ old('long_description') }}</textarea>
                <small class="text-muted">{{ __('admin.items_page.long_description_hint') }}</small>
            </div>

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

            <button type="submit" class="btn btn-primary px-4">{{ __('admin.items_page.save_product') }}</button>
        </form>
    </div>
</div>
@endsection
