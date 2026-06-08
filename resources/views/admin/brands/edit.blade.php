@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Edit Brand Profile</h2>
            <p class="text-muted mb-0">Update brand identity, active status, logo, social links and storefront presentation.</p>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2" style="border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Brands
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3 ps-4">
                    <h5 class="card-title fw-bold m-0 text-dark">Brand Settings</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Brand Logo Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Brand Logo</label>
                            <div class="d-flex align-items-center gap-4 p-3 bg-light" style="border-radius: 12px; border: 1px dashed rgba(0,0,0,0.1);">
                                <div class="logo-preview-wrapper" style="position: relative;">
                                    @if($brand->logo)
                                        <img id="logoPreview" src="{{ asset('storage/' . $brand->logo) }}" class="rounded-circle shadow-sm border border-2 border-white" style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div id="logoPlaceholder" class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 80px; height: 80px; font-weight: bold; font-size: 1.5rem;">
                                            {{ strtoupper(substr($brand->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="logo" id="logoInput" class="form-control form-control-sm mb-2" accept="image/*">
                                    <small class="text-muted d-block">Recommended: Square format (1:1), PNG/JPG/SVG, max 2MB.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Brand Name & Description -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold text-muted small text-uppercase">Brand Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $brand->name) }}" required style="border-radius: 8px;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold text-muted small text-uppercase">Description</label>
                            <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Describe this brand and products to customers..." style="border-radius: 8px;">{{ old('description', $brand->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status & Service Countries -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase d-block">Service Countries</label>
                                <div class="d-flex gap-4 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="service_countries[]" value="Saudi Arabia" id="countryKsa" 
                                            {{ in_array('Saudi Arabia', old('service_countries', $brand->service_countries ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium text-dark" for="countryKsa">
                                            Saudi Arabia 🇸🇦
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="service_countries[]" value="UAE" id="countryUae" 
                                            {{ in_array('UAE', old('service_countries', $brand->service_countries ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium text-dark" for="countryUae">
                                            UAE 🇦🇪
                                        </label>
                                    </div>
                                </div>
                                @error('service_countries')
                                    <div class="text-danger small mt-2 d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase d-block">Active Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }} style="width: 40px; height: 20px;">
                                    <label class="form-check-label fw-medium text-dark ms-2" for="is_active">
                                        Active (Show storefront and items)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="opacity: 0.1;">

                        <!-- Social Links -->
                        <h6 class="fw-bold mb-3 text-dark">Social Media Links</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="instagram_link" class="form-label text-muted small"><i class="fab fa-instagram me-1 text-danger"></i> Instagram</label>
                                <input type="url" name="instagram_link" id="instagram_link" class="form-control" placeholder="https://instagram.com/username" value="{{ old('instagram_link', $brand->instagram_link) }}" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label for="tiktok_link" class="form-label text-muted small"><i class="fab fa-tiktok me-1 text-dark"></i> TikTok</label>
                                <input type="url" name="tiktok_link" id="tiktok_link" class="form-control" placeholder="https://tiktok.com/@username" value="{{ old('tiktok_link', $brand->tiktok_link) }}" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label for="snapchat_link" class="form-label text-muted small"><i class="fab fa-snapchat-ghost me-1 text-warning"></i> Snapchat</label>
                                <input type="url" name="snapchat_link" id="snapchat_link" class="form-control" placeholder="https://snapchat.com/add/username" value="{{ old('snapchat_link', $brand->snapchat_link) }}" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label for="twitter_link" class="form-label text-muted small"><i class="fab fa-twitter me-1 text-primary"></i> Twitter / X</label>
                                <input type="url" name="twitter_link" id="twitter_link" class="form-control" placeholder="https://twitter.com/username" value="{{ old('twitter_link', $brand->twitter_link) }}" style="border-radius: 8px;">
                            </div>
                        </div>

                        <hr class="my-4" style="opacity: 0.1;">

                        <!-- External Store Integration -->
                        <h6 class="fw-bold mb-3 text-dark">External Store Integration</h6>
                        <div class="mb-3">
                            <label for="store_link" class="form-label text-muted small"><i class="fas fa-link me-1 text-success"></i> External Store URL</label>
                            <input type="url" name="store_link" id="store_link" class="form-control" placeholder="https://myexternalstore.com" value="{{ old('store_link', $brand->store_link) }}" style="border-radius: 8px;">
                        </div>
                        <div class="mb-4">
                            <label for="store_link_description" class="form-label text-muted small">External Store Button Description</label>
                            <textarea name="store_link_description" id="store_link_description" rows="2" class="form-control" placeholder="Quick instructions or a CTA for the external store link..." style="border-radius: 8px;">{{ old('store_link_description', $brand->store_link_description) }}</textarea>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2 fw-bold" style="border-radius: 8px;">
                                <i class="fas fa-save me-2"></i> Update Brand & Sync Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Brand Info Card Preview -->
            <div class="card border-0 shadow-sm mb-4 text-center p-4" style="border-radius: 16px;">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-bold mb-3">Storefront Card Preview</h6>
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
                        <span class="badge bg-success rounded-pill mt-2 py-1 px-3" style="font-size: 0.75rem;">Approved Vendor</span>
                    </div>

                    <p class="text-muted small px-2" style="max-height: 80px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                        {{ $brand->description ?? 'No brand description provided yet.' }}
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
                            <span class="text-muted">Service Regions:</span>
                            <span class="fw-bold">
                                @if(empty($brand->service_countries))
                                    None
                                @else
                                    {{ implode(', ', $brand->service_countries) }}
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Products:</span>
                            <span class="fw-bold text-primary">{{ $brand->items()->count() }} Products</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Live logo preview
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
                        // If placeholder exists, replace it
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
