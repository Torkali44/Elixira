@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">{{ __('admin.users_page.edit_title') }}</h2>
        <p class="text-muted mb-0">{{ __('admin.users_page.edit_subtitle') }}</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">{{ __('admin.users_page.back') }}</a>
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
                    <span class="badge {{ $user->is_suspended ? 'bg-danger' : 'bg-success' }}">{{ $user->is_suspended ? __('admin.users_page.suspended') : __('admin.users_page.active') }}</span>
                </div>
                <hr>
                <div class="text-start">
                    <p class="mb-2"><strong>{{ __('admin.users_page.joined') }}:</strong> {{ $user->created_at?->format('M d, Y') }}</p>
                    <p class="mb-2"><strong>{{ __('admin.users_page.phone') }}:</strong> {{ $user->phone ?? '-' }}</p>
                    <p class="mb-0"><strong>{{ __('admin.users_page.code') }}:</strong> {{ $user->user_code ?? '-' }}</p>
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
                            <label for="name" class="form-label">{{ __('admin.users_page.full_name') }}</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">{{ __('admin.users_page.email_address') }}</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">{{ __('admin.users_page.phone') }}</label>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="+966555555555">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="user_code" class="form-label">{{ __('admin.users_page.code') }}</label>
                            <input type="text" id="user_code" name="user_code" class="form-control @error('user_code') is-invalid @enderror" value="{{ old('user_code', $user->user_code) }}" placeholder="ELX-001">
                            @error('user_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label">{{ __('admin.users_page.user_role') }}</label>
                            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>{{ __('admin.users_page.user') }}</option>
                                <option value="vendor" {{ old('role', $user->role) === 'vendor' ? 'selected' : '' }}>{{ __('admin.users_page.vendors') }}</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __('admin.users_page.admins') }}</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="avatar" class="form-label">{{ __('admin.users_page.avatar') }}</label>
                            <input type="file" id="avatar" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('admin.users_page.avatar_hint') }}</div>
                        </div>

                        @if($user->avatar)
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="remove_avatar" name="remove_avatar">
                                    <label class="form-check-label" for="remove_avatar">
                                        {{ __('admin.users_page.remove_avatar') }}
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary">{{ __('admin.users_page.save_changes') }}</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">{{ __('admin.users_page.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
