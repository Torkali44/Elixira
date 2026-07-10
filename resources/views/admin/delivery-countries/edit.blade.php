@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">{{ __('admin.delivery_zones.edit_country') }}: {{ $deliveryCountry->name_en }}</h2>
    <a href="{{ route('admin.delivery-countries.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> {{ __('admin.delivery_zones.back') }}</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.delivery-countries.update', $deliveryCountry) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.delivery-countries._form', ['country' => $deliveryCountry])
            <button type="submit" class="btn btn-primary">{{ __('admin.common.save') }}</button>
        </form>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">{{ __('admin.delivery_zones.cities_title') }}</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">{{ __('admin.delivery_zones.city_name') }}</th>
                        <th>{{ __('admin.delivery_zones.delivery_fee') }}</th>
                        <th>{{ __('admin.delivery_zones.sort_order') }}</th>
                        <th>{{ __('admin.delivery_zones.col_status') }}</th>
                        <th class="pe-4 text-end">{{ __('admin.delivery_zones.col_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveryCountry->cities as $city)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $city->name_en }}</div>
                                <div class="text-muted small" dir="rtl">{{ $city->name_ar }}</div>
                            </td>
                            <td>{{ number_format($city->delivery_fee, 2) }} {{ $deliveryCountry->local_currency_label }}</td>
                            <td>{{ $city->sort_order }}</td>
                            <td>
                                @if($city->is_active)
                                    <span class="badge bg-success">{{ __('admin.delivery_zones.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('admin.delivery_zones.inactive') }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#edit-city-{{ $city->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.delivery-countries.cities.destroy', [$deliveryCountry, $city]) }}" method="POST" class="d-inline" onsubmit="return confirm(@json(__('admin.delivery_zones.delete_city_confirm')));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <tr class="collapse" id="edit-city-{{ $city->id }}">
                            <td colspan="5" class="bg-light">
                                <form action="{{ route('admin.delivery-countries.cities.update', [$deliveryCountry, $city]) }}" method="POST" class="p-3">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">{{ __('admin.delivery_zones.name_en') }}</label>
                                            <input type="text" name="name_en" class="form-control" value="{{ $city->name_en }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">{{ __('admin.delivery_zones.name_ar') }}</label>
                                            <input type="text" name="name_ar" class="form-control" value="{{ $city->name_ar }}" dir="rtl" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">{{ __('admin.delivery_zones.delivery_fee') }}</label>
                                            <input type="number" step="0.01" min="0" name="delivery_fee" class="form-control" value="{{ $city->delivery_fee }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">{{ __('admin.delivery_zones.sort_order') }}</label>
                                            <input type="number" name="sort_order" class="form-control" value="{{ $city->sort_order }}">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <div class="form-check mb-2">
                                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="city-active-{{ $city->id }}" @checked($city->is_active)>
                                                <label class="form-check-label" for="city-active-{{ $city->id }}">{{ __('admin.delivery_zones.active') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-sm btn-primary">{{ __('admin.common.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">{{ __('admin.delivery_zones.no_cities') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">{{ __('admin.delivery_zones.add_city') }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.delivery-countries.cities.store', $deliveryCountry) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">{{ __('admin.delivery_zones.name_en') }}</label>
                    <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('admin.delivery_zones.name_ar') }}</label>
                    <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar') }}" dir="rtl" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('admin.delivery_zones.delivery_fee') }}</label>
                    <input type="number" step="0.01" min="0" name="delivery_fee" class="form-control" value="{{ old('delivery_fee', 0) }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('admin.delivery_zones.sort_order') }}</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="new-city-active" checked>
                        <label class="form-check-label" for="new-city-active">{{ __('admin.delivery_zones.active') }}</label>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i> {{ __('admin.delivery_zones.add_city') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
