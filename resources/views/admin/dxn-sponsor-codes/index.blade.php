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
        <form action="{{ route('admin.dxn-sponsor-codes.store') }}" method="POST" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-3">
                <label class="form-label">{{ __('admin.dxn_sponsor_codes.code') }}</label>
                <input type="text" name="code" class="form-control" required maxlength="100" value="{{ old('code') }}">
            </div>
            <div class="col-md-5">
                <label class="form-label">{{ __('admin.dxn_sponsor_codes.sponsor_name') }}</label>
                <input type="text" name="sponsor_name" class="form-control" required maxlength="255" value="{{ old('sponsor_name') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('admin.dxn_sponsor_codes.sort_order') }}</label>
                <input type="number" name="sort_order" class="form-control" min="0" max="9999" value="{{ old('sort_order', 0) }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">{{ __('admin.common.save') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('admin.dxn_sponsor_codes.code') }}</th>
                        <th>{{ __('admin.dxn_sponsor_codes.sponsor_name') }}</th>
                        <th>{{ __('admin.dxn_sponsor_codes.sort_order') }}</th>
                        <th>{{ __('admin.dxn_sponsor_codes.active') }}</th>
                        <th class="text-end">{{ __('admin.vendor_requests.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($codes as $code)
                        <tr>
                            <td colspan="5" class="p-0 border-0">
                                <form action="{{ route('admin.dxn-sponsor-codes.update', $code) }}" method="POST" class="d-flex flex-wrap align-items-center gap-2 p-3 border-bottom">
                                    @csrf
                                    @method('PATCH')
                                    <div style="min-width: 120px; flex: 1;">
                                        <input type="text" name="code" class="form-control form-control-sm" value="{{ $code->code }}" required>
                                    </div>
                                    <div style="min-width: 180px; flex: 2;">
                                        <input type="text" name="sponsor_name" class="form-control form-control-sm" value="{{ $code->sponsor_name }}" required>
                                    </div>
                                    <div style="width: 90px;">
                                        <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $code->sort_order }}" min="0">
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked($code->is_active)>
                                    </div>
                                    <div class="ms-auto d-flex gap-2">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">{{ __('admin.common.save') }}</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-delete-url="{{ route('admin.dxn-sponsor-codes.destroy', $code) }}" data-confirm="{{ __('admin.dxn_sponsor_codes.confirm_delete') }}">
                                            {{ __('admin.common.delete') }}
                                        </button>
                                    </div>
                                </form>
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

<form id="sponsorCodeDeleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endsection

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
