@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Edit Product</h3>
        <a href="{{ route('vendor.items.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Products
        </a>
    </div>

    @if($item->status == 'rejected')
        <div class="alert alert-danger mb-4">
            <strong><i class="fas fa-exclamation-triangle me-2"></i> Product Rejected</strong>
            <p class="mb-0 mt-1">{{ $item->rejection_reason }}</p>
        </div>
    @elseif($item->status == 'approved')
        <div class="alert alert-warning mb-4">
            <i class="fas fa-info-circle me-2"></i> Note: Updating this product will change its status back to <strong>Pending Approval</strong>.
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('vendor.items.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Price *</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $item->price) }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Stock *</label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock', $item->stock) }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Short Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description', $item->description) }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Long Description</label>
                            <textarea name="long_description" class="form-control" rows="5">{{ old('long_description', $item->long_description) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Main Image</label>
                            @if($item->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $item->image) }}" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image.</small>
                        </div>
                        
                        <div class="mb-3 border-top pt-3 mt-3">
                            <label class="form-label">Add Gallery Images</label>
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        </div>

                        @if($item->images->count() > 0)
                            <div class="mb-3">
                                <label class="form-label d-block">Current Gallery</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($item->images as $img)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $img->image) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted d-block mt-1">Note: Removing gallery images is not available for vendors. Please contact support.</small>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-end mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Update Product & Submit for Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
