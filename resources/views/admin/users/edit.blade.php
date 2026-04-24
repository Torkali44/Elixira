@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Edit User</h2>
        <p class="text-muted mb-0">Update member details and manage the avatar used across the system.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <x-user-avatar :user="$user" size="120" class="mx-auto mb-3" />
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">{{ ucfirst($user->role) }}</span>
                    <span class="badge {{ $user->is_suspended ? 'bg-danger' : 'bg-success' }}">{{ $user->is_suspended ? 'Suspended' : 'Active' }}</span>
                </div>
                <hr>
                <div class="text-start">
                    <p class="mb-2"><strong>Joined:</strong> {{ $user->created_at?->format('M d, Y') }}</p>
                    <p class="mb-2"><strong>Phone:</strong> {{ $user->phone ?? '-' }}</p>
                    <p class="mb-0"><strong>Member Code:</strong> {{ $user->user_code ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="+966555555555">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="user_code" class="form-label">Member Code</label>
                            <input type="text" id="user_code" name="user_code" class="form-control @error('user_code') is-invalid @enderror" value="{{ old('user_code', $user->user_code) }}" placeholder="ELX-001">
                            @error('user_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="avatar" class="form-label">Avatar</label>
                            <input type="file" id="avatar" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload a JPG, PNG, or WEBP image up to 2MB.</div>
                        </div>

                        @if($user->avatar)
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="remove_avatar" name="remove_avatar">
                                    <label class="form-check-label" for="remove_avatar">
                                        Remove current avatar and fall back to initials
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
