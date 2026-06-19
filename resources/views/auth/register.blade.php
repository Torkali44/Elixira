@extends('layouts.framer')

@section('title', app()->getLocale() === 'ar' ? 'إنشاء حساب - Elixira' : 'Register - Elixira')

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
    .auth-phone-row {
        display: flex;
        gap: 0.75rem;
        align-items: stretch;
        margin-bottom: 0.5rem;
    }
    .auth-phone-codes {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }
    .auth-phone-code {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.65rem 0.75rem;
        border-radius: 10px;
        border: 1px solid var(--elx-border);
        background: rgba(255, 255, 255, 0.05);
        cursor: pointer;
        color: var(--elx-white);
        font-size: 0.85rem;
        font-weight: 600;
        transition: var(--elx-transition);
    }
    .auth-phone-code input { display: none; }
    .auth-phone-code.active,
    .auth-phone-code:has(input:checked) {
        border-color: var(--elx-cyan);
        background: rgba(74, 200, 246, 0.12);
        color: var(--elx-cyan);
    }
    .auth-phone-code img {
        width: 22px;
        height: 15px;
        border-radius: 2px;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="elx-container">
        <div class="auth-card" data-animate>
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--elx-accent);">{{ __('app.auth.register_title') }}</h1>
                <p style="color: var(--elx-gray);">{{ __('app.auth.register_subtitle') }}</p>
            </div>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <label class="auth-label">{{ __('app.auth.full_name') }}</label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}" required autofocus autocomplete="name">
                <x-input-error :messages="$errors->get('name')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 0.5rem;" />

                <label class="auth-label">{{ __('app.auth.email') }}</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autocomplete="username">
                <x-input-error :messages="$errors->get('email')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 0.5rem;" />

                @php
                    $phoneCountry = old('phone_country_code', '+966');
                    $ksaFlag = app(\App\Support\ItemPricingService::class)->countryFlag('KSA');
                    $uaeFlag = app(\App\Support\ItemPricingService::class)->countryFlag('UAE');
                @endphp
                <label class="auth-label">{{ __('app.auth.phone') }}</label>
                <div class="auth-phone-row">
                    <div class="auth-phone-codes">
                        <label class="auth-phone-code {{ $phoneCountry === '+966' ? 'active' : '' }}">
                            <input type="radio" name="phone_country_code" value="+966" @checked($phoneCountry === '+966')>
                            @if($ksaFlag)<img src="{{ $ksaFlag }}" alt="KSA">@endif
                            <span>+966</span>
                        </label>
                        <label class="auth-phone-code {{ $phoneCountry === '+971' ? 'active' : '' }}">
                            <input type="radio" name="phone_country_code" value="+971" @checked($phoneCountry === '+971')>
                            @if($uaeFlag)<img src="{{ $uaeFlag }}" alt="UAE">@endif
                            <span>+971</span>
                        </label>
                    </div>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" required placeholder="{{ __('app.auth.phone_placeholder') }}" style="margin-bottom: 0; flex: 1;">
                </div>
                <x-input-error :messages="$errors->get('phone')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 0.5rem;" />
                <x-input-error :messages="$errors->get('phone_country_code')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 0.5rem;" />

                <label class="auth-label">{{ __('app.auth.password') }}</label>
                <input type="password" name="password" class="form-input" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 0.5rem;" />

                <label class="auth-label">{{ __('app.auth.confirm_password') }}</label>
                <input type="password" name="password_confirmation" class="form-input" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password_confirmation')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 1rem;" />

                <label class="auth-label">{{ __('app.auth.account_type') }}</label>
                <select name="account_type" id="account_type" class="form-input" required onchange="toggleVendorFields()" style="background-color: #13252d; color: white;">
                    <option value="customer" {{ old('account_type') === 'customer' ? 'selected' : '' }} style="background-color: #13252d;">{{ __('app.auth.customer') }}</option>
                    <option value="vendor" {{ old('account_type') === 'vendor' ? 'selected' : '' }} style="background-color: #13252d;">{{ __('app.auth.vendor') }}</option>
                </select>
                <x-input-error :messages="$errors->get('account_type')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 0.5rem;" />

                <div id="vendor_fields" style="display: {{ old('account_type') === 'vendor' ? 'block' : 'none' }}; margin-top: 1rem; padding: 1.5rem; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px dashed rgba(74, 200, 246, 0.3);">
                    <div style="color: var(--elx-cyan); font-size: 0.85rem; margin-bottom: 1rem;">
                        <i class="fas fa-store"></i> {{ __('app.auth.vendor_hint') }}
                    </div>
                    <label class="auth-label" style="margin-top: 0;">{{ __('app.auth.brand_name') }}</label>
                    <input type="text" name="brand_name" id="brand_name" class="form-input" value="{{ old('brand_name') }}" placeholder="{{ __('app.auth.brand_placeholder') }}">
                    <x-input-error :messages="$errors->get('brand_name')" style="color: #ff8a8a; font-size: 0.8rem; margin-bottom: 0.5rem;" />
                </div>

                <script>
                    function toggleVendorFields() {
                        const type = document.getElementById('account_type').value;
                        const fields = document.getElementById('vendor_fields');
                        const brandInput = document.getElementById('brand_name');

                        if (type === 'vendor') {
                            fields.style.display = 'block';
                            brandInput.required = true;
                        } else {
                            fields.style.display = 'none';
                            brandInput.required = false;
                            brandInput.value = '';
                        }
                    }

                    // Run on load to set initial state
                    document.addEventListener('DOMContentLoaded', toggleVendorFields);

                    document.querySelectorAll('.auth-phone-code input').forEach((input) => {
                        input.addEventListener('change', () => {
                            document.querySelectorAll('.auth-phone-code').forEach((label) => label.classList.remove('active'));
                            input.closest('.auth-phone-code')?.classList.add('active');
                        });
                    });
                </script>

                <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem; margin-top: 1.5rem;">
                    {{ __('app.auth.create_account') }}
                </button>

                <div style="margin-top: 2rem; text-align: center; font-size: 0.85rem;">
                    <span style="color: var(--elx-gray);">{{ __('app.auth.has_account') }}</span>
                    <a href="{{ route('login') }}" style="color: var(--elx-cyan); text-decoration: none; font-weight: 700; margin-left: 0.5rem;">{{ __('app.auth.login_btn') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

