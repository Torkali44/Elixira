@extends('layouts.admin')

@section('title', 'View Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Product Details: {{ $item->name }}</h2>
    <div class="d-flex gap-2">
        @if($item->brandModel)
            <a href="{{ route('admin.brands.edit', $item->brandModel) }}" class="btn btn-outline-primary">
                <i class="fas fa-store me-2"></i> Edit Vendor Brand
            </a>
        @endif
        <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Products
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid rounded mb-3" style="max-height: 250px; object-fit: cover;" alt="{{ $item->name }}">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 250px;">
                        <i class="fas fa-image text-muted fa-4x"></i>
                    </div>
                @endif
                <h4>{{ $item->name }}</h4>
                <div class="mb-2">
                    <span class="badge bg-primary fs-6">﷼ {{ number_format($item->price, 2) }}</span>
                    @if($item->status === 'approved')
                        <span class="badge bg-success fs-6">Approved</span>
                    @elseif($item->status === 'pending')
                        <span class="badge bg-warning text-dark fs-6">Pending</span>
                    @elseif($item->status === 'rejected_with_notes')
                        <span class="badge bg-warning text-dark fs-6">Rejected (Notes)</span>
                    @else
                        <span class="badge bg-danger fs-6">Rejected</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="mb-0">General Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 30%">Category:</th>
                        <td>{{ $item->category->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Brand:</th>
                        <td>{{ $item->brandModel->name ?? $item->brand ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Vendor:</th>
                        <td>{{ $item->vendor->name ?? 'N/A' }} ({{ $item->vendor->email ?? 'N/A' }})</td>
                    </tr>
                    <tr>
                        <th>Stock:</th>
                        <td>{{ $item->stock }}</td>
                    </tr>
                    <tr>
                        <th>Points:</th>
                        <td>{{ $item->points }}</td>
                    </tr>
                    <tr>
                        <th>Short Description:</th>
                        <td>{{ $item->description }}</td>
                    </tr>
                    <tr>
                        <th>Long Description:</th>
                        <td>{!! nl2br(e($item->long_description)) !!}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($item->images->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="mb-0">Product Gallery</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($item->images as $img)
                        <img src="{{ asset('storage/' . $img->image) }}" class="rounded shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@if($item->status === 'rejected_with_notes')
<div class="card border-0 shadow-sm mt-4 border-warning">
    <div class="card-body">
        <h5 class="text-warning fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i> Returned to Vendor for Revision</h5>
        <p class="text-muted mb-2">This product was rejected with notes. The vendor must edit and resubmit before it returns to pending approval.</p>
        @if($item->rejection_reason)
            <div class="alert alert-warning mb-3">{{ $item->rejection_reason }}</div>
        @endif
        <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Products
        </a>
    </div>
</div>
@endif

@if($item->status === 'pending')
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-clipboard-check me-2 text-primary"></i> Product Approval Actions</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">As an admin, you can review this product and decide to approve or reject it.</p>
        <div class="d-flex gap-3">
            <!-- Approve Form -->
            <form action="{{ route('admin.items.approve', $item->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success px-4 py-2">
                    <i class="fas fa-check me-2"></i> Approve Product
                </button>
            </form>

            <!-- Reject Button (Triggers collapse) -->
            <button class="btn btn-danger px-4 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#rejectSection" aria-expanded="false" aria-controls="rejectSection">
                <i class="fas fa-times me-2"></i> Reject Product
            </button>
        </div>

        <!-- Collapse Section for Rejection Form -->
        <div class="collapse mt-3" id="rejectSection">
            <div class="card card-body bg-light border-0">
                <form action="{{ route('admin.items.reject', $item->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rejection Type</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reject_type" id="reject_notes" value="notes" checked>
                                <label class="form-check-label" for="reject_notes">
                                    Reject with Notes (Allow Vendor to edit and resubmit)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reject_type" id="reject_final" value="final">
                                <label class="form-check-label" for="reject_final">
                                    Final Rejection (Do not allow resubmission)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="reasonGroup">
                        <label for="rejection_reason" class="form-label fw-bold">Rejection Reason / Notes</label>
                        <textarea class="form-control" name="rejection_reason" id="rejection_reason" rows="3" placeholder="Provide feedback to the vendor..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger">
                        Confirm Rejection
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
