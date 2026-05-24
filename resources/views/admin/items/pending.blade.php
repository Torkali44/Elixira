@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">Pending Products</h2>
    <a href="{{ route('admin.items.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Add product</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Product Details</th>
                        <th>Vendor</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingItems as $item)
                        <tr>
                            <td>{{ $item->id }} </td>
                            <td>
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->name }}</strong><br>
                                <small class="text-muted">{{ $item->category->name }}</small>
                            </td>
                            <td>
                                @if($item->vendor)
                                    {{ $item->vendor->name }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                 @if($item->brandModel)
                                  <span class="badge bg-secondary"> {{ $item->brandModel->name }}</span> 
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($item->category)
                                    <span class="badge bg-secondary">{{ $item->category->name }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->stock }}</td>
                            <td>{{ $item->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.items.show', $item) }}" class="btn btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.items.approve', $item) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to approve this product?">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-success">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.items.reject', $item) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <p>Please provide an optional reason for rejecting <strong>{{ $item->name }}</strong> (Required if returning with notes):</p>
                                                    <div class="mb-3">
                                                        <textarea name="rejection_reason" class="form-control" rows="4" placeholder="This will be visible to the vendor..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <div>
                                                        <button type="submit" name="reject_type" value="notes" class="btn btn-warning text-dark me-2">Reject with Notes</button>
                                                        <button type="submit" name="reject_type" value="final" class="btn btn-danger" onclick="return confirm('Are you sure you want to permanently reject this product?');">Final Reject</button>
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
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-check-circle fs-1 mb-3"></i>
                                    <p class="mb-0">No pending products to review.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $pendingItems->links() }}
        </div>
    </div>
</div>
@endsection
