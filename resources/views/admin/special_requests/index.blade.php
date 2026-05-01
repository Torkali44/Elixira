@extends('layouts.admin')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="mb-1">الطلبات الخاصة (Special Requests)</h2>
        <p class="text-muted mb-0">Manage special requests for out-of-stock items.</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>User</th>
                        <th>WhatsApp</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($specialRequests as $request)
                        <tr>
                            <td>
                                @if($request->item)
                                    <div class="d-flex align-items-center gap-3">
                                        @if($request->item->image)
                                            <img src="{{ asset('storage/' . $request->item->image) }}" alt="" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div style="width: 48px; height: 48px; background: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $request->item->name }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Deleted Product</span>
                                @endif
                            </td>
                            <td>
                                @if($request->user)
                                    <div class="fw-bold">{{ $request->user->name }}</div>
                                @else
                                    <div class="fw-bold">{{ $request->name ?? 'Guest' }}</div>
                                @endif
                            </td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $request->phone) }}?text={{ urlencode('مرحباً، المنتج (' . ($request->item ? $request->item->name : 'الذي طلبته') . ') متوفر الآن في Elixira!') }}" target="_blank" class="btn btn-sm btn-success">
                                    <i class="fab fa-whatsapp"></i> {{ $request->phone }}
                                </a>
                            </td>
                            <td>{{ $request->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-success">Notified</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.special-requests.updateStatus', $request) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($request->status === 'pending')
                                        <input type="hidden" name="status" value="notified">
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Mark as Notified">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @else
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Mark as Pending">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No special requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $specialRequests->links() }}
        </div>
    </div>
</div>
@endsection
