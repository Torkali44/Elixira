@extends('layouts.admin')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="mb-1">{{ __('admin.dxn_sponsor_codes.title') }}</h2>
        <p class="text-muted mb-0">{{ __('admin.dxn_sponsor_codes.subtitle') }}</p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold">{{ __('admin.dxn_sponsor_codes.add') }}</div>
    <div class="card-body">
        <form action="{{ route('admin.dxn-sponsor-codes.store') }}" method="POST">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold" for="new_code">{{ __('admin.dxn_sponsor_codes.code') }}</label>
                    <input id="new_code" type="text" name="code" class="form-control" required maxlength="100" value="{{ old('code') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold" for="new_sponsor_name">{{ __('admin.dxn_sponsor_codes.sponsor_name') }}</label>
                    <input id="new_sponsor_name" type="text" name="sponsor_name" class="form-control" required maxlength="255" value="{{ old('sponsor_name') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold" for="new_sort_order">{{ __('admin.dxn_sponsor_codes.sort_order') }}</label>
                    <input id="new_sort_order" type="number" name="sort_order" class="form-control" min="0" max="9999" value="{{ old('sort_order', 0) }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">{{ __('admin.common.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0 sponsor-codes-table">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="min-width: 140px;">{{ __('admin.dxn_sponsor_codes.code') }}</th>
                        <th style="min-width: 200px;">{{ __('admin.dxn_sponsor_codes.sponsor_name') }}</th>
                        <th style="min-width: 100px;">{{ __('admin.dxn_sponsor_codes.sort_order') }}</th>
                        <th class="text-center" style="min-width: 90px;">{{ __('admin.dxn_sponsor_codes.active') }}</th>
                        <th class="text-center pe-3" style="min-width: 160px;">{{ __('admin.dxn_sponsor_codes.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($codes as $code)
                        <tr>
                            <td class="ps-3">
                                <label class="form-label small text-muted mb-1 d-md-none" for="code_{{ $code->id }}">{{ __('admin.dxn_sponsor_codes.code') }}</label>
                                <input
                                    form="sponsor-form-{{ $code->id }}"
                                    id="code_{{ $code->id }}"
                                    type="text"
                                    name="code"
                                    class="form-control form-control-sm"
                                    value="{{ $code->code }}"
                                    required
                                >
                            </td>
                            <td>
                                <label class="form-label small text-muted mb-1 d-md-none" for="sponsor_name_{{ $code->id }}">{{ __('admin.dxn_sponsor_codes.sponsor_name') }}</label>
                                <input
                                    form="sponsor-form-{{ $code->id }}"
                                    id="sponsor_name_{{ $code->id }}"
                                    type="text"
                                    name="sponsor_name"
                                    class="form-control form-control-sm"
                                    value="{{ $code->sponsor_name }}"
                                    required
                                >
                            </td>
                            <td>
                                <label class="form-label small text-muted mb-1 d-md-none" for="sort_order_{{ $code->id }}">{{ __('admin.dxn_sponsor_codes.sort_order') }}</label>
                                <input
                                    form="sponsor-form-{{ $code->id }}"
                                    id="sort_order_{{ $code->id }}"
                                    type="number"
                                    name="sort_order"
                                    class="form-control form-control-sm"
                                    value="{{ $code->sort_order }}"
                                    min="0"
                                >
                            </td>
                            <td class="text-center">
                                <label class="form-label small text-muted mb-1 d-md-none" for="is_active_{{ $code->id }}">{{ __('admin.dxn_sponsor_codes.active') }}</label>
                                <div class="form-check form-switch mb-0 d-inline-flex justify-content-center">
                                    <input
                                        form="sponsor-form-{{ $code->id }}"
                                        class="form-check-input"
                                        type="checkbox"
                                        name="is_active"
                                        value="1"
                                        id="is_active_{{ $code->id }}"
                                        @checked($code->is_active)
                                    >
                                </div>
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <button form="sponsor-form-{{ $code->id }}" type="submit" class="btn btn-sm btn-outline-primary">
                                        {{ __('admin.common.save') }}
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        data-delete-url="{{ route('admin.dxn-sponsor-codes.destroy', $code) }}"
                                        data-confirm="{{ __('admin.dxn_sponsor_codes.confirm_delete') }}"
                                    >
                                        {{ __('admin.common.delete') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">{{ __('admin.dxn_sponsor_codes.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($codes->hasPages())
        <div class="card-footer">{{ $codes->links() }}</div>
    @endif
</div>

@foreach($codes as $code)
    <form id="sponsor-form-{{ $code->id }}" action="{{ route('admin.dxn-sponsor-codes.update', $code) }}" method="POST" class="d-none">
        @csrf
        @method('PATCH')
    </form>
@endforeach

<form id="sponsorCodeDeleteForm" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
    .sponsor-codes-table th:nth-child(4),
    .sponsor-codes-table td:nth-child(4),
    .sponsor-codes-table th:nth-child(5),
    .sponsor-codes-table td:nth-child(5) {
        text-align: center;
        vertical-align: middle;
    }

    .sponsor-codes-table td:nth-child(4) .form-check-input {
        float: none;
        margin-left: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('[data-delete-url]').forEach((button) => {
        button.addEventListener('click', () => {
            Swal.fire({
                icon: 'warning',
                title: @json(__('admin.common.please_confirm')),
                text: button.dataset.confirm,
                showCancelButton: true,
                confirmButtonText: @json(__('admin.common.yes')),
                cancelButtonText: @json(__('admin.common.cancel')),
            }).then((result) => {
                if (!result.isConfirmed) return;
                const form = document.getElementById('sponsorCodeDeleteForm');
                form.action = button.dataset.deleteUrl;
                form.submit();
            });
        });
    });
</script>
@endpush
