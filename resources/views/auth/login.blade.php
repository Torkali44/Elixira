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
    .password-wrap {
        position: relative;
        margin-bottom: 0.5rem;
    }
    .password-wrap .form-input {
        margin-bottom: 0;
        padding-inline-end: 3rem;
    }
    .password-toggle-btn {
        position: absolute;
        top: 50%;
        inset-inline-end: 1rem;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
        padding: 0.25rem;
        line-height: 1;
        font-size: 1rem;
        transition: color 0.2s;
        z-index: 2;
    }
    .password-toggle-btn:hover { color: var(--elx-cyan); }
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
                <div class="password-wrap">
                    <input type="password" name="password" id="login-password" class="form-input" required autocomplete="current-password">
                    <button type="button" class="password-toggle-btn" onclick="togglePassword('login-password', this)" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
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

<script>
function togglePassword(inputId, btn) {
    var input = document.getElementById(inputId);
    var icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
