@extends('layouts.framer')

@section('title', app()->getLocale() === 'ar' ? 'نسيت كلمة المرور - Elixira' : 'Forgot Password - Elixira')

@section('head')
@include('auth.partials.auth-styles')
@endsection

@section('content')
<div class="auth-page">
    <div class="elx-container">
        <div class="auth-card" data-animate>
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 2rem; margin-bottom: 0.5rem; color: var(--elx-accent);">{{ __('app.auth.forgot_password_title') }}</h1>
                <p style="color: var(--elx-gray); line-height: 1.7;">{{ __('app.auth.forgot_password_subtitle') }}</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <label class="auth-label">{{ __('app.auth.email') }}</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus autocomplete="username">
                <x-input-error :messages="$errors->get('email')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 1rem;" />

                <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem; margin-top: 1rem;">
                    {{ __('app.auth.forgot_password_send') }}
                </button>

                <div style="margin-top: 1.5rem; text-align: center;">
                    <a href="{{ route('login') }}" style="color: var(--elx-cyan); text-decoration: none; font-size: 0.9rem;">{{ __('app.auth.back_to_login') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
