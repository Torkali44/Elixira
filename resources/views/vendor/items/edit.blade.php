@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">{{ __('vendor.items_page.edit_product') }}</h3>
        <a href="{{ route('vendor.items.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> {{ __('vendor.items_page.back') }}
        </a>
    </div>

    @if($item->status == 'rejected')
        <div class="alert alert-danger mb-4">
            <strong><i class="fas fa-exclamation-triangle me-2"></i> {{ __('vendor.items_page.rejected') }}</strong>
            <p class="mb-0 mt-1">{{ $item->rejection_reason }}</p>
        </div>
    @elseif($item->status == 'approved')
        <div class="alert alert-warning mb-4">
            <i class="fas fa-info-circle me-2"></i> {{ __('vendor.items_page.edit_warning') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('vendor.items.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-8">

                        {{-- Bilingual Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('vendor.items_page.product_name') }} <span class="text-danger">*</span></label>
                            <ul class="nav nav-tabs mb-2" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#name-en-tab">English</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#name-ar-tab">العربية</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="name-en-tab">
                                    <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $item->name_en ?: $item->name) }}" placeholder="Product name in English" required>
                                    @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="tab-pane fade" id="name-ar-tab">
                                    <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $item->name_ar) }}" dir="rtl" placeholder="اسم المنتج بالعربي">
                                    @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('vendor.items_page.category') }} <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">{{ __('vendor.items_page.select_category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->local_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">{{ __('vendor.items_page.price') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $item->price) }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">{{ __('vendor.items_page.stock') }} <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock', $item->stock) }}" required>
                            </div>
                        </div>

                        @include('partials.admin.item-country-prices', ['item' => $item])

                        {{-- Bilingual Short Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('vendor.items_page.short_description') }}</label>
                            <ul class="nav nav-tabs mb-2" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#desc-en-tab">English</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#desc-ar-tab">العربية</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="desc-en-tab">
                                    <textarea name="description_en" class="form-control" rows="2" placeholder="Short description in English">{{ old('description_en', $item->description_en ?: $item->description) }}</textarea>
                                </div>
                                <div class="tab-pane fade" id="desc-ar-tab">
                                    <textarea name="description_ar" class="form-control" rows="2" dir="rtl" placeholder="وصف مختصر بالعربي">{{ old('description_ar', $item->description_ar) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('vendor.items_page.long_description') }}</label>
                            <textarea name="long_description" class="form-control" rows="5">{{ old('long_description', $item->long_description) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">{{ __('vendor.items_page.main_image') }}</label>
                            @if($item->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $item->image) }}" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">{{ __('vendor.items_page.keep_image_hint') }}</small>
                        </div>

                        <div class="mb-3 border-top pt-3 mt-3">
                            <label class="form-label">{{ __('vendor.items_page.add_gallery') }}</label>
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        </div>

                        @if($item->images->count() > 0)
                            <div class="mb-3">
                                <label class="form-label d-block">{{ __('vendor.items_page.current_gallery') }}</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($item->images as $img)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $img->image) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted d-block mt-1">{{ __('vendor.items_page.gallery_contact_hint') }}</small>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-end mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> {{ __('vendor.items_page.update_product') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
