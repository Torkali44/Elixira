@extends('layouts.framer')

@section('title', 'Register - Elixira')

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
                <h1 style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--elx-accent);">Join Elixira</h1>
                <p style="color: var(--elx-gray);">Create an account for faster checkout and exclusive offers.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <label class="auth-label">Full Name</label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}" required autofocus autocomplete="name">
                <x-input-error :messages="$errors->get('name')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 0.5rem;" />

                <label class="auth-label">Email Address</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autocomplete="username">
                <x-input-error :messages="$errors->get('email')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 0.5rem;" />

                <label class="auth-label">Avatar (Optional)</label>
                <input type="file" name="avatar" class="form-input" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                <x-input-error :messages="$errors->get('avatar')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 0.5rem;" />

                <label class="auth-label">Password</label>
                <input type="password" name="password" class="form-input" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 0.5rem;" />

                <label class="auth-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-input" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password_confirmation')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 1rem;" />

                <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem; margin-top: 1.5rem;">
                    Create Account
                </button>

                <div style="margin-top: 2rem; text-align: center; font-size: 0.85rem;">
                    <span style="color: var(--elx-gray);">Already have an account?</span>
                    <a href="{{ route('login') }}" style="color: var(--elx-cyan); text-decoration: none; font-weight: 700; margin-left: 0.5rem;">Log in</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
