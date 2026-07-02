@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold text-dark">{{ __('admin.brands_page.create_title') }}</h2>
            <p class="text-muted mb-0">{{ __('admin.brands_page.create_subtitle') }}</p>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2" style="border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> {{ __('admin.brands_edit.back') }}
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3 ps-4">
                    <h5 class="card-title fw-bold m-0 text-dark">{{ __('admin.brands_page.create_title') }}</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('partials.admin.brand-form-fields', [
                            'brand' => null,
                            'vendorProfiles' => $vendorProfiles,
                            'selectedTags' => $selectedTags ?? '',
                            'tagSuggestions' => $tagSuggestions ?? [],
                        ])
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2 fw-bold" style="border-radius: 8px;">
                                <i class="fas fa-save me-2"></i> {{ __('admin.brands_page.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');

    if (logoInput) {
        logoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.addEventListener('load', function () {
                if (logoPreview) {
                    logoPreview.setAttribute('src', this.result);
                    return;
                }

                const placeholder = document.getElementById('logoPlaceholder');
                if (!placeholder) {
                    return;
                }

                const img = document.createElement('img');
                img.setAttribute('id', 'logoPreview');
                img.setAttribute('src', this.result);
                img.setAttribute('class', 'rounded-circle shadow-sm border border-2 border-white');
                img.setAttribute('style', 'width: 80px; height: 80px; object-fit: cover;');
                placeholder.parentNode.replaceChild(img, placeholder);
            });
            reader.readAsDataURL(file);
        });
    }
</script>
@endpush
@endsection
