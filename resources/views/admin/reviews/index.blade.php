@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Testimonials & Reviews Management</h1>

             <div class="mb-4">
                <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">Add New Screenshot/Video</a>
            </div> 

            <!-- List of Reviews -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">All Reviews</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" style="color: #333;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th style="width: 100px;">Source</th>
                                    <th style="width: 120px;">Rating</th>
                                    <th>Reviewer Info</th>
                                    <th>Testimonial / Media</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $review)
                                <tr style="background-color: #fff;">
                                    <td class="font-weight-bold">#{{ $review->id }}</td>
                                    <td>
                                        <span class="badge badge-secondary px-2 py-1" style="font-size: 0.8rem; background-color: #6c757d; color: #fff;">
                                            {{ ucfirst($review->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-warning">
                                            @if($review->rating)
                                                @for($i=0; $i<$review->rating; $i++) <i class="fas fa-star" style="font-size: 0.8rem;"></i> @endfor
                                                <span class="text-dark small ml-1">({{ $review->rating }}/5)</span>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($review->type === 'direct')
                                            <div class="font-weight-bold text-dark">{{ $review->name }}</div>
                                            <div class="small text-muted">{{ $review->age }} yrs • {{ $review->skin_type }}</div>
                                        @else
                                            <div class="text-muted small font-italic">Admin Entry</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($review->type === 'direct')
                                            <div class="p-2 bg-light rounded small text-dark" style="max-width: 350px; border: 1px solid #eee;">
                                                "{{ $review->content }}"
                                            </div>
                                        @elseif($review->type === 'video')
                                            <a href="{{ $review->content }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                <i class="fab fa-youtube mr-1"></i> Video Link
                                            </a>
                                        @else
                                            <div class="d-flex flex-column gap-2">
                                                <a href="{{ asset('storage/' . $review->avatar) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $review->avatar) }}" class="rounded shadow-sm" style="height: 50px; width: 50px; object-fit: cover; border: 1px solid #ddd;">
                                                </a>
                                                @if($review->content)
                                                    <div class="small text-dark font-italic" style="max-width: 200px;">{{ $review->content }}</div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($review->status === 'approved')
                                            <span class="badge" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745; border: 1px solid #28a745; padding: 5px 10px; border-radius: 50px; font-weight: bold;">
                                                <i class="fas fa-check-circle mr-1"></i> Approved
                                            </span>
                                        @elseif($review->status === 'pending')
                                            <span class="badge" style="background-color: rgba(255, 193, 7, 0.1); color: #856404; border: 1px solid #ffc107; padding: 5px 10px; border-radius: 50px; font-weight: bold;">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid #dc3545; padding: 5px 10px; border-radius: 50px; font-weight: bold;">
                                                <i class="fas fa-times-circle mr-1"></i> Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <form action="{{ route('admin.reviews.updateStatus', $review->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                @if($review->status !== 'approved')
                                                    <button type="submit" name="status" value="approved" class="btn btn-sm btn-success p-1" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                @if($review->status !== 'rejected')
                                                    <button type="submit" name="status" value="rejected" class="btn btn-sm btn-warning p-1" title="Reject">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @endif
                                            </form>
                                            
                                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline ml-1" data-confirm="Permanently delete?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger p-1" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
