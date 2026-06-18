@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">{{ __('admin.items_page.edit_product') }}: {{ $item->local_name }}</h2>
    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> {{ __('admin.items_page.back') }}</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Bilingual Name --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">{{ __('admin.items_page.product_name') }} <span class="text-danger">*</span></label>
                <ul class="nav nav-tabs mb-2" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#name-en-tab">English</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#name-ar-tab">العربية</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="name-en-tab">
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $item->name_en ?: $item->name) }}" maxlength="255" placeholder="Product name in English" required>
                        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="tab-pane fade" id="name-ar-tab">
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar', $item->name_ar) }}" maxlength="255" dir="rtl" placeholder="اسم المنتج بالعربي" required>
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
                            <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>{{ $category->local_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="brand_id" class="form-label">{{ __('admin.items_page.brand_vendor') }}</label>
                    <select class="form-select" id="brand_id" name="brand_id">
                        <option value="">— {{ __('admin.items_page.no_brand') }} —</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->id }}" {{ old('brand_id', $item->brand_id) == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="brand" class="form-label">{{ __('admin.items_page.brand_text') }}</label>
                    <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand', $item->brand) }}" placeholder="e.g. DXN">
                    <small class="text-muted">{{ __('admin.items_page.brand_fallback') }}</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="reward_points" class="form-label">{{ __('admin.items_page.reward_points') }}</label>
                    <input type="number" min="0" class="form-control @error('reward_points') is-invalid @enderror" id="reward_points" name="reward_points" value="{{ old('reward_points', $item->reward_points) }}">
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
                        <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3" placeholder="Short description in English" required>{{ old('description_en', $item->description_en ?: $item->description) }}</textarea>
                        @error('description_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="tab-pane fade" id="desc-ar-tab">
                        <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3" dir="rtl" placeholder="وصف مختصر بالعربي" required>{{ old('description_ar', $item->description_ar) }}</textarea>
                        @error('description_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">{{ __('admin.items_page.base_price') }} <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price', $item->price) }}" required>
                    <small class="text-muted">{{ __('admin.items_page.base_price_hint') }}</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">{{ __('admin.items_page.stock') }} <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control" id="stock" name="stock" value="{{ old('stock', $item->stock) }}" required>
                </div>
            </div>

            @include('partials.admin.item-country-prices', ['item' => $item])

            <div class="mb-3">
                <label for="long_description" class="form-label">{{ __('admin.items_page.long_description') }}</label>
                <textarea class="form-control" id="long_description" name="long_description" rows="10" placeholder="Write detailed information about the product here...">{{ old('long_description', $item->long_description) }}</textarea>
                <small class="text-muted">{{ __('admin.items_page.long_description_hint') }}</small>
            </div>

            <div class="mb-4">
                <label class="form-label d-block">{{ __('admin.items_page.main_image') }}</label>
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->local_name }}" class="img-thumbnail mb-2" style="max-height: 150px;">
                @endif
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <small class="text-muted">{{ __('admin.items_page.replace_image_hint') }}</small>
            </div>

            <div class="mb-4">
                <label class="form-label d-block fw-bold">{{ __('admin.items_page.gallery_images') }}</label>
                <div class="row g-2 mb-3">
                    @foreach($item->images as $img)
                        <div class="col-auto position-relative">
                            <img src="{{ asset('storage/' . $img->image) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 p-1 px-2 m-1 js-delete-gallery" data-form-id="delete-gallery-img-{{ $img->id }}" data-confirm="Remove this image?">
                                <i class="fas fa-times" style="font-size: 0.7rem;"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                <small class="text-muted">{{ __('admin.items_page.gallery_hint') }}</small>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_featured">{{ __('admin.items_page.featured') }}</label>
            </div>

            @include('admin.partials.tags-input', ['selectedTags' => $selectedTags ?? '', 'tagSuggestions' => $tagSuggestions ?? []])

            <button type="submit" class="btn btn-primary px-4">{{ __('admin.items_page.update_product') }}</button>
        </form>
    </div>
</div>

{{-- Hidden Forms for Gallery Deletion --}}
@foreach($item->images as $img)
    <form id="delete-gallery-img-{{ $img->id }}" action="{{ route('admin.items.delete-image', $img->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endforeach

<script>
document.querySelectorAll('.js-delete-gallery').forEach((button) => {
    button.addEventListener('click', function () {
        const formId = button.dataset.formId;
        const form = document.getElementById(formId);
        if (!form) return;

        Swal.fire({
            icon: 'warning',
            title: 'Please confirm',
            text: button.dataset.confirm || 'Delete this image?',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

@endsection
