@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">Edit Product: {{ $item->name }}</h2>
    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $item->name) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select category...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand', $item->brand) }}" placeholder="e.g. DXN">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="points" class="form-label">Points</label>
                    <input type="number" min="0" class="form-control" id="points" name="points" value="{{ old('points', $item->points) }}">
                    <small class="text-muted">Points auto-increment by 1 on each purchase.</small>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $item->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price (﷼) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price', $item->price) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">Stock / Inventory <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control" id="stock" name="stock" value="{{ old('stock', $item->stock) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="long_description" class="form-label">Product Blog / Long Description (Attractive details)</label>
                <textarea class="form-control" id="long_description" name="long_description" rows="10" placeholder="Write detailed information about the product here...">{{ old('long_description', $item->long_description) }}</textarea>
                <small class="text-muted">This will be displayed as a featured article on the product page.</small>
            </div>

            <div class="mb-4">
                <label class="form-label d-block">Current Main Image</label>
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="img-thumbnail mb-2" style="max-height: 150px;">
                @endif
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <small class="text-muted">Upload a new image to replace the current one.</small>
            </div>

            <div class="mb-4">
                <label class="form-label d-block fw-bold">Additional Product Images (Gallery)</label>
                <div class="row g-2 mb-3">
                    @foreach($item->images as $img)
                        <div class="col-auto position-relative">
                            <img src="{{ asset('storage/' . $img->image) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                            {{-- Unified Delete Trigger --}}
                            <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 p-1 px-2 m-1 js-delete-gallery" data-form-id="delete-gallery-img-{{ $img->id }}" data-confirm="Remove this image?">
                                <i class="fas fa-times" style="font-size: 0.7rem;"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                <small class="text-muted">You can select multiple images at once to upload to the gallery.</small>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_featured">Featured on homepage</label>
            </div>

            <button type="submit" class="btn btn-primary px-4">Update Product</button>
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
