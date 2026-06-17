@extends('layouts.framer')

@section('title', __('dxn_team.status_page_title'))

@section('head')
<style>
    .dxn-status-card {
        background: var(--elx-glass);
        border: 1px solid var(--elx-border);
        border-radius: 24px;
        padding: 2rem;
        max-width: 720px;
        margin: 0 auto;
    }
    .dxn-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    .dxn-status-badge.pending { background: rgba(255,193,7,0.15); color: #ffc107; border: 1px solid rgba(255,193,7,0.35); }
    .dxn-status-badge.approved { background: rgba(40,167,69,0.15); color: #5dd879; border: 1px solid rgba(40,167,69,0.35); }
    .dxn-status-badge.rejected { background: rgba(220,53,69,0.15); color: #ff8a8a; border: 1px solid rgba(220,53,69,0.35); }
    .dxn-status-meta { color: rgba(255,255,255,0.55); font-size: 0.9rem; margin-bottom: 1.5rem; }
    .dxn-status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .dxn-status-item label { display: block; font-size: 0.75rem; color: rgba(255,255,255,0.45); margin-bottom: 0.25rem; }
    .dxn-status-item div { color: #fff; font-weight: 600; }
    @media (max-width: 767px) { .dxn-status-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">{{ __('dxn_team.status_heading') }}</span>
            </h1>
        </div>

        <div class="dxn-status-card" data-animate>
            @php
                $statusClass = match($application->status) {
                    'approved' => 'approved',
                    'rejected' => 'rejected',
                    default => 'pending',
                };
                $statusMessage = match($application->status) {
                    'approved' => __('dxn_team.status_approved'),
                    'rejected' => __('dxn_team.status_rejected'),
                    default => __('dxn_team.status_pending'),
                };
            @endphp

            <span class="dxn-status-badge {{ $statusClass }}">
                {{ __('admin.dxn_team_requests.status_'.$application->status) }}
            </span>
            <p class="dxn-status-meta">{{ __('dxn_team.status_submitted_at', ['date' => $application->created_at->format('Y-m-d H:i')]) }}</p>
            <p style="color: var(--elx-light); margin-bottom: 1.5rem;">{{ $statusMessage }}</p>

            <div class="dxn-status-grid">
                <div class="dxn-status-item"><label>{{ __('admin.dxn_team_requests.col_name') }}</label><div>{{ $application->name }}</div></div>
                <div class="dxn-status-item"><label>{{ __('admin.dxn_team_requests.col_sponsor_code') }}</label><div>{{ $application->sponsor_code ?: '—' }}</div></div>
                <div class="dxn-status-item"><label>{{ __('admin.dxn_team_requests.col_email') }}</label><div>{{ $application->email }}</div></div>
                <div class="dxn-status-item"><label>{{ __('admin.dxn_team_requests.col_phone') }}</label><div>{{ $application->phone }}</div></div>
                <div class="dxn-status-item"><label>{{ __('admin.dxn_team_requests.col_country') }}</label><div>{{ $application->country }}</div></div>
            </div>

            <a href="{{ route('dxn-distributor.create') }}" class="elx-btn elx-btn--glass" style="margin-top: 1.5rem;">
                {{ __('dxn_team.status_back_to_form') }}
            </a>
            @if(!empty($whatsAppUrl))
                <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener" class="elx-btn elx-btn--primary" style="margin-top: 1rem; margin-inline-start: 0.5rem;">
                    <i class="fab fa-whatsapp"></i> {{ __('dxn_team.send_whatsapp') }}
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($showStatusPopup)
<script>
    Swal.fire({
        icon: @json($application->status === 'approved' ? 'success' : ($application->status === 'rejected' ? 'error' : 'info')),
        title: @json(__('dxn_team.status_popup_title')),
        text: @json($statusMessage),
        confirmButtonText: @json(__('app.confirm')),
    });
</script>
@endif
@endsection
