@extends('layouts.admin')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="mb-1">{{ __('admin.users_page.title') }}</h2>
        <p class="text-muted mb-0">{{ __('admin.users_page.subtitle') }}</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted text-uppercase">{{ __('admin.users_page.total_users') }}</small>
                <h3 class="mt-2 mb-0">{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted text-uppercase">{{ __('admin.users_page.admins') }}</small>
                <h3 class="mt-2 mb-0">{{ $stats['admins'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted text-uppercase">{{ __('admin.users_page.vendors') }}</small>
                <h3 class="mt-2 mb-0">{{ $stats['vendors'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted text-uppercase">{{ __('admin.users_page.suspended') }}</small>
                <h3 class="mt-2 mb-0">{{ $stats['suspended'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label for="search" class="form-label">{{ __('admin.users_page.search') }}</label>
                <input type="text" id="search" name="search" class="form-control" placeholder="{{ __('admin.users_page.search_placeholder') }}" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label for="role" class="form-label">{{ __('admin.users_page.role') }}</label>
                <select id="role" name="role" class="form-select">
                    <option value="">{{ __('admin.users_page.all_roles') }}</option>
                    <option value="admin" @selected(request('role') === 'admin')>{{ __('admin.users_page.admins') }}</option>
                    <option value="vendor" @selected(request('role') === 'vendor')>{{ __('admin.users_page.vendors') }}</option>
                    <option value="user" @selected(request('role') === 'user')>{{ __('admin.users_page.user') }}</option>
                </select>
            </div>
            
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">{{ __('admin.users_page.filter') }}</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">{{ __('admin.users_page.reset') }}</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>{{ __('admin.users_page.user') }}</th>
                        <th>{{ __('admin.users_page.country') }}</th>
                        <th>{{ __('admin.users_page.role') }}</th>
                        <th>{{ __('admin.users_page.phone') }}</th>
                        <th>{{ __('admin.users_page.email') }}</th>
                        <th>{{ __('admin.users_page.code') }}</th>
                        <th>{{ __('admin.users_page.status') }}</th>
                        <th class="text-end">{{ __('admin.users_page.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <x-user-avatar :user="$user" size="48" />
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold d-flex align-items-center gap-1">
                                        @if($user->role === 'vendor' && $user->vendorProfile && $user->vendorProfile->service_countries)
                                            @foreach($user->vendorProfile->service_countries as $country)
                                                @php
                                                    $flagSrc = '';
                                                    if (stripos($country, 'Saudi') !== false || stripos($country, 'KSA') !== false) {
                                                        $flagSrc = asset('images/sa.png');
                                                    } elseif (stripos($country, 'Emirates') !== false || stripos($country, 'UAE') !== false) {
                                                        $flagSrc = asset('images/AE.png');
                                                    }
                                                @endphp
                                                @if($flagSrc)
                                                    <img src="{{ $flagSrc }}" alt="{{ $country }}" width="20" height="14" style="border-radius: 2px;" title="{{ $country }}">
                                                @endif
                                            @endforeach
                                        @else
                                            <x-phone-flag :phone="$user->phone" :show-phone="false" />
                                        @endif
                                    </div>
                                    @if($user->birth_date)
                                        <small class="text-muted">
                                            {{ __('admin.users_page.years_old', ['age' => \Carbon\Carbon::parse($user->birth_date)->age]) }}
                                        </small>
                                    @endif
                                </div>
                            </td>

                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-primary">{{ __('admin.users_page.admins') }}</span>
                                @elseif($user->role === 'vendor')
                                    <span class="badge bg-info">{{ __('admin.users_page.vendors') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('admin.users_page.user') }}</span>
                                @endif
                            </td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->user_code ?? '-' }}</td>
                            <td>
                                @if($user->is_suspended)
                                    <span class="badge bg-danger">{{ __('admin.users_page.suspended') }}</span>
                                @else
                                    <span class="badge bg-success">{{ __('admin.users_page.active') }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    @if($user->role !== 'admin' && $user->id !== auth()->id())
                                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $user->is_suspended ? 'btn-success' : 'btn-warning' }}">
                                                {{ $user->is_suspended ? __('admin.users_page.activate') : __('admin.users_page.suspend') }}
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" data-confirm="{{ __('admin.users_page.delete_confirm') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-light text-muted" disabled>
                                            {{ __('admin.users_page.protected') }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">{{ __('admin.users_page.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
