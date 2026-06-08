@extends('layouts.framer')

@section('title', $brand->name . ' - Store Unavailable')

@section('content')
<div class="page-content">
    <div class="elx-container" style="max-width: 720px; text-align: center;">
        <div data-animate style="padding: 4rem 2rem; border-radius: 24px; background: rgba(0,0,0,0.35); border: 1px solid rgba(255, 107, 107, 0.25);">
            <div style="width: 88px; height: 88px; margin: 0 auto 1.5rem; border-radius: 50%; background: rgba(255, 107, 107, 0.12); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-store-slash" style="font-size: 2rem; color: #ff6b6b;"></i>
            </div>

            <h1 style="font-family: 'Bricolage Grotesque', sans-serif; color: #fff; margin-bottom: 1rem;">
                {{ $brand->name }} is currently inactive
            </h1>

            @if($isOwner)
                <p style="color: rgba(255,255,255,0.75); line-height: 1.8; margin-bottom: 1.5rem;">
                    Your brand storefront is hidden from customers. The admin has set this brand to <strong style="color: #ff6b6b;">Inactive</strong>,
                    so public visitors cannot see your store page or products until it is reactivated.
                </p>
                <div style="display: flex; flex-wrap: gap: 1rem; justify-content: center;">
                    <a href="{{ route('vendor.brand.edit') }}" class="elx-btn elx-btn--primary">Manage My Brand</a>
                    <a href="{{ route('vendor.dashboard') }}" class="elx-btn elx-btn--glass">Go to Dashboard</a>
                </div>
            @else
                <p style="color: rgba(255,255,255,0.75); line-height: 1.8; margin-bottom: 1.5rem;">
                    This brand has been deactivated by an administrator and is not visible to the public.
                </p>
                <a href="{{ route('admin.brands.edit', $brand) }}" class="elx-btn elx-btn--primary">Edit Brand Settings</a>
            @endif
        </div>
    </div>
</div>
@endsection
