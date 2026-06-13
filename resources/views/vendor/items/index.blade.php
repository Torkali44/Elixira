@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0">{{ __('vendor.items.title') }}</h3>
        <a href="{{ route('vendor.items.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> {{ __('vendor.items.add_product') }}
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('vendor.items.col_image') }}</th>
                            <th>{{ __('vendor.items.col_name') }}</th>
                            <th>{{ __('vendor.items.col_category') }}</th>
                            <th>{{ __('vendor.items.col_price') }}</th>
                            <th>{{ __('vendor.items.col_stock') }}</th>
                            <th>{{ __('vendor.items.col_status') }}</th>
                            <th>{{ __('vendor.items.col_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->stock }}</td>
                                <td>
                                    @if($item->status === 'approved')
                                        <span class="badge bg-success">{{ __('vendor.items.status_approved') }}</span>
                                    @elseif($item->status === 'rejected')
                                        <span class="badge bg-danger" title="{{ $item->rejection_reason }}">{{ __('vendor.items.status_rejected') }}</span>
                                        @if($item->rejection_reason)
                                            <small class="d-block text-danger mt-1">{{ $item->rejection_reason }}</small>
                                        @endif
                                    @elseif($item->status === 'rejected_with_notes')
                                        <span class="badge bg-warning text-dark">{{ __('vendor.items.status_needs_revision') }}</span>
                                        @if($item->rejection_reason)
                                            <small class="d-block text-muted mt-1">{{ $item->rejection_reason }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-info text-dark">{{ __('vendor.items.status_pending') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('vendor.items.edit', $item) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('vendor.items.destroy', $item) }}" method="POST" class="d-inline" data-confirm="{{ __('vendor.items.confirm_delete') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger rounded-end">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fs-1 mb-3"></i>
                                        <p class="mb-0">{{ __('vendor.items.empty') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
