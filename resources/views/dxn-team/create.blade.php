@extends('layouts.framer')

@section('title', __('dxn_team.page_title'))

@section('content')
<div class="page-content">
    <div class="elx-container" style="max-width: 760px;">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">{{ __('dxn_team.hero_title') }}</span>
            </h1>
            <p class="elx-hero__subtitle">{{ __('dxn_team.hero_subtitle') }}</p>
        </div>

        <div class="contact-card" data-animate>
            @if(session('success'))
                <div style="background: rgba(74, 200, 246, 0.1); color: var(--elx-cyan); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid var(--elx-cyan);">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('dxn-team.store') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <input type="text" class="form-input" name="name" value="{{ old('name', $user?->name) }}" placeholder="{{ __('dxn_team.form_name') }}" required>
                    <input type="email" class="form-input" name="email" value="{{ old('email', $user?->email) }}" placeholder="{{ __('dxn_team.form_email') }}" required>
                </div>
                <input type="text" class="form-input" name="phone" value="{{ old('phone', $user?->phone) }}" placeholder="{{ __('dxn_team.form_phone') }}" required>
                <input type="text" class="form-input" name="member_code" value="{{ old('member_code', $user?->user_code) }}" placeholder="{{ __('dxn_team.form_member_code') }}">
                <select class="form-input" name="country" required style="appearance: auto;">
                    <option value="">{{ __('dxn_team.form_country_placeholder') }}</option>
                    <option value="KSA" @selected(old('country') === 'KSA')>{{ __('shop.country_ksa') }}</option>
                    <option value="UAE" @selected(old('country') === 'UAE')>{{ __('shop.country_uae') }}</option>
                </select>
                <textarea class="form-input form-textarea" name="team_goal" rows="3" placeholder="{{ __('dxn_team.form_team_goal') }}">{{ old('team_goal') }}</textarea>
                <textarea class="form-input form-textarea" name="message" rows="4" placeholder="{{ __('dxn_team.form_message') }}">{{ old('message') }}</textarea>

                <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem;">
                    {{ __('dxn_team.form_submit') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
