@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.dxn-team-requests.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left"></i> {{ __('admin.common.back') }}
    </a>
    <h2 class="mb-0">{{ __('admin.dxn_team_requests.details') }}</h2>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <p><strong>{{ __('admin.dxn_team_requests.col_name') }}:</strong> {{ $request->name }}</p>
        <p><strong>{{ __('admin.dxn_team_requests.col_email') }}:</strong> {{ $request->email }}</p>
        <p><strong>{{ __('admin.dxn_team_requests.col_phone') }}:</strong> {{ $request->phone }}</p>
        <p><strong>{{ __('admin.dxn_team_requests.col_member_code') }}:</strong> {{ $request->member_code ?: '—' }}</p>
        <p><strong>{{ __('admin.dxn_team_requests.col_country') }}:</strong> {{ $request->country }}</p>
        @if($request->team_goal)
            <p><strong>{{ __('admin.dxn_team_requests.col_team_goal') }}:</strong> {{ $request->team_goal }}</p>
        @endif
        @if($request->message)
            <p style="white-space: pre-wrap;"><strong>{{ __('admin.dxn_team_requests.col_message') }}:</strong><br>{{ $request->message }}</p>
        @endif
    </div>
</div>

<form action="{{ route('admin.dxn-team-requests.update', $request) }}" method="POST" class="card shadow-sm mb-3">
    @csrf
    @method('PATCH')
    <div class="card-body d-flex flex-wrap gap-2 align-items-center">
        <label class="mb-0">{{ __('admin.dxn_team_requests.col_status') }}:</label>
        <select name="status" class="form-select" style="width: auto;">
            @foreach(['pending', 'approved', 'rejected'] as $status)
                <option value="{{ $status }}" @selected($request->status === $status)>{{ __('admin.dxn_team_requests.status_'.$status) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">{{ __('admin.common.save') }}</button>
    </div>
</form>

<form action="{{ route('admin.dxn-team-requests.destroy', $request) }}" method="POST" data-confirm="{{ __('admin.dxn_team_requests.confirm_delete') }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger">{{ __('admin.common.delete') }}</button>
</form>
@endsection
