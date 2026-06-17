@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">{{ __('vendor.items_page.add_product') }}</h3>
        <a href="{{ route('vendor.items.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> {{ __('vendor.items_page.back') }}
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('vendor.items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                                    <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en') }}" placeholder="Product name in English" required>
                                    @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="tab-pane fade" id="name-ar-tab">
                                    <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar') }}" dir="rtl" placeholder="اسم المنتج بالعربي">
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
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->local_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">{{ __('vendor.items_page.price') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">{{ __('vendor.items_page.stock') }} <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">{{ __('admin.items_page.reward_points') }}</label>
                                <input type="number" min="0" name="reward_points" class="form-control" value="{{ old('reward_points', 0) }}">
                                <small class="text-muted">{{ __('admin.items_page.reward_points_hint') }}</small>
                            </div>
                        </div>

                        @include('partials.admin.item-country-prices', ['item' => null])

                        {{-- Bilingual Short Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('vendor.items_page.short_description') }}</label>
                            <ul class="nav nav-tabs mb-2" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#desc-en-tab">English</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#desc-ar-tab">العربية</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="desc-en-tab">
                                    <textarea name="description_en" class="form-control" rows="2" placeholder="Short description in English">{{ old('description_en') }}</textarea>
                                </div>
                                <div class="tab-pane fade" id="desc-ar-tab">
                                    <textarea name="description_ar" class="form-control" rows="2" dir="rtl" placeholder="وصف مختصر بالعربي">{{ old('description_ar') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('vendor.items_page.long_description') }}</label>
                            <textarea name="long_description" class="form-control" rows="5">{{ old('long_description') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">{{ __('vendor.items_page.main_image') }} <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('vendor.items_page.gallery_images') }}</label>
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                            <small class="text-muted">{{ __('vendor.items_page.gallery_hint') }}</small>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> {{ __('vendor.items_page.save_product') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
