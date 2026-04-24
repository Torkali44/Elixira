@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Reservations</h2>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Date &amp; time</th>
                        <th>Guests</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $res)
                    <tr>
                        <td>{{ $res->name }}</td>
                        <td>{{ $res->phone }}</td>
                        <td>
                            {{ $res->reservation_date->format('Y-m-d') }}<br>
                            <small class="text-muted">{{ $res->reservation_time }}</small>
                        </td>
                        <td>{{ $res->guests }}</td>
                        <td>
                            @if($res->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($res->status == 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($res->notes, 30) }}</td>
                        <td>
                            <form action="{{ route('admin.reservations.update', $res->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                @if($res->status == 'pending')
                                    <button type="submit" name="status" value="confirmed" class="btn btn-sm btn-success" title="Confirm"><i class="fas fa-check"></i></button>
                                    <button type="submit" name="status" value="cancelled" class="btn btn-sm btn-danger" title="Cancel"><i class="fas fa-times"></i></button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No reservations.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $reservations->links() }}
    </div>
</div>
@endsection
