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
            <form action="{{ route('vendor.items.update', $item) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-8">

                        @include('partials.admin.item-bilingual-fields', ['item' => $item])

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
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('vendor.items_page.stock') }} <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $item->stock) }}">
                                @error('stock')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('admin.items_page.reward_points') }}</label>
                                <input type="number" min="0" name="reward_points" class="form-control" value="{{ old('reward_points', $item->reward_points ?? 0) }}">
                            </div>
                        </div>

                        @include('partials.admin.item-country-prices', ['item' => $item])

                        @include('partials.admin.bilingual-long-description', ['model' => $item, 'prefix' => 'vendor-item-long-desc'])
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
