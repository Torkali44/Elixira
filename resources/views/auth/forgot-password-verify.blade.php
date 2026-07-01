@extends('layouts.framer')

@section('title', app()->getLocale() === 'ar' ? 'رمز إعادة التعيين - Elixira' : 'Reset Code - Elixira')

@section('head')
@include('auth.partials.auth-styles')
@endsection

@section('content')
<div class="auth-page">
    <div class="elx-container">
        <div class="auth-card" data-animate>
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 2rem; margin-bottom: 0.5rem; color: var(--elx-accent);">{{ __('app.auth.password_reset_verify_title') }}</h1>
                <p style="color: var(--elx-gray); line-height: 1.7;">{{ __('app.auth.password_reset_verify_subtitle') }}</p>
                <p style="color: var(--elx-cyan); font-size: 0.9rem; margin-top: 0.75rem; word-break: break-all;">{{ $email }}</p>
            </div>

            @if (session('status'))
                <div style="background: rgba(0, 255, 136, 0.1); border: 1px solid rgba(0, 255, 136, 0.25); color: #00ff88; padding: 0.85rem 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.verify.submit') }}">
                @csrf

                <label class="auth-label">{{ __('app.auth.verify_otp_label') }}</label>
                <input type="text" name="code" class="otp-input" inputmode="numeric" pattern="[0-9]*" maxlength="6" required autofocus autocomplete="one-time-code">
                <x-input-error :messages="$errors->get('code')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 1rem;" />

                <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem; margin-top: 1rem;">
                    {{ __('app.auth.password_reset_verify_btn') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
