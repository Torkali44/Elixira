@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ __('admin.avatar_options_admin.title') }}</h2>
    <a href="{{ route('admin.avatar-options.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> {{ __('admin.avatar_options_admin.add_avatar') }}
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><small class="text-muted">{{ __('admin.avatar_options_admin.total') }}</small><h4>{{ $stats['total'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><small class="text-muted">{{ __('admin.avatar_options_admin.active') }}</small><h4>{{ $stats['active'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><small class="text-muted">{{ __('admin.avatar_options_admin.inactive') }}</small><h4>{{ $stats['inactive'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><small class="text-muted">{{ __('admin.avatar_options_admin.used_by_users') }}</small><h4>{{ $stats['assigned'] }}</h4></div></div></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>{{ __('admin.avatar_options_admin.col_avatar') }}</th>
                        <th>{{ __('admin.avatar_options_admin.col_name') }}</th>
                        <th>{{ __('admin.avatar_options_admin.col_gender') }}</th>
                        <th>{{ __('admin.avatar_options_admin.col_sort') }}</th>
                        <th>{{ __('admin.avatar_options_admin.col_status') }}</th>
                        <th>{{ __('admin.avatar_options_admin.col_ratio') }}</th>
                        <th class="text-end">{{ __('admin.avatar_options_admin.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($avatarOptions as $avatar)
                        <tr>
                            <td><img src="{{ $avatar->image_url }}" alt="{{ $avatar->name }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;"></td>
                            <td>{{ $avatar->name }}</td>
                            <td>
                                @if($avatar->gender === 'male')
                                    <span class="badge bg-primary">{{ __('admin.avatar_options_admin.male') }}</span>
                                @elseif($avatar->gender === 'female')
                                    <span class="badge bg-danger" style="background-color: #e83e8c !important;">{{ __('admin.avatar_options_admin.female') }}</span>
                                @else
                                    <span class="badge bg-info">{{ __('admin.avatar_options_admin.both') }}</span>
                                @endif
                            </td>
                            <td>{{ $avatar->sort_order }}</td>
                            <td>
                                <span class="badge {{ $avatar->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $avatar->is_active ? __('admin.avatar_options_admin.active') : __('admin.avatar_options_admin.inactive') }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <strong>{{ __('admin.avatar_options_admin.usage_total', ['count' => $avatar->users_count]) }}</strong><br>
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
                                            <span class="text-primary">{{ __('admin.avatar_options_admin.m_ratio', ['percent' => round($malePercent)]) }}</span>
                                            <span class="text-danger">{{ __('admin.avatar_options_admin.f_ratio', ['percent' => round($femalePercent)]) }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">{{ __('admin.avatar_options_admin.not_used') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.avatar-options.toggle', $avatar) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-sm {{ $avatar->is_active ? 'btn-outline-warning' : 'btn-success' }}"
                                        title="{{ $avatar->is_active ? __('admin.avatar_options_admin.disable') : __('admin.avatar_options_admin.enable') }}">
                                        {{ $avatar->is_active ? __('admin.avatar_options_admin.disable') : __('admin.avatar_options_admin.enable') }}
                                    </button>
                                </form>
                                <a href="{{ route('admin.avatar-options.edit', $avatar) }}" class="btn btn-sm btn-outline-primary">{{ __('admin.avatar_options_admin.edit') }}</a>
                                <form action="{{ route('admin.avatar-options.destroy', $avatar) }}" method="POST" class="d-inline" data-confirm="{{ __('admin.avatar_options_admin.delete_confirm') }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">{{ __('admin.avatar_options_admin.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">{{ __('admin.avatar_options_admin.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $avatarOptions->links('pagination::bootstrap-5') }}
       
    </div>
</div>
@endsection
