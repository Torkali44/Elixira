@extends('layouts.framer')

@section('title', 'My Account - Elixira')

@section('head')
<style>
    .account-shell {
        display: grid;
        grid-template-columns: minmax(280px, 340px) minmax(0, 1fr);
        gap: 2rem;
        align-items: start;
    }

    .account-card {
        background: linear-gradient(180deg, rgba(19, 37, 45, 0.96), rgba(10, 26, 34, 0.96));
        border: 1px solid var(--elx-border);
        border-radius: 28px;
        padding: 2rem;
        box-shadow: 0 24px 70px rgba(0, 0, 0, 0.25);
    }

    .account-card + .account-card {
        margin-top: 1.5rem;
    }

    .account-sidebar {
        position: sticky;
        top: 110px;
    }

    .account-hero {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .account-hero__copy {
        max-width: 720px;
    }

    .account-hero__copy p {
        color: rgba(255, 255, 255, 0.72);
        max-width: 620px;
    }

    .account-hero__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
    }

    .account-avatar {
        width: 84px;
        height: 84px;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(74, 200, 246, 0.25);
    }

    .account-avatar-panel {
        display: grid;
        grid-template-columns: 92px minmax(0, 1fr);
        gap: 1rem;
        align-items: center;
        padding: 1rem;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .account-avatar-actions {
        display: grid;
        gap: 0.75rem;
    }

    .account-check {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.92rem;
    }

    .account-sidebar__top {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .account-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        border-radius: 999px;
        padding: 0.38rem 0.9rem;
        background: rgba(74, 200, 246, 0.1);
        color: var(--elx-cyan);
        font-size: 0.8rem;
        border: 1px solid rgba(74, 200, 246, 0.22);
    }

    .account-muted {
        color: var(--elx-gray);
        font-size: 0.92rem;
    }

    .account-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.9rem;
        margin-top: 1.5rem;
    }

    .account-stat {
        padding: 1rem;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .account-stat__value {
        display: block;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--elx-white);
    }

    .account-stat__label {
        font-size: 0.78rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--elx-light);
    }

    .account-links {
        display: grid;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .account-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 0.95rem 1rem;
        border-radius: 18px;
        color: var(--elx-white);
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        transition: var(--elx-transition);
        font-weight: 500;
        text-align: center;
    }

    .account-link:hover {
        border-color: var(--elx-cyan);
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.06);
    }

    .account-section__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .account-section__title {
        font-size: 1.25rem;
        color: var(--elx-accent);
        margin: 0;
    }

    .account-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .account-field--full {
        grid-column: 1 / -1;
    }

    .account-label {
        display: block;
        color: var(--elx-light);
        font-size: 0.82rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .account-input,
    .account-select,
    .account-textarea {
        width: 100%;
        padding: 0.95rem 1rem;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.09);
        background: rgba(255, 255, 255, 0.04);
        color: var(--elx-white);
        outline: none;
        transition: var(--elx-transition);
    }

    .account-input:focus,
    .account-select:focus,
    .account-textarea:focus {
        border-color: var(--elx-cyan);
        box-shadow: 0 0 0 3px rgba(74, 200, 246, 0.12);
    }

    .account-select option {
        background: #13252d;
        color: #fff;
    }

    .account-inline {
        display: grid;
        grid-template-columns: 140px minmax(0, 1fr);
        gap: 0.75rem;
    }

    .account-error {
        color: #ff9b9b;
        font-size: 0.85rem;
        margin-top: 0.45rem;
    }

    .account-success {
        margin-bottom: 1.5rem;
        padding: 1rem 1.2rem;
        border-radius: 18px;
        border: 1px solid rgba(74, 200, 246, 0.26);
        background: rgba(74, 200, 246, 0.1);
        color: var(--elx-cyan);
    }

    .account-order-list {
        display: grid;
        gap: 1rem;
    }

    .account-order {
        border-radius: 22px;
        padding: 1.3rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .account-order__top,
    .account-order__bottom {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 1rem;
    }

    .account-order__top {
        margin-bottom: 1rem;
        align-items: flex-start;
    }

    .account-order__meta {
        color: var(--elx-light);
        font-size: 0.82rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .account-order__items {
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.94rem;
        margin-top: 0.8rem;
        display: grid;
        gap: 0.35rem;
    }

    .account-status {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        border: 1px solid transparent;
    }

    .account-status--pending {
        color: #ffd36a;
        background: rgba(255, 193, 7, 0.12);
        border-color: rgba(255, 193, 7, 0.2);
    }

    .account-status--confirmed,
    .account-status--preparing {
        color: #8fdfff;
        background: rgba(13, 202, 240, 0.12);
        border-color: rgba(13, 202, 240, 0.2);
    }

    .account-status--ready,
    .account-status--delivered {
        color: #7ef0bf;
        background: rgba(25, 135, 84, 0.12);
        border-color: rgba(25, 135, 84, 0.2);
    }

    .account-status--cancelled {
        color: #ff9b9b;
        background: rgba(220, 53, 69, 0.12);
        border-color: rgba(220, 53, 69, 0.2);
    }

    .account-pagination {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 1rem;
        align-items: center;
        margin-top: 1.5rem;
    }

    .account-pagination__link,
    .account-pagination__text {
        padding: 0.75rem 1rem;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(255, 255, 255, 0.03);
        color: var(--elx-white);
        font-size: 0.92rem;
    }

    .account-pagination__link:hover {
        border-color: var(--elx-cyan);
    }

    .account-featured {
        display: grid;
        gap: 0.85rem;
        margin-top: 1.2rem;
    }

    .account-featured__item {
        display: grid;
        grid-template-columns: 64px minmax(0, 1fr);
        gap: 0.9rem;
        align-items: center;
        padding: 0.85rem;
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        background: rgba(255, 255, 255, 0.03);
        color: var(--elx-white);
    }

    .account-featured__thumb {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .account-featured__thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .account-danger {
        border-color: rgba(220, 53, 69, 0.28);
    }

    .account-danger summary {
        list-style: none;
        cursor: pointer;
    }

    .account-danger summary::-webkit-details-marker {
        display: none;
    }

    .account-danger__panel {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(220, 53, 69, 0.16);
    }

    @media (max-width: 1024px) {
        .account-shell {
            grid-template-columns: 1fr;
        }

        .account-sidebar {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .account-form-grid,
        .account-grid {
            grid-template-columns: 1fr;
        }

        .account-inline {
            grid-template-columns: 1fr;
        }

        .account-card {
            padding: 1.4rem;
        }

        .account-avatar {
            width: 70px;
            height: 70px;
            border-radius: 22px;
        }

        .account-avatar-panel {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
@php
    $phone = old('phone_number', $user->phone);
    $countryCode = old('country_code', '+966');
    $phoneNumber = $phone;

    if ($phone && str_starts_with($phone, '+971')) {
        $countryCode = '+971';
        $phoneNumber = substr($phone, 4);
    } elseif ($phone && str_starts_with($phone, '+966')) {
        $countryCode = '+966';
        $phoneNumber = substr($phone, 4);
    }

    $statusClasses = [
        'pending' => 'account-status--pending',
        'confirmed' => 'account-status--confirmed',
        'preparing' => 'account-status--preparing',
        'ready' => 'account-status--ready',
        'delivered' => 'account-status--delivered',
        'cancelled' => 'account-status--cancelled',
    ];
@endphp

<div class="page-content">
    <div class="elx-container">
        <div class="account-hero" data-animate>
            <div class="account-hero__copy">
                <h1 class="elx-hero__title">
                    <span class="elx-hero__title-gradient">My Account</span>
                </h1>
                <p>Manage your details, keep your member code ready for checkout, and review every order from one place.</p>
            </div>

            <div class="account-hero__actions">
                <a href="#details" class="elx-btn elx-btn--glass">Edit Details</a>
                <a href="{{ route('profile.orders.index') }}" class="elx-btn elx-btn--glass">My Orders</a>
                <a href="{{ route('menu.index') }}" class="elx-btn elx-btn--primary">Shop Now</a>
            </div>
        </div>

        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div class="account-success" data-animate>
                Your account changes were saved successfully.
            </div>
        @endif

        <div class="account-shell">
            <aside class="account-sidebar" data-animate>
                <div class="account-card">
                    <div class="account-sidebar__top">
                        <x-user-avatar :user="$user" size="84" class="account-avatar" />

                        <div>
                            <div class="account-pill">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ $user->role === 'admin' ? 'Administrator' : 'Member' }}</span>
                            </div>
                            <h2 style="margin: 0.9rem 0 0.25rem; font-size: 1.5rem;">{{ $user->name }}</h2>
                            <p class="account-muted">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="account-grid">
                        <div class="account-stat">
                            <span class="account-stat__value">{{ $accountStats['total_orders'] }}</span>
                            <span class="account-stat__label">Orders</span>
                        </div>
                        <div class="account-stat">
                            <span class="account-stat__value">{{ $accountStats['active_orders'] }}</span>
                            <span class="account-stat__label">In Progress</span>
                        </div>
                        <div class="account-stat">
                            <span class="account-stat__value">{{ $accountStats['delivered_orders'] }}</span>
                            <span class="account-stat__label">Delivered</span>
                        </div>
                        <div class="account-stat">
                            <span class="account-stat__value">﷼ {{ number_format((float) $accountStats['total_spent'], 2) }}</span>
                            <span class="account-stat__label">Spent</span>
                        </div>
                    </div>

                    <div class="account-links">
                        <a href="#security" class="account-link">
                            <i class="fas fa-lock"></i>
                            <span>Update password</span>
                        </a>
                        <a href="{{ route('profile.orders.index') }}" class="account-link">
                            <i class="fas fa-box-open"></i>
                            <span>View previous orders</span>
                        </a>
                        <a href="{{ route('profile.avatar-options') }}" class="account-link">
                            <i class="fas fa-image-portrait"></i>
                            <span>Choose avatar</span>
                        </a>
                        <a href="{{ route('orders.track') }}" class="account-link">
                            <i class="fas fa-location-arrow"></i>
                            <span>Track by phone</span>
                        </a>
                    </div>
                </div>

                <div class="account-card">
                    <div class="account-section__header" style="margin-bottom: 1rem;">
                        <h3 class="account-section__title">Account Snapshot</h3>
                    </div>

                    <div style="display: grid; gap: 0.85rem;">
                        <div>
                            <span class="account-label">Member Since</span>
                            <div>{{ $user->created_at?->format('F Y') ?? 'Recently joined' }}</div>
                        </div>
                        <div>
                            <span class="account-label">Phone</span>
                            <div>{{ $user->phone ?: 'Add your number for faster checkout' }}</div>
                        </div>
                        <div>
                            <span class="account-label">Member Code</span>
                            <div>{{ $user->user_code ?: 'Not added yet' }}</div>
                        </div>
                        <div>
                            <span class="account-label">Last Delivery Address</span>
                            <div class="account-muted">{{ $latestOrder?->address ?: 'No saved delivery orders yet.' }}</div>
                        </div>
                    </div>
                </div>

                @if($featuredItems->isNotEmpty())
                    <div class="account-card">
                        <div class="account-section__header" style="margin-bottom: 1rem;">
                            <h3 class="account-section__title">Suggested For You</h3>
                        </div>

                        <div class="account-featured">
                            @foreach($featuredItems as $item)
                                <a href="{{ route('menu.show', $item) }}" class="account-featured__item">
                                    <div class="account-featured__thumb">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                                        @else
                                            <div style="width: 100%; height: 100%; display: grid; place-items: center; color: var(--elx-cyan);">
                                                <i class="fas fa-seedling"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <div style="font-weight: 700;">{{ $item->name }}</div>
                                        <div class="account-muted">{{ $item->category?->name ?: 'Featured ritual' }}</div>
                                        <div style="margin-top: 0.35rem; color: var(--elx-cyan); font-weight: 700;">﷼ {{ number_format($item->price, 2) }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>

            <section data-animate>
                <div class="account-card" id="details">
                    <div class="account-section__header">
                        <div>
                            <h2 class="account-section__title">Profile Details</h2>
                            <p class="account-muted">Keep your contact information ready for checkout and support.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="account-form-grid">


                            <div>
                                <label for="name" class="account-label">Full Name</label>
                                <input id="name" name="name" type="text" class="account-input" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="account-label">Email Address</label>
                                <input id="email" name="email" type="email" class="account-input" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="gender" class="account-label">Gender</label>
                                <select id="gender" name="gender" class="account-select" required>
                                    <option value="" disabled @selected(is_null(old('gender', $user->gender)))>Select...</option>
                                    <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                                    <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                                </select>
                                @error('gender')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="account-field--full">
                                <label for="user_code" class="account-label">Member Code</label>
                                <input id="user_code" name="user_code" type="text" class="account-input" value="{{ old('user_code', $user->user_code) }}" placeholder="Optional code used to match older orders">
                                @error('user_code')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="account-field--full">
                                <label class="account-label">Phone Number</label>
                                <div class="account-inline">
                                    <select name="country_code" class="account-select">
                                        <option value="+966" @selected($countryCode === '+966')>+966</option>
                                        <option value="+971" @selected($countryCode === '+971')>+971</option>
                                    </select>
                                    <input name="phone_number" type="tel" class="account-input" value="{{ $phoneNumber }}" placeholder="Phone number">
                                </div>
                                @error('phone_number')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                                @error('country_code')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                            <button type="submit" class="elx-btn elx-btn--primary">Save Changes</button>
                            <a href="{{ route('cart.index') }}" class="elx-btn elx-btn--glass">Go To Checkout</a>
                        </div>
                    </form>
                </div>

                <div class="account-card" id="security">
                    <div class="account-section__header">
                        <div>
                            <h2 class="account-section__title">Security</h2>
                            <p class="account-muted">Change your password whenever you want to keep the account protected.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="account-form-grid">
                            <div class="account-field--full">
                                <label for="current_password" class="account-label">Current Password</label>
                                <input id="current_password" name="current_password" type="password" class="account-input" autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="account-label">New Password</label>
                                <input id="password" name="password" type="password" class="account-input" autocomplete="new-password">
                                @error('password', 'updatePassword')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="account-label">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="account-input" autocomplete="new-password">
                            </div>
                        </div>

                        <button type="submit" class="elx-btn elx-btn--glass" style="margin-top: 1.5rem;">Update Password</button>
                    </form>
                </div>

                @if($user->role === 'admin')
                    <div class="account-card account-danger">
                        <div class="account-section__header" style="margin-bottom: 0;">
                            <div>
                                <h2 class="account-section__title" style="color: #ffb1b1;">Danger Zone</h2>
                                <p class="account-muted">Administrator accounts are protected and cannot delete themselves from the system.</p>
                            </div>
                            <span class="elx-btn" style="background: rgba(255, 193, 7, 0.12); border-color: rgba(255, 193, 7, 0.24); color: #ffd36a;">Protected</span>
                        </div>
                    </div>
                @else
                    <details class="account-card account-danger" @if($errors->userDeletion->isNotEmpty()) open @endif>
                        <summary>
                            <div class="account-section__header" style="margin-bottom: 0;">
                                <div>
                                    <h2 class="account-section__title" style="color: #ffb1b1;">Danger Zone</h2>
                                    <p class="account-muted">Delete the account permanently only if you are completely sure.</p>
                                </div>
                                <span class="elx-btn" style="background: rgba(220, 53, 69, 0.12); border-color: rgba(220, 53, 69, 0.24); color: #ff9b9b;">Delete Account</span>
                            </div>
                        </summary>

                        <div class="account-danger__panel">
                            <form method="POST" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('DELETE')

                                <label for="delete_password" class="account-label">Confirm With Password</label>
                                <input id="delete_password" name="password" type="password" class="account-input" placeholder="Enter your password to continue" required>
                                @error('password', 'userDeletion')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror

                                <button type="submit" class="elx-btn" style="margin-top: 1rem; background: rgba(220, 53, 69, 0.16); border-color: rgba(220, 53, 69, 0.28); color: #ff9b9b;">Permanently Delete Account</button>
                            </form>
                        </div>
                    </details>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
