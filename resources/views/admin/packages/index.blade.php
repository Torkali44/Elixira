@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1 fw-bold">{{ __('admin.packages_page.title') }}</h2>
        <p class="text-muted mb-0">{{ __('admin.packages_page.subtitle') }}</p>
    </div>
    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i> {{ __('admin.packages_page.add') }}</a>
</div>

@php
    $newCount = \App\Models\Package::where('status', 'pending')->where('created_at', '>=', now()->subHours(24))->count();
    $pendingCount = \App\Models\Package::where('status', 'pending')->where('created_at', '<', now()->subHours(24))->count();
    $approvedCount = \App\Models\Package::where('status', 'approved')->count();
    $rejectedCount = \App\Models\Package::whereIn('status', ['rejected', 'rejected_with_notes'])->count();
    $allCount = \App\Models\Package::count();
@endphp

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-2 d-flex flex-wrap gap-2">
                <a href="{{ route('admin.packages.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-light' }} rounded-pill px-4">
                    <i class="fas fa-list me-2"></i> {{ __('admin.items.filter_all') }} ({{ $allCount }})
                </a>
                <a href="{{ route('admin.packages.index', ['status' => 'new']) }}" class="btn rounded-pill px-4 {{ request('status') === 'new' ? 'text-white' : 'btn-light' }}" style="{{ request('status') === 'new' ? 'background:#0d9488; border-color:#0d9488;' : 'color:#0d9488;' }}">
                    <i class="fas fa-star me-2"></i> {{ __('admin.items.filter_new') }} ({{ $newCount }})
                </a>
                <a href="{{ route('admin.packages.index', ['status' => 'pending']) }}" class="btn {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-light text-warning' }} rounded-pill px-4">
                    <i class="fas fa-clock me-2"></i> {{ __('admin.items.filter_pending') }} ({{ $pendingCount }})
                </a>
                <a href="{{ route('admin.packages.index', ['status' => 'approved']) }}" class="btn {{ request('status') === 'approved' ? 'btn-success' : 'btn-light text-success' }} rounded-pill px-4">
                    <i class="fas fa-check-circle me-2"></i> {{ __('admin.items.filter_approved') }} ({{ $approvedCount }})
                </a>
                <a href="{{ route('admin.packages.index', ['status' => 'rejected']) }}" class="btn {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-light text-danger' }} rounded-pill px-4">
                    <i class="fas fa-times-circle me-2"></i> {{ __('admin.items.filter_rejected') }} ({{ $rejectedCount }})
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0" style="border-radius: 16px;">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">{{ __('admin.packages_page.name') }}</th>
                    <th>{{ __('admin.items.col_brand') }}</th>
                    <th>{{ __('admin.packages_page.items_count') }}</th>
                    <th>{{ __('admin.packages_page.price') }}</th>
                    <th>{{ __('admin.packages_page.status') }}</th>
                    <th>{{ __('admin.items.col_actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $package)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $package->local_name }}</td>
                        <td>{{ $package->brand?->name ?? '—' }}</td>
                        <td>{{ $package->items_count }}</td>
                        <td>﷼ {{ number_format($package->price, 2) }}</td>
                        <td>
                            @if($package->status === 'approved')
                                <span class="badge bg-success rounded-pill px-3 py-2">{{ __('admin.items.status_approved') }}</span>
                            @elseif($package->status === 'pending')
                                @if($package->created_at->diffInHours(now()) < 24)
                                    <span class="badge rounded-pill px-3 py-2 text-white" style="background:#0d9488;">{{ __('admin.items.status_new') }}</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">{{ __('admin.items.status_pending') }}</span>
                                @endif
                            @elseif($package->status === 'rejected_with_notes')
                                <span class="badge bg-danger rounded-pill px-3 py-2">{{ __('admin.items.status_rejected_notes') }}</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2">{{ __('admin.items.status_rejected') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-outline-primary" title="{{ __('admin.home_sections_page.edit') }}"><i class="fas fa-edit"></i></a>

                                @if($package->status !== 'approved')
                                    <form action="{{ route('admin.packages.approve', $package) }}" method="POST" class="d-inline" data-confirm="{{ __('admin.packages_page.confirm_approve') }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="{{ __('admin.items.approve') }}"><i class="fas fa-check"></i></button>
                                    </form>
                                @endif

                                @if(!in_array($package->status, ['rejected', 'rejected_with_notes']))
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#rejectPackageModal{{ $package->id }}" title="{{ __('admin.items.reject') }}"><i class="fas fa-times"></i></button>
                                @endif

                                <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="d-inline" data-confirm="{{ __('admin.packages_page.confirm_delete') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('admin.packages_page.delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>

                            <div class="modal fade" id="rejectPackageModal{{ $package->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ __('admin.packages_page.reject_modal_title', ['name' => $package->local_name]) }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('admin.common.cancel') }}"></button>
                                        </div>
                                        <form action="{{ route('admin.packages.reject', $package) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <p>{{ __('admin.items.reject_modal_hint') }}</p>
                                                <textarea name="rejection_reason" class="form-control" rows="4" placeholder="{{ __('admin.items.rejection_placeholder') }}"></textarea>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.common.cancel') }}</button>
                                                <div>
                                                    <button type="submit" name="reject_type" value="notes" class="btn btn-warning text-dark me-2">{{ __('admin.items.reject_with_notes') }}</button>
                                                    <button type="submit" name="reject_type" value="final" class="btn btn-danger">{{ __('admin.items.final_reject') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">{{ __('admin.packages_page.empty') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $packages->links() }}
@endsection
