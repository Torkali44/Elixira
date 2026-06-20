@extends('layouts.framer')

@section('title', app()->getLocale() === 'ar' ? 'تسجيل الدخول - Elixira' : 'Log in - Elixira')

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
        max-width: 500px;
        margin: auto;
    }
    .form-input {
        width: 100%;
        padding: 0.8rem 1.2rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--elx-border);
        border-radius: 10px;
        color: var(--elx-white);
        margin-bottom: 0.5rem;
        outline: none;
        transition: var(--elx-transition);
    }
    .form-input:focus { border-color: var(--elx-cyan); }
    .auth-label {
        display: block;
        color: var(--elx-gray);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="elx-container">
        <div class="auth-card" data-animate>
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--elx-accent);">{{ __('app.auth.login_title') }}</h1>
                <p style="color: var(--elx-gray);">{{ __('app.auth.login_subtitle') }}</p>
            </div>

            <x-auth-session-status class="mb-4" style="color: var(--elx-cyan); text-align: center;" :status="session('status')" />

            @if (session('error'))
                <div style="background: rgba(255, 77, 77, 0.1); border: 1px solid rgba(255, 77, 77, 0.25); color: #ff8a8a; padding: 0.85rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: 0.9rem; text-align: center;">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login', [], false) }}">
                @csrf

                <label class="auth-label">{{ __('app.auth.email') }}</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus autocomplete="username">
                <x-input-error :messages="$errors->get('email')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 1rem;" />

                <label class="auth-label">{{ __('app.auth.password') }}</label>
                <input type="password" name="password" class="form-input" required autocomplete="current-password">
                <x-input-error :messages="$errors->get('password')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 1rem;" />

                <div style="display: flex; align-items: center; gap: 0.5rem; margin: 1.5rem 0;">
                    <input id="remember_me" type="checkbox" name="remember" style="accent-color: var(--elx-cyan);">
                    <label for="remember_me" style="color: var(--elx-gray); font-size: 0.9rem;">{{ __('app.auth.remember_me') }}</label>
                </div>

                <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem;">
                    {{ __('app.auth.login_btn') }}
                </button>

                <div style="margin-top: 2rem; display: flex; justify-content: space-between; font-size: 0.85rem;">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color: var(--elx-gray); text-decoration: none;">{{ __('app.auth.forgot_password') }}</a>
                    @endif
                    <a href="{{ route('register') }}" style="color: var(--elx-cyan); text-decoration: none; font-weight: 700;">{{ __('app.auth.create_account') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

