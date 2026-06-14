<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">{{ __('admin.avatar_options_admin.char_name') }}</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $avatarOption?->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('admin.avatar_options_admin.gender_category') }}</label>
        <select name="gender" class="form-select" required>
            <option value="male" @selected(old('gender', $avatarOption?->gender) === 'male')>{{ __('admin.avatar_options_admin.male') }}</option>
            <option value="female" @selected(old('gender', $avatarOption?->gender) === 'female')>{{ __('admin.avatar_options_admin.female') }}</option>
            <option value="both" @selected(old('gender', $avatarOption?->gender) === 'both')>{{ __('admin.avatar_options_admin.both') }}</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('admin.avatar_options_admin.sort_order') }}</label>
        <input type="number" name="sort_order" class="form-control" min="1" value="{{ old('sort_order', $avatarOption?->sort_order ?? 1) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('admin.avatar_options_admin.avatar_image') }}</label>
        <input type="file" name="image" class="form-control" accept="image/*" {{ $avatarOption ? '' : 'required' }}>
        @if($avatarOption)
            <div class="form-text">{{ __('admin.avatar_options_admin.keep_image') }}</div>
        @endif
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $avatarOption?->is_active ?? true))>
            <label class="form-check-label" for="is_active">{{ __('admin.avatar_options_admin.active_users') }}</label>
        </div>
    </div>
</div>
