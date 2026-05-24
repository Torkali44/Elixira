@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">All Products</h3>
        <a href="{{ route('vendor.items.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add Product
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Ownership</th>
                            <th>Status</th>
                            <th>Actions</th>
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
                                    @if($item->brand_id === $vendorBrandId)
                                        <span class="badge bg-primary">My Product</span>
                                    @else
                                        <span class="badge bg-secondary">Other</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($item->status == 'rejected')
                                        <span class="badge bg-danger" title="{{ $item->rejection_reason }}">Rejected</span>
                                        @if($item->rejection_reason)
                                            <small class="d-block text-danger mt-1">{{ $item->rejection_reason }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->brand_id === $vendorBrandId)
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('vendor.items.edit', $item) }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('vendor.items.destroy', $item) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to delete this product?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger rounded-end">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted"><i class="fas fa-lock" title="Cannot edit other vendor's product"></i></span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fs-1 mb-3"></i>
                                        <p class="mb-0">You haven't added any products yet.</p>
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
