<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">Character Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $avatarOption?->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" min="1" value="{{ old('sort_order', $avatarOption?->sort_order ?? 1) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Image URL</label>
        <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $avatarOption?->image_url) }}" required>
    </div>
    <div class="col-12">
        <label class="form-label">Optional Link</label>
        <input type="url" name="link_url" class="form-control" value="{{ old('link_url', $avatarOption?->link_url) }}">
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $avatarOption?->is_active ?? true))>
            <label class="form-check-label" for="is_active">Active for users</label>
        </div>
    </div>
</div>
