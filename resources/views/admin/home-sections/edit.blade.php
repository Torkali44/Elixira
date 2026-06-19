@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-0">{{ __('admin.home_sections_page.hero_editor_title') }}</h2>
        <p class="text-muted small mb-0">{{ __('admin.home_sections_page.hero_editor_subtitle') }}</p>
    </div>
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
        <i class="fas fa-external-link-alt me-2"></i>{{ __('admin.nav.view_storefront') }}
    </a>
</div>

<div class="alert alert-info">{{ __('admin.home_sections_page.hero_help') }}</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.home-sections.update', $section) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">{{ __('admin.home_sections_page.title_field') }}</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $section->title) }}" maxlength="2000" required>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('admin.home_sections_page.subtitle_field') }}</label>
                <textarea name="subtitle" class="form-control" rows="3" maxlength="5000" required>{{ old('subtitle', $section->subtitle) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.home_sections_page.button_label') }}</label>
                    <input type="text" name="button_label" class="form-control" value="{{ old('button_label', $section->button_label) }}" placeholder="{{ __('home.enter_store') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.home_sections_page.button_url') }}</label>
                    <input type="text" name="button_url" class="form-control" value="{{ old('button_url', $section->button_url) }}" placeholder="/menu">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('admin.home_sections_page.secondary_button_json') }}</label>
                <textarea name="body" class="form-control font-monospace small" rows="3">{{ old('body', $section->body) }}</textarea>
                <small class="text-muted">{{ __('admin.home_sections_page.body_hint_hero') }}</small>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('admin.home_sections_page.image') }}</label>
                @if($section->image)
                    <div class="mb-2"><img src="{{ asset('storage/'.$section->image) }}" alt="" class="rounded border" style="max-height:200px;"></div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">{{ __('admin.home_sections_page.save') }}</button>
        </form>
    </div>
</div>
@endsection
