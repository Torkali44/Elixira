@extends('layouts.admin')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="mb-1"> Vendor Requests </h2>
        <p class="text-muted mb-0">Review and manage vendor applications.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Brand</th>
                        <th>User</th>
                        <th>Applied At</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($request->brand_logo)
                                        <img src="{{ asset('storage/' . $request->brand_logo) }}" alt="{{ $request->brand_name }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div style="width: 48px; height: 48px; background: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-store text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $request->brand_name }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 250px;">{{ $request->brand_description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $request->user->name }}</div>
                                <div class="small text-muted">{{ $request->user->email }}</div>
                            </td>
                            <td>{{ $request->updated_at->format('M d, Y h:i A') }}</td>
                            <td>
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($request->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($request->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.vendors.requests.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No vendor requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>
</div>

@endsection
