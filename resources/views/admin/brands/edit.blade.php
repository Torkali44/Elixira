@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold text-dark">{{ __('admin.brands_edit.title') }}</h2>
            <p class="text-muted mb-0">{{ __('admin.brands_edit.subtitle') }}</p>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2" style="border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> {{ __('admin.brands_edit.back') }}
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3 ps-4">
                    <h5 class="card-title fw-bold m-0 text-dark">{{ __('admin.brands_edit.settings') }}</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('partials.admin.brand-form-fields', [
                            'brand' => $brand,
                            'vendorProfiles' => $vendorProfiles ?? collect(),
                            'selectedTags' => $selectedTags ?? '',
                            'tagSuggestions' => $tagSuggestions ?? [],
                        ])

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2 fw-bold" style="border-radius: 8px;">
                                <i class="fas fa-save me-2"></i> {{ __('admin.brands_edit.update_btn') }}
                            </button>
                        </div>
                    </form>

                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="mt-3" onsubmit="return confirm(@json(__('admin.brands_page.confirm_delete')));">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 py-2" @disabled($brand->items()->count() > 0)>
                            <i class="fas fa-trash me-2"></i> {{ __('admin.brands_page.delete') }}
                        </button>
                    </form>
                    @if($brand->items()->count() > 0)
                        <small class="text-muted d-block mt-2">{{ __('admin.brands_page.cannot_delete_with_products') }}</small>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 text-center p-4" style="border-radius: 16px;">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-bold mb-3">{{ __('admin.brands_edit.preview_title') }}</h6>
                    <div class="d-flex flex-column align-items-center mb-3">
                        <div class="mb-3">
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" class="rounded-circle shadow border border-2 border-white" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto shadow" style="width: 100px; height: 100px; font-weight: bold; font-size: 2rem;">
                                    {{ strtoupper(substr($brand->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <h4 class="fw-bold m-0 text-dark">{{ $brand->name }}</h4>
                        <span class="badge bg-success rounded-pill mt-2 py-1 px-3" style="font-size: 0.75rem;">{{ __('admin.brands_edit.approved_vendor') }}</span>
                    </div>

                    <p class="text-muted small px-2" style="max-height: 80px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                        {{ $brand->description ?? __('admin.brands_edit.no_description') }}
                    </p>

                    <div class="d-flex justify-content-center gap-3 my-3">
                        @if($brand->instagram_link)
                            <a href="{{ $brand->instagram_link }}" target="_blank" class="text-danger"><i class="fab fa-instagram fs-4"></i></a>
                        @endif
                        @if($brand->tiktok_link)
                            <a href="{{ $brand->tiktok_link }}" target="_blank" class="text-dark"><i class="fab fa-tiktok fs-4"></i></a>
                        @endif
                        @if($brand->snapchat_link)
                            <a href="{{ $brand->snapchat_link }}" target="_blank" class="text-warning"><i class="fab fa-snapchat-ghost fs-4"></i></a>
                        @endif
                        @if($brand->twitter_link)
                            <a href="{{ $brand->twitter_link }}" target="_blank" class="text-primary"><i class="fab fa-twitter fs-4"></i></a>
                        @endif
                    </div>

                    <div class="border-top pt-3 mt-3 text-start small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('admin.brands_edit.service_regions') }}</span>
                            <span class="fw-bold">
                                @if(empty($brand->service_countries))
                                    {{ __('admin.brands_edit.none') }}
                                @else
                                    {{ implode(', ', $brand->service_countries) }}
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">{{ __('admin.brands_edit.total_products') }}</span>
                            <span class="fw-bold text-primary">{{ __('admin.brands_edit.products_count', ['count' => $brand->items()->count()]) }}</span>
                        </div>
                    </div>
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
        logoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    if (logoPreview) {
                        logoPreview.setAttribute('src', this.result);
                    } else {
                        const placeholder = document.getElementById('logoPlaceholder');
                        if (placeholder) {
                            const img = document.createElement('img');
                            img.setAttribute('id', 'logoPreview');
                            img.setAttribute('src', this.result);
                            img.setAttribute('class', 'rounded-circle shadow-sm border border-2 border-white');
                            img.setAttribute('style', 'width: 80px; height: 80px; object-fit: cover;');
                            placeholder.parentNode.replaceChild(img, placeholder);
                        }
                    }
                });
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush
@endsection
