@extends('layouts.framer')

@section('title', app()->getLocale() === 'ar' ? 'كلمة مرور جديدة - Elixira' : 'New Password - Elixira')

@section('head')
@include('auth.partials.auth-styles')
@endsection

@section('content')
<div class="auth-page">
    <div class="elx-container">
        <div class="auth-card" data-animate>
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 2rem; margin-bottom: 0.5rem; color: var(--elx-accent);">{{ __('app.auth.password_reset_new_title') }}</h1>
                <p style="color: var(--elx-gray); line-height: 1.7;">{{ __('app.auth.password_reset_new_subtitle') }}</p>
            </div>

            <form method="POST" action="{{ route('password.reset.submit') }}">
                @csrf

                <label class="auth-label">{{ __('app.auth.password') }}</label>
                <input type="password" name="password" class="form-input" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 1rem;" />

                <label class="auth-label">{{ __('app.auth.confirm_password') }}</label>
                <input type="password" name="password_confirmation" class="form-input" required autocomplete="new-password">

                <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem; margin-top: 1.5rem;">
                    {{ __('app.auth.password_reset_save') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
