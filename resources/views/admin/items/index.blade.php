@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1 fw-bold">All Products</h2>
        <p class="text-muted mb-0">Manage all products uploaded by vendors and the platform admin.</p>
    </div>
    <a href="{{ route('admin.items.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Add product</a>
</div>

{{-- Status Filters --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-2 d-flex flex-wrap gap-2">
                <a href="{{ route('admin.items.index') }}" 
                   class="btn {{ !request('status') ? 'btn-primary' : 'btn-light' }} rounded-pill px-4">
                    <i class="fas fa-list me-2"></i> All ({{ \App\Models\Item::count() }})
                </a>
                <a href="{{ route('admin.items.index', ['status' => 'pending']) }}" 
                   class="btn {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-light text-warning' }} rounded-pill px-4">
                    <i class="fas fa-clock me-2"></i> Pending ({{ \App\Models\Item::where('status', 'pending')->count() }})
                </a>
                <a href="{{ route('admin.items.index', ['status' => 'approved']) }}" 
                   class="btn {{ request('status') === 'approved' ? 'btn-success' : 'btn-light text-success' }} rounded-pill px-4">
                    <i class="fas fa-check-circle me-2"></i> Approved ({{ \App\Models\Item::where('status', 'approved')->count() }})
                </a>
                <a href="{{ route('admin.items.index', ['status' => 'rejected']) }}" 
                   class="btn {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-light text-danger' }} rounded-pill px-4">
                    <i class="fas fa-times-circle me-2"></i> Rejected ({{ \App\Models\Item::whereIn('status', ['rejected', 'rejected_with_notes'])->count() }})
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
                        <th class="ps-4">Product Details</th>
                        <th>Brand (Vendor)</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock Status</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                                        {{ $item->description ?: 'No short description provided.' }}
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
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Owner: {{ $item->vendor->name }}</small>
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
                                <span class="badge bg-danger rounded-pill px-2 py-1">Out of stock</span>
                            @elseif($item->stock <= 10)
                                <span class="badge bg-warning text-dark rounded-pill px-2 py-1">Low ({{ $item->stock }})</span>
                            @else
                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">{{ $item->stock }} in stock</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status === 'approved')
                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i> Approved</span>
                            @elseif($item->status === 'pending')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-clock me-1"></i> Pending Approval</span>
                            @elseif($item->status === 'rejected_with_notes')
                                <span class="badge bg-danger rounded-pill px-3 py-2" title="Rejection Reason: {{ $item->rejection_reason }}"><i class="fas fa-exclamation-circle me-1"></i> Rejected (Notes)</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2"><i class="fas fa-times-circle me-1"></i> Rejected</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.items.show', $item->id) }}" class="btn btn-sm btn-outline-info" title="View details"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit product"><i class="fas fa-edit"></i></a>
                                
                                @if($item->status !== 'approved')
                                    <form action="{{ route('admin.items.approve', $item->id) }}" method="POST" class="d-inline" data-confirm="Approve this product and make it active on storefront?">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Approve"><i class="fas fa-check"></i></button>
                                    </form>
                                @endif
                                
                                @if(!in_array($item->status, ['rejected', 'rejected_with_notes']))
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}" title="Reject Product"><i class="fas fa-times"></i></button>
                                @endif

                                <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="d-inline" data-confirm="Delete this product?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Product: {{ $item->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.items.reject', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <p>Provide a rejection reason below. Selecting <strong>Reject with Notes</strong> allows the vendor to read the comments, fix issues, and resubmit.</p>
                                                <div class="mb-3">
                                                    <label for="rejection_reason_{{ $item->id }}" class="form-label fw-bold">Feedback / Rejection Reason</label>
                                                    <textarea id="rejection_reason_{{ $item->id }}" name="rejection_reason" class="form-control" rows="4" placeholder="Mention what needs to be fixed..."></textarea>
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox d-block mb-3" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            No products found matching the criteria.
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
