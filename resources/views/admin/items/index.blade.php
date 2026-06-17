@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1 fw-bold">{{ __('admin.items.title') }}</h2>
        <p class="text-muted mb-0">{{ __('admin.items.subtitle') }}</p>
    </div>
    <a href="{{ route('admin.items.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i> {{ __('admin.items.add_product') }}</a>
</div>

@php
    $newCount     = \App\Models\Item::where('status', 'pending')->where('created_at', '>=', now()->subHours(24))->count();
    $pendingCount = \App\Models\Item::where('status', 'pending')->where('created_at', '<',  now()->subHours(24))->count();
    $approvedCount = \App\Models\Item::where('status', 'approved')->count();
    $rejectedCount = \App\Models\Item::whereIn('status', ['rejected', 'rejected_with_notes'])->count();
    $allCount     = \App\Models\Item::count();
@endphp

{{-- Status Filters --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-2 d-flex flex-wrap gap-2">
                <a href="{{ route('admin.items.index') }}"
                   class="btn {{ !request('status') ? 'btn-primary' : 'btn-light' }} rounded-pill px-4">
                    <i class="fas fa-list me-2"></i> {{ __('admin.items.filter_all') }} ({{ $allCount }})
                </a>
                <a href="{{ route('admin.items.index', ['status' => 'new']) }}"
                   class="btn rounded-pill px-4 {{ request('status') === 'new' ? 'text-white' : 'btn-light' }}"
                   style="{{ request('status') === 'new' ? 'background:#0d9488; border-color:#0d9488;' : 'color:#0d9488;' }}">
                    <i class="fas fa-star me-2"></i> {{ __('admin.items.filter_new') }} ({{ $newCount }})
                </a>
                <a href="{{ route('admin.items.index', ['status' => 'pending']) }}"
                   class="btn {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-light text-warning' }} rounded-pill px-4">
                    <i class="fas fa-clock me-2"></i> {{ __('admin.items.filter_pending') }} ({{ $pendingCount }})
                </a>
                <a href="{{ route('admin.items.index', ['status' => 'approved']) }}"
                   class="btn {{ request('status') === 'approved' ? 'btn-success' : 'btn-light text-success' }} rounded-pill px-4">
                    <i class="fas fa-check-circle me-2"></i> {{ __('admin.items.filter_approved') }} ({{ $approvedCount }})
                </a>
                <a href="{{ route('admin.items.index', ['status' => 'rejected']) }}"
                   class="btn {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-light text-danger' }} rounded-pill px-4">
                    <i class="fas fa-times-circle me-2"></i> {{ __('admin.items.filter_rejected') }} ({{ $rejectedCount }})
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">{{ __('admin.items.col_details') }}</th>
                        <th>{{ __('admin.items.col_brand') }}</th>
                        <th>{{ __('admin.items.col_category') }}</th>
                        <th>{{ __('admin.items.col_price') }}</th>
                        <th>{{ __('admin.items.col_stock') }}</th>
                        <th>{{ __('admin.items.col_status') }}</th>
                        <th>{{ __('admin.audit.last_modified') }}</th>
                        <th>{{ __('admin.items.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" width="54" height="54" class="rounded shadow-sm border" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width: 54px; height: 54px;">
                                        <i class="fas fa-image fa-lg"></i>
                                    </div>
                                @endif
                                <div>
                                    <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">{{ $item->name }}</span>
                                    <small class="text-muted d-block" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $item->description ?: __('admin.items.no_description') }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($item->brandModel)
                                <div class="d-flex align-items-center gap-2">
                                    @if($item->brandModel->logo)
                                        <img src="{{ asset('storage/' . $item->brandModel->logo) }}" width="24" height="24" class="rounded-circle border" style="object-fit: cover;">
                                    @endif
                                    <div>
                                        <span class="fw-bold text-primary">{{ $item->brandModel->name }}</span>
                                        @if($item->vendor)
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">{{ __('admin.items.owner', ['name' => $item->vendor->name]) }}</small>
                                        @endif
                                    </div>
                                </div>
                            @elseif($item->brand)
                                <span class="badge bg-light text-dark border">{{ $item->brand }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $item->category->name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <strong class="text-dark">﷼ {{ number_format($item->price, 2) }}</strong>
                            @if($item->points > 0)
                                <small class="text-success d-block" style="font-size: 0.75rem;"><i class="fas fa-star me-1"></i>{{ $item->points }} pts</small>
                            @endif
                        </td>
                        <td>
                            @if($item->stock <= 0)
                                <span class="badge bg-danger rounded-pill px-2 py-1">{{ __('admin.items.out_of_stock') }}</span>
                            @elseif($item->stock <= 10)
                                <span class="badge bg-warning text-dark rounded-pill px-2 py-1">{{ __('admin.items.low_stock', ['count' => $item->stock]) }}</span>
                            @else
                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">{{ __('admin.items.in_stock', ['count' => $item->stock]) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status === 'approved')
                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i> {{ __('admin.items.status_approved') }}</span>
                            @elseif($item->status === 'pending')
                                @if($item->created_at->diffInHours(now()) < 24)
                                    <span class="badge rounded-pill px-3 py-2 text-white" style="background:#0d9488;"><i class="fas fa-star me-1"></i> {{ __('admin.items.status_new') }}</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-clock me-1"></i> {{ __('admin.items.status_pending') }}</span>
                                @endif
                            @elseif($item->status === 'rejected_with_notes')
                                <span class="badge bg-danger rounded-pill px-3 py-2" title="Rejection Reason: {{ $item->rejection_reason }}"><i class="fas fa-exclamation-circle me-1"></i> {{ __('admin.items.status_rejected_notes') }}</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2"><i class="fas fa-times-circle me-1"></i> {{ __('admin.items.status_rejected') }}</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $item->updated_at?->format('Y-m-d H:i') ?? __('admin.audit.never') }}</small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.items.show', $item->id) }}" class="btn btn-sm btn-outline-info" title="{{ __('admin.items.view_details') }}"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="{{ __('admin.items.edit_product') }}"><i class="fas fa-edit"></i></a>
                                
                                @if($item->status !== 'approved')
                                    <form action="{{ route('admin.items.approve', $item->id) }}" method="POST" class="d-inline" data-confirm="{{ __('admin.items.confirm_approve') }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="{{ __('admin.items.approve') }}"><i class="fas fa-check"></i></button>
                                    </form>
                                @endif
                                
                                @if(!in_array($item->status, ['rejected', 'rejected_with_notes']))
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}" title="{{ __('admin.items.reject') }}"><i class="fas fa-times"></i></button>
                                @endif
 
                                <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="d-inline" data-confirm="{{ __('admin.items.confirm_delete') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('admin.items.delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
 
                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ __('admin.items.reject_modal_title', ['name' => $item->name]) }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('admin.common.cancel') }}"></button>
                                        </div>
                                        <form action="{{ route('admin.items.reject', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <p>{{ __('admin.items.reject_modal_hint') }}</p>
                                                <div class="mb-3">
                                                    <label for="rejection_reason_{{ $item->id }}" class="form-label fw-bold">{{ __('admin.items.rejection_reason') }}</label>
                                                    <textarea id="rejection_reason_{{ $item->id }}" name="rejection_reason" class="form-control" rows="4" placeholder="{{ __('admin.items.rejection_placeholder') }}"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.common.cancel') }}</button>
                                                <div>
                                                    <button type="submit" name="reject_type" value="notes" class="btn btn-warning text-dark me-2">{{ __('admin.items.reject_with_notes') }}</button>
                                                    <button type="button" class="btn btn-danger" data-swal-confirm="{{ __('admin.items.confirm_final_reject') }}" data-final-reject="{{ $item->id }}">{{ __('admin.items.final_reject') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox d-block mb-3" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            {{ __('admin.items.empty') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $items->links() }}
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-final-reject]').forEach((button) => {
        button.addEventListener('click', () => {
            const form = button.closest('form');
            Swal.fire({
                icon: 'warning',
                title: @json(__('admin.common.please_confirm')),
                text: button.dataset.swalConfirm,
                showCancelButton: true,
                confirmButtonText: @json(__('admin.common.yes')),
                cancelButtonText: @json(__('admin.common.cancel')),
            }).then((result) => {
                if (!result.isConfirmed || !form) return;
                let input = form.querySelector('input[name="reject_type"]');
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'reject_type';
                    form.appendChild(input);
                }
                input.value = 'final';
                form.submit();
            });
        });
    });
</script>
@endpush
