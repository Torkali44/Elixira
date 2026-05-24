@extends('layouts.admin')

@section('title', 'View Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Product Details: {{ $item->name }}</h2>
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
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
@endsection
