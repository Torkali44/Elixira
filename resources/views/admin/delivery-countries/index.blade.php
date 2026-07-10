@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">{{ __('admin.delivery_zones.title') }}</h2>
    <a href="{{ route('admin.delivery-countries.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> {{ __('admin.delivery_zones.add_country') }}
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">{{ __('admin.delivery_zones.col_country') }}</th>
                        <th>{{ __('admin.delivery_zones.col_code') }}</th>
                        <th>{{ __('admin.delivery_zones.col_currency') }}</th>
                        <th>{{ __('admin.delivery_zones.col_cities') }}</th>
                        <th>{{ __('admin.delivery_zones.col_status') }}</th>
                        <th class="pe-4 text-end">{{ __('admin.delivery_zones.col_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($countries as $country)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ app()->getLocale() === 'ar' ? $country->name_ar : $country->name_en }}</td>
                            <td><code>{{ $country->code }}</code></td>
                            <td>{{ $country->local_currency_label }}</td>
                            <td>{{ $country->cities_count }}</td>
                            <td>
                                @if($country->is_active)
                                    <span class="badge bg-success">{{ __('admin.delivery_zones.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('admin.delivery_zones.inactive') }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.delivery-countries.edit', $country) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.delivery-countries.destroy', $country) }}" method="POST" class="d-inline" onsubmit="return confirm(@json(__('admin.delivery_zones.delete_confirm')));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">{{ __('admin.delivery_zones.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $countries->links() }}</div>
@endsection
