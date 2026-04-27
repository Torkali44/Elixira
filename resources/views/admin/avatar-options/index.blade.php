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
                        <th>Link</th>
                        <th>Sort</th>
                        <th>Status</th>
                        <th>Users</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($avatarOptions as $avatar)
                        <tr>
                            <td><img src="{{ $avatar->image_url }}" alt="{{ $avatar->name }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;"></td>
                            <td>{{ $avatar->name }}</td>
                            <td><a href="{{ $avatar->link_url ?: '#' }}" target="_blank">{{ $avatar->link_url ? 'Open link' : '—' }}</a></td>
                            <td>{{ $avatar->sort_order }}</td>
                            <td>
                                <span class="badge {{ $avatar->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $avatar->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $avatar->users_count }}</td>
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
