@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">Add product</h2>
    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Product name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select categoryâ€¦</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand') }}" placeholder="e.g. DXN">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="points" class="form-label">Points</label>
                    <input type="number" min="0" class="form-control" id="points" name="points" value="{{ old('points', 0) }}">
                    <small class="text-muted">Points auto-increment by 1 on each purchase.</small>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price (﷼) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">Stock / Inventory <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="long_description" class="form-label">Product Blog / Long Description (Attractive details)</label>
                <textarea class="form-control" id="long_description" name="long_description" rows="10" placeholder="Write detailed information about the product here...">{{ old('long_description') }}</textarea>
                <small class="text-muted">This will be displayed as a featured article on the product page.</small>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Main Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="images" class="form-label">Additional Product Images (Gallery)</label>
                <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                <small class="text-muted">You can select multiple images to show under the main image.</small>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_featured">Featured on homepage</label>
            </div>

            <button type="submit" class="btn btn-primary px-4">Save product</button>
        </form>
    </div>
</div>
@endsection
