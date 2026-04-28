@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Avatar Library</h2>
    <a href="{{ route('admin.avatar-options.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Avatar
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><small class="text-muted">Total</small><h4>{{ $stats['total'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><small class="text-muted">Active</small><h4>{{ $stats['active'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><small class="text-muted">Inactive</small><h4>{{ $stats['inactive'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><small class="text-muted">Used By Users</small><h4>{{ $stats['assigned'] }}</h4></div></div></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Sort</th>
                        <th>Status</th>
                        <th>Usage Ratio</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($avatarOptions as $avatar)
                        <tr>
                            <td><img src="{{ $avatar->image_url }}" alt="{{ $avatar->name }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;"></td>
                            <td>{{ $avatar->name }}</td>
                            <td>
                                @if($avatar->gender === 'male')
                                    <span class="badge bg-primary">Male</span>
                                @elseif($avatar->gender === 'female')
                                    <span class="badge bg-danger" style="background-color: #e83e8c !important;">Female</span>
                                @else
                                    <span class="badge bg-info">Both</span>
                                @endif
                            </td>
                            <td>{{ $avatar->sort_order }}</td>
                            <td>
                                <span class="badge {{ $avatar->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $avatar->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <strong>Total:</strong> {{ $avatar->users_count }}<br>
                                    @if($avatar->users_count > 0)
                                        <div class="progress mt-1" style="height: 6px;">
                                            @php
                                                $malePercent = ($avatar->male_users_count / $avatar->users_count) * 100;
                                                $femalePercent = ($avatar->female_users_count / $avatar->users_count) * 100;
                                            @endphp
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $malePercent }}%" title="Male: {{ round($malePercent) }}%"></div>
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $femalePercent }}%; background-color: #e83e8c !important;" title="Female: {{ round($femalePercent) }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1" style="font-size: 0.75rem;">
                                            <span class="text-primary">M: {{ round($malePercent) }}%</span>
                                            <span class="text-danger">F: {{ round($femalePercent) }}%</span>
                                        </div>
                                    @else
                                        <span class="text-muted">Not used yet</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.avatar-options.toggle', $avatar) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-sm {{ $avatar->is_active ? 'btn-outline-warning' : 'btn-success' }}"
                                        title="{{ $avatar->is_active ? 'Deactivate this avatar' : 'Activate this avatar' }}">
                                        {{ $avatar->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                <a href="{{ route('admin.avatar-options.edit', $avatar) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.avatar-options.destroy', $avatar) }}" method="POST" class="d-inline" data-confirm="Delete this avatar?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No avatars found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $avatarOptions->links('pagination::bootstrap-5') }}
       
    </div>
</div>
@endsection
