@extends('layouts.framer')

@section('title', app()->getLocale() === 'ar' ? 'تأكيد البريد الإلكتروني - Elixira' : 'Verify Email - Elixira')

@section('head')
<style>
    .auth-page {
        background-image: linear-gradient(rgba(5, 16, 23, 0.8), rgba(19, 37, 45, 0.7)), url('https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding-top: 100px;
        padding-bottom: 60px;
    }
    .auth-card {
        background: var(--elx-glass);
        backdrop-filter: blur(42px);
        border: 1px solid var(--elx-border);
        border-radius: var(--elx-radius-sm);
        padding: 3rem;
        width: 100%;
        max-width: 560px;
        margin: auto;
    }
    .otp-input {
        width: 100%;
        padding: 1rem 1.2rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--elx-border);
        border-radius: 10px;
        color: var(--elx-white);
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: 0.45rem;
        text-align: center;
        outline: none;
        transition: var(--elx-transition);
    }
    .otp-input:focus { border-color: var(--elx-cyan); }
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="elx-container">
        <div class="auth-card" data-animate>
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="width: 64px; height: 64px; margin: 0 auto 1rem; border-radius: 50%; background: rgba(74, 200, 246, 0.12); border: 1px solid rgba(74, 200, 246, 0.35); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-shield-halved" style="color: var(--elx-cyan); font-size: 1.5rem;"></i>
                </div>
                <h1 style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 2rem; margin-bottom: 0.5rem; color: var(--elx-accent);">{{ __('app.auth.verify_title') }}</h1>
                <p style="color: var(--elx-gray); line-height: 1.7;">{{ __('app.auth.verify_otp_subtitle') }}</p>
                @auth
                    <p style="color: var(--elx-cyan); font-size: 0.9rem; margin-top: 0.75rem; word-break: break-all;">{{ auth()->user()->email }}</p>
                @endauth
            </div>

            @if (session('status') === 'verification-otp-sent')
                <div style="background: rgba(0, 255, 136, 0.1); border: 1px solid rgba(0, 255, 136, 0.25); color: #00ff88; padding: 0.85rem 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    {{ __('app.auth.verify_otp_sent') }}
                </div>
            @endif

            @if (session('error'))
                <div style="background: rgba(255, 77, 77, 0.1); border: 1px solid rgba(255, 77, 77, 0.25); color: #ff8a8a; padding: 0.85rem 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    {{ session('error') }}
                </div>
            @endif

            <p style="color: rgba(255,255,255,0.45); font-size: 0.82rem; margin-bottom: 1.25rem; text-align: center;">
                {{ __('app.auth.verify_otp_check_spam', ['email' => config('mail.from.address')]) }}
            </p>


            <form method="POST" action="{{ route('verification.verify', [], false) }}" style="margin-bottom: 1rem;">
                @csrf
                <label class="auth-label" style="display:block; color: var(--elx-gray); font-size: 0.9rem; margin-bottom: 0.5rem;">{{ __('app.auth.verify_otp_label') }}</label>
                <input
                    type="text"
                    name="code"
                    class="otp-input @error('code') is-invalid @enderror"
                    value="{{ old('code') }}"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    autocomplete="one-time-code"
                    placeholder="000000"
                    required
                    autofocus
                >
                @error('code')
                    <div style="color: #ff8a8a; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
                @enderror

                <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem; margin-top: 1.25rem;">
                    <i class="fas fa-check-circle" style="margin-right: 0.35rem;"></i>
                    {{ __('app.auth.verify_otp_submit') }}
                </button>
            </form>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <form method="POST" action="{{ route('verification.send', [], false) }}">
                    @csrf
                    <button type="submit" class="elx-btn elx-btn--ghost" style="width: 100%; justify-content: center; padding: 0.9rem;">
                        <i class="fas fa-paper-plane" style="margin-right: 0.35rem;"></i>
                        {{ __('app.auth.resend_verification') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout', [], false) }}">
                    @csrf
                    <button type="submit" class="elx-btn elx-btn--ghost" style="width: 100%; justify-content: center; padding: 0.9rem; opacity: 0.75;">
                        {{ __('app.auth.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
