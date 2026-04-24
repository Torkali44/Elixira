@extends('layouts.framer')

@section('title', 'Log in - Elixira')

@section('head')
<style>
    .auth-page {
        background-image: linear-gradient(rgba(5, 16, 23, 0.8), rgba(19, 37, 45, 0.7)), url('https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
        display: flex;
        align-items: center;
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
                <h1 style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--elx-accent);">Welcome back</h1>
                <p style="color: var(--elx-gray);">Sign in to manage your account or continue shopping.</p>
            </div>

            <x-auth-session-status class="mb-4" style="color: var(--elx-cyan); text-align: center;" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label class="auth-label">Email Address</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus autocomplete="username">
                <x-input-error :messages="$errors->get('email')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 1rem;" />

                <label class="auth-label">Password</label>
                <input type="password" name="password" class="form-input" required autocomplete="current-password">
                <x-input-error :messages="$errors->get('password')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 1rem;" />

                <div style="display: flex; align-items: center; gap: 0.5rem; margin: 1.5rem 0;">
                    <input id="remember_me" type="checkbox" name="remember" style="accent-color: var(--elx-cyan);">
                    <label for="remember_me" style="color: var(--elx-gray); font-size: 0.9rem;">Remember me</label>
                </div>

                <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem;">
                    Log in
                </button>

                <div style="margin-top: 2rem; display: flex; justify-content: space-between; font-size: 0.85rem;">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color: var(--elx-gray); text-decoration: none;">Forgot password?</a>
                    @endif
                    <a href="{{ route('register') }}" style="color: var(--elx-cyan); text-decoration: none; font-weight: 700;">Create an account</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
