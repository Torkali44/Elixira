@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('vendor.brands_page.title') }}</h2>
    <a href="{{ route('brands.index') }}" target="_blank" class="btn btn-outline-primary">
        <i class="fas fa-external-link-alt"></i> {{ __('vendor.brands_page.view_public') }}
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">{{ __('vendor.brands_page.col_brand') }}</th>
                        <th>{{ __('vendor.brands_page.col_vendor') }}</th>
                        <th>{{ __('vendor.brands_page.col_countries') }}</th>
                        <th>{{ __('vendor.brands_page.col_products') }}</th>
                        <th>{{ __('vendor.brands_page.col_status') }}</th>
                        <th>{{ __('admin.audit.last_modified') }}</th>
                        <th class="pe-4 text-end">{{ __('vendor.brands_page.col_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                @else
                                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-store text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $brand->name }}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($brand->vendorProfile && $brand->vendorProfile->user)
                                <div class="d-flex align-items-center gap-2">
                                    <x-user-avatar :user="$brand->vendorProfile->user" size="30" />
                                    <span>{{ $brand->vendorProfile->user->name }}</span>
                                </div>
                            @else
                                <span class="text-muted">{{ __('vendor.brands_page.no_vendor') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($brand->service_countries)
                                @foreach($brand->service_countries as $country)
                                    <span class="badge bg-light text-dark border">{{ $country }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary rounded-pill">{{ $brand->items_count }}</span>
                        </td>
                        <td>
                            @if($brand->is_active)
                                <span class="badge bg-success">{{ __('vendor.brands_page.active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('vendor.brands_page.inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $brand->updated_at?->format('Y-m-d H:i') ?? __('admin.audit.never') }}</small>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-outline-primary" title="{{ __('vendor.brands_page.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('brands.show', $brand->slug) }}" target="_blank" class="btn btn-outline-secondary" title="{{ __('vendor.brands_page.view_store') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">{{ __('vendor.brands_page.empty') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($brands->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $brands->links() }}
    </div>
    @endif
</div>
@endsection
