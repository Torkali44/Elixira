@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">{{ __('admin.categories_page.create_category') }}</h2>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> {{ __('admin.categories_page.back') }}</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Bilingual Name --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">{{ __('admin.categories_page.name') }} <span class="text-danger">*</span></label>
                <ul class="nav nav-tabs mb-2" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#name-en-tab">English</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#name-ar-tab">العربية</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="name-en-tab">
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en') }}" maxlength="255" placeholder="Category name in English" required>
                        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="tab-pane fade" id="name-ar-tab">
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" maxlength="255" dir="rtl" placeholder="اسم القسم بالعربي" required>
                        @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Bilingual Description --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">{{ __('admin.categories_page.description') }}</label>
                <ul class="nav nav-tabs mb-2" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#desc-en-tab">English</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#desc-ar-tab">العربية</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="desc-en-tab">
                        <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3" placeholder="Description in English" required>{{ old('description_en') }}</textarea>
                        @error('description_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="tab-pane fade" id="desc-ar-tab">
                        <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3" dir="rtl" placeholder="الوصف بالعربي" required>{{ old('description_ar') }}</textarea>
                        @error('description_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">{{ __('admin.categories_page.image') }}</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">{{ __('admin.categories_page.max_size') }}</small>
            </div>

            <button type="submit" class="btn btn-primary px-4">{{ __('admin.categories_page.save') }}</button>
        </form>
    </div>
</div>
@endsection
