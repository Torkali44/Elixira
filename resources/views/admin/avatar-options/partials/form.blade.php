<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">Character Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $avatarOption?->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Gender Category</label>
        <select name="gender" class="form-select" required>
            <option value="male" @selected(old('gender', $avatarOption?->gender) === 'male')>Male</option>
            <option value="female" @selected(old('gender', $avatarOption?->gender) === 'female')>Female</option>
            <option value="both" @selected(old('gender', $avatarOption?->gender) === 'both')>Both / Neutral</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" min="1" value="{{ old('sort_order', $avatarOption?->sort_order ?? 1) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Avatar Image</label>
        <input type="file" name="image" class="form-control" accept="image/*" {{ $avatarOption ? '' : 'required' }}>
        @if($avatarOption)
            <div class="form-text">Leave empty to keep current image.</div>
        @endif
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $avatarOption?->is_active ?? true))>
            <label class="form-check-label" for="is_active">Active for users</label>
        </div>
    </div>
</div>
