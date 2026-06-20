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
            <form action="{{ route('vendor.items.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-8">

                        @include('partials.admin.item-bilingual-fields', ['item' => null])

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
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('vendor.items_page.stock') }} <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}">
                                @error('stock')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('admin.items_page.reward_points') }}</label>
                                <input type="number" min="0" name="reward_points" class="form-control" value="{{ old('reward_points', 0) }}">
                                <small class="text-muted">{{ __('admin.items_page.reward_points_hint') }}</small>
                            </div>
                        </div>

                        @include('partials.admin.item-country-prices', ['item' => null])

                        @include('partials.admin.bilingual-long-description', ['model' => null, 'prefix' => 'vendor-item-long-desc'])
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
