@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Add New Product</h3>
        <a href="{{ route('vendor.items.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Products
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('vendor.items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Price *</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Stock *</label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Short Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Long Description</label>
                            <textarea name="long_description" class="form-control" rows="5">{{ old('long_description') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Main Image *</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gallery Images (Optional)</label>
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                            <small class="text-muted">You can select multiple images.</small>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
