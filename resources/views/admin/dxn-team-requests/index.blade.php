@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ __('admin.dxn_team_requests.title') }}</h2>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ __('admin.dxn_team_requests.col_name') }}</th>
                    <th>{{ __('admin.dxn_team_requests.col_country') }}</th>
                    <th>{{ __('admin.dxn_team_requests.col_status') }}</th>
                    <th>{{ __('admin.dxn_team_requests.col_date') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $item)
                    <tr class="{{ $item->read_at ? '' : 'table-warning' }}">
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->country }}</td>
                        <td>{{ __('admin.dxn_team_requests.status_'.$item->status) }}</td>
                        <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.dxn-team-requests.show', $item) }}" class="btn btn-sm btn-outline-primary">{{ __('admin.common.view') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">{{ __('admin.dxn_team_requests.empty') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $requests->links() }}</div>
@endsection
