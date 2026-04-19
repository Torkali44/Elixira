@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">Edit: {{ $section->admin_label ?? $section->slug }}</h2>
    <a href="{{ route('admin.home-sections.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <p class="small text-muted">Slug <code>{{ $section->slug }}</code> is used by the theme. Template controls how this block renders on the homepage.</p>
        <form action="{{ route('admin.home-sections.update', $section) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Admin label</label>
                    <input type="text" name="admin_label" class="form-control" value="{{ old('admin_label', $section->admin_label) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Template <span class="text-danger">*</span></label>
                    <select name="template" class="form-select" required>
                        @foreach(['hero','heading','featured_products','split','newsletter','icon_cards','paragraph','cta'] as $t)
                            <option value="{{ $t }}" {{ old('template', $section->template) === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $section->title) }}" maxlength="2000">
            </div>
            <div class="mb-3">
                <label class="form-label">Subtitle</label>
                <textarea name="subtitle" class="form-control" rows="2" maxlength="5000">{{ old('subtitle', $section->subtitle) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Body</label>
                <textarea name="body" class="form-control font-monospace small" rows="8" maxlength="20000">{{ old('body', $section->body) }}</textarea>
                <small class="text-muted">For template <strong>icon_cards</strong>, use JSON array: <code>[{"icon":"fa-leaf","title":"...","text":"..."},...]</code></small>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Button label</label>
                    <input type="text" name="button_label" class="form-control" value="{{ old('button_label', $section->button_label) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Button URL (path, e.g. /menu)</label>
                    <input type="text" name="button_url" class="form-control" value="{{ old('button_url', $section->button_url) }}" placeholder="/menu">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sort order <span class="text-danger">*</span></label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $section->sort_order) }}" min="0" required>
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <div class="form-check mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Visible on homepage</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Image</label>
                @if($section->image)
                    <div class="mb-2"><img src="{{ asset('storage/'.$section->image) }}" alt="" class="rounded border" style="max-height:160px;"></div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Save section</button>
        </form>
    </div>
</div>
@endsection
