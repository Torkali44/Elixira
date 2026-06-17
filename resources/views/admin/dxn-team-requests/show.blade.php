@extends('layouts.admin')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <a href="{{ route('admin.dxn-team-requests.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fas fa-arrow-left"></i> {{ __('admin.common.back') }}
        </a>
        <h2 class="mb-1">{{ __('admin.dxn_team_requests.details') }}</h2>
        <p class="text-muted mb-0">#{{ $request->id }} · {{ $request->created_at->format('Y-m-d H:i') }}
            @if($request->isExistingMemberRequest())
                · <span class="badge bg-info">{{ __('admin.dxn_team_requests.type_existing') }}</span>
            @endif
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener" class="btn btn-success">
            <i class="fab fa-whatsapp"></i> {{ __('admin.dxn_team_requests.open_whatsapp') }}
        </a>
        @php
            $statusBadge = match($request->status) {
                'approved' => 'success',
                'rejected' => 'danger',
                default => 'warning',
            };
        @endphp
        <span class="badge bg-{{ $statusBadge }} fs-6 px-3 py-2">
            {{ __('admin.dxn_team_requests.status_'.$request->status) }}
        </span>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">{{ __('admin.dxn_team_requests.section_personal') }}</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_name') }}</dt>
                    <dd class="col-sm-8">{{ $request->name }}</dd>
                    @if($request->gender)
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_gender') }}</dt>
                        <dd class="col-sm-8">{{ __('dxn_team.gender_'.$request->gender) }}</dd>
                    @endif
                    @if($request->date_of_birth)
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_dob') }}</dt>
                        <dd class="col-sm-8">{{ $request->date_of_birth->format('Y-m-d') }}</dd>
                    @endif
                    <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_email') }}</dt>
                    <dd class="col-sm-8"><a href="mailto:{{ $request->email }}">{{ $request->email }}</a></dd>
                    <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_phone') }}</dt>
                    <dd class="col-sm-8">{{ $request->phone }}</dd>
                    @if($request->nationality)
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_nationality') }}</dt>
                        <dd class="col-sm-8">{{ $request->nationality }}</dd>
                    @endif
                    @if($request->id_number)
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_id_number') }}</dt>
                        <dd class="col-sm-8">{{ $request->id_number }}</dd>
                    @endif
                    @if($request->passport_number)
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_passport') }}</dt>
                        <dd class="col-sm-8">{{ $request->passport_number }}</dd>
                    @endif
                    <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_country') }}</dt>
                    <dd class="col-sm-8">{{ $request->country ?: '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">{{ __('admin.dxn_team_requests.section_sponsor') }}</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_sponsor_code') }}</dt>
                    <dd class="col-sm-8">{{ $request->sponsor_code ?: ($request->member_code ?: '—') }}</dd>
                    <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_sponsor_name') }}</dt>
                    <dd class="col-sm-8">{{ $request->sponsor_name ?: '—' }}</dd>
                    @if($request->contract_accepted_at)
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_contract') }}</dt>
                        <dd class="col-sm-8">{{ $request->contract_accepted_at->format('Y-m-d H:i') }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    @if($request->has_heir)
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">{{ __('admin.dxn_team_requests.section_heir') }}</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_heir_name') }}</dt>
                        <dd class="col-sm-8">{{ $request->heir_name ?: '—' }}</dd>
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_heir_relationship') }}</dt>
                        <dd class="col-sm-8">{{ $request->heir_relationship ?: '—' }}</dd>
                        @if($request->heir_id_number)
                            <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_heir_id') }}</dt>
                            <dd class="col-sm-8">{{ $request->heir_id_number }}</dd>
                        @endif
                        @if($request->heir_passport_number)
                            <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_heir_passport') }}</dt>
                            <dd class="col-sm-8">{{ $request->heir_passport_number }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    @endif

    @if($request->address)
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">{{ __('admin.dxn_team_requests.section_address') }}</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_address') }}</dt>
                        <dd class="col-sm-8" style="white-space: pre-wrap;">{{ $request->address }}</dd>
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_address_country') }}</dt>
                        <dd class="col-sm-8">{{ $request->address_country ?: '—' }}</dd>
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_address_city') }}</dt>
                        <dd class="col-sm-8">{{ $request->address_city ?: '—' }}</dd>
                        <dt class="col-sm-4">{{ __('admin.dxn_team_requests.col_postal_code') }}</dt>
                        <dd class="col-sm-8">{{ $request->postal_code ?: '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    @endif

    @if($request->isLegacyTeamRequest())
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">{{ __('admin.dxn_team_requests.section_legacy_team') }}</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        @if($request->team_name)
                            <dt class="col-sm-3">{{ __('admin.dxn_team_requests.col_team_name') }}</dt>
                            <dd class="col-sm-9">{{ $request->team_name }}</dd>
                        @endif
                        @if($request->team_size)
                            <dt class="col-sm-3">{{ __('admin.dxn_team_requests.col_team_size') }}</dt>
                            <dd class="col-sm-9">{{ $request->team_size }}</dd>
                        @endif
                        @if(!empty($request->team_members))
                            <dt class="col-sm-3">{{ __('admin.dxn_team_requests.col_team_members') }}</dt>
                            <dd class="col-sm-9">
                                <ul class="mb-0">
                                    @foreach($request->team_members as $member)
                                        <li>{{ $member['name'] ?? '—' }} — {{ $member['contact'] ?? '—' }}</li>
                                    @endforeach
                                </ul>
                            </dd>
                        @endif
                        @if($request->team_goal)
                            <dt class="col-sm-3">{{ __('admin.dxn_team_requests.col_team_goal') }}</dt>
                            <dd class="col-sm-9">{{ $request->team_goal }}</dd>
                        @endif
                        @if($request->message)
                            <dt class="col-sm-3">{{ __('admin.dxn_team_requests.col_message') }}</dt>
                            <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $request->message }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    @endif
</div>

<form action="{{ route('admin.dxn-team-requests.update', $request) }}" method="POST" enctype="multipart/form-data" class="card border-0 shadow-sm mb-3">
    @csrf
    @method('PATCH')
    <div class="card-header bg-white fw-bold">{{ __('admin.dxn_team_requests.manage_request') }}</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('admin.dxn_team_requests.col_status') }}</label>
                <select name="status" class="form-select">
                    @foreach(['pending', 'approved', 'rejected'] as $status)
                        <option value="{{ $status }}" @selected($request->status === $status)>{{ __('admin.dxn_team_requests.status_'.$status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('admin.dxn_team_requests.assigned_dxn_code') }}</label>
                <input type="text" name="assigned_dxn_member_code" class="form-control @error('assigned_dxn_member_code') is-invalid @enderror" value="{{ old('assigned_dxn_member_code', $request->assigned_dxn_member_code ?: ($request->isExistingMemberRequest() ? $request->member_code : '')) }}" placeholder="45871236">
                @error('assigned_dxn_member_code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <div class="form-text">{{ __('admin.dxn_team_requests.assigned_dxn_code_hint') }}</div>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('admin.dxn_team_requests.dxn_tag_color') }}</label>
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <input type="color" name="dxn_tag_color" class="form-control form-control-color" value="{{ old('dxn_tag_color', $request->user?->dxn_tag_color ?: '#00ff88') }}">
                    @foreach($tagColors as $color)
                        <button type="button" class="btn btn-sm border" style="width:28px;height:28px;background:{{ $color }};" onclick="document.querySelector('[name=dxn_tag_color]').value='{{ $color }}'"></button>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('admin.dxn_team_requests.dxn_badge') }}</label>
                <input type="file" name="dxn_badge_image" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('admin.dxn_team_requests.admin_notes') }}</label>
                <textarea name="admin_notes" class="form-control" rows="2">{{ old('admin_notes', $request->admin_notes) }}</textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">{{ __('admin.common.save') }}</button>
    </div>
</form>

<form action="{{ route('admin.dxn-team-requests.destroy', $request) }}" method="POST" data-confirm="{{ __('admin.dxn_team_requests.confirm_delete') }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger">{{ __('admin.common.delete') }}</button>
</form>
@endsection
