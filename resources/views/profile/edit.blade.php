@extends('layouts.framer')

@section('title', __('profile_page.page_title'))

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
                    <span class="elx-hero__title-gradient">{{ __('profile_page.hero_title') }}</span>
                </h1>
                <p>{{ __('profile_page.hero_subtitle') }}</p>
            </div>

            <div class="account-hero__actions">
                <a href="#details" class="elx-btn elx-btn--glass">{{ __('profile_page.edit_details') }}</a>
                <a href="{{ route('profile.orders.index') }}" class="elx-btn elx-btn--glass">{{ __('profile_page.my_orders') }}</a>
                <a href="{{ route('menu.index') }}" class="elx-btn elx-btn--primary">{{ __('profile_page.shop_now') }}</a>
            </div>
        </div>

        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div class="account-success" data-animate>
                {{ __('profile_page.saved_success') }}
            </div>
        @endif

        @if(session('success'))
            <div class="account-success" data-animate>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="margin-bottom: 1.5rem; padding: 1rem 1.2rem; border-radius: 18px; border: 1px solid rgba(220, 53, 69, 0.35); background: rgba(220, 53, 69, 0.1); color: #ff8a8a;" data-animate>
                {{ session('error') }}
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
                                <span>{{ $user->role === 'admin' ? __('profile_page.administrator') : ($user->role === 'vendor' ? __('profile_page.vendor') : __('profile_page.member')) }}</span>
                            </div>
                            @if($user->is_dxn_verified && $user->dxn_member_code)
                                <div style="display: inline-flex; align-items: center; gap: 0.5rem; margin-top: 0.65rem; padding: 0.35rem 0.75rem; border-radius: 999px; border: 1px solid {{ $user->resolvedDxnTagColor() }}; box-shadow: 0 0 12px {{ $user->resolvedDxnTagColor() }}55; background: {{ $user->resolvedDxnTagColor() }}15;">
                                    @if($user->dxn_badge_image)
                                        <img src="{{ $user->dxn_badge_url }}" alt="" style="width: 22px; height: 22px; object-fit: contain; border-radius: 4px;">
                                    @else
                                        <span style="width: 22px; height: 22px; display: inline-block; border: 1px dashed {{ $user->resolvedDxnTagColor() }}55; border-radius: 4px;"></span>
                                    @endif
                                    <span style="font-size: 0.78rem; font-weight: 700; color: {{ $user->resolvedDxnTagColor() }}; letter-spacing: 0.03em;">
                                        DXN.Mem: {{ $user->dxn_member_code }}
                                    </span>
                                </div>
                            @endif
                            <h2 style="margin: 0.9rem 0 0.25rem; font-size: 1.5rem;">{{ $user->name }}</h2>
                            <p class="account-muted">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="account-grid">
                        <div class="account-stat">
                            <span class="account-stat__value">{{ $accountStats['total_orders'] }}</span>
                            <span class="account-stat__label">{{ __('profile_page.orders') }}</span>
                        </div>
                        <div class="account-stat">
                            <span class="account-stat__value">{{ $accountStats['active_orders'] }}</span>
                            <span class="account-stat__label">{{ __('profile_page.in_progress') }}</span>
                        </div>
                        <div class="account-stat">
                            <span class="account-stat__value">{{ $accountStats['delivered_orders'] }}</span>
                            <span class="account-stat__label">{{ __('profile_page.delivered') }}</span>
                        </div>
                        <div class="account-stat">
                            <span class="account-stat__value">{{ number_format($user->total_points ?? 0) }}</span>
                            <span class="account-stat__label">{{ __('profile_page.points_total') }}</span>
                        </div>
                    </div>

                    @if($user->pointsTransactions->isNotEmpty())
                        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.08);">
                            <h3 style="font-size: 1rem; margin-bottom: 1rem; color: var(--elx-accent);">
                                <i class="fas fa-star me-1"></i> {{ __('profile_page.points_history') }}
                            </h3>
                            <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 280px; overflow-y: auto;">
                                @foreach($user->pointsTransactions as $transaction)
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; padding: 0.75rem 1rem; background: rgba(255,255,255,0.03); border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                                        <div>
                                            <div style="font-weight: 600; font-size: 0.9rem;">+{{ $transaction->points }} {{ __('profile_page.reward_points') }}</div>
                                            <div class="account-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">{{ $transaction->local_description }}</div>
                                            @if($transaction->order_id)
                                                <a href="{{ route('profile.orders.show', $transaction->order_id) }}" style="font-size: 0.75rem; color: var(--elx-cyan);">{{ __('profile_page.points_order', ['id' => $transaction->order_id]) }}</a>
                                            @endif
                                        </div>
                                        <span class="account-muted" style="font-size: 0.75rem; white-space: nowrap;">{{ $transaction->created_at->format('M d, Y') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="account-muted" style="margin-top: 1.25rem; font-size: 0.85rem;">{{ __('profile_page.no_points_yet') }}</p>
                    @endif

                    <div class="account-links">
                        <!-- <a href="#security" class="account-link">
                            <i class="fas fa-lock"></i>
                            <span>Update password</span>
                        </a> -->
                        <a href="#addresses" class="account-link">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ __('profile_page.manage_addresses') }}</span>
                        </a>
                        <a href="{{ route('profile.orders.index') }}" class="account-link">
                            <i class="fas fa-box-open"></i>
                            <span>{{ __('profile_page.view_orders') }}</span>
                        </a>
                        <a href="{{ route('profile.avatar-options') }}" class="account-link">
                            <i class="fas fa-image-portrait"></i>
                            <span>{{ __('profile_page.choose_avatar') }}</span>
                        </a>
                        <a href="{{ route('orders.track') }}" class="account-link">
                            <i class="fas fa-location-arrow"></i>
                            <span>{{ __('profile_page.track_by_phone') }}</span>
                        </a>
                        
                        @if(auth()->user()->role !== 'admin')
                        @if(!auth()->user()->vendorProfile || auth()->user()->vendorProfile->status === 'draft')
                        <a href="{{ route('vendor.onboarding', ['step' => auth()->user()->vendorProfile?->onboarding_step ?? 1]) }}" class="account-link" style="border-color: rgba(74, 200, 246, 0.3); background: rgba(74, 200, 246, 0.05);">
                            <i class="fas fa-store"></i>
                            <span style="color: var(--elx-cyan);">{{ auth()->user()->vendorProfile?->status === 'draft' ? __('profile_page.resume_vendor_draft') : __('profile_page.become_vendor') }}</span>
                        </a>
                        @elseif(auth()->user()->vendorProfile->status === 'pending')
                        <a href="{{ route('vendor.pending') }}" class="account-link">
                            <i class="fas fa-clock"></i>
                            <span style="color: #ffd36a;">{{ __('profile_page.vendor_pending') }}</span>
                        </a>
                        @elseif(auth()->user()->vendorProfile->status === 'rejected_with_notes')
                        <a href="{{ route('vendor.rejected') }}" class="account-link" style="border-color: rgba(255, 107, 107, 0.3); background: rgba(255, 107, 107, 0.05);">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span style="color: #ff6b6b;">{{ __('profile_page.vendor_resubmit') }}</span>
                        </a>
                        @elseif(auth()->user()->vendorProfile->status === 'approved')
                        <a href="{{ route('vendor.dashboard') }}" class="account-link">
                            <i class="fas fa-store"></i>
                            <span style="color: #7ef0bf;">{{ __('profile_page.vendor_dashboard') }}</span>
                        </a>
                        @endif
                        @endif
                    </div>
                </div>

                <div class="account-card">
                    <div class="account-section__header" style="margin-bottom: 1rem;">
                        <h3 class="account-section__title">{{ __('profile_page.account_snapshot') }}</h3>
                    </div>

                    <div style="display: grid; gap: 0.85rem;">
                        <div>
                            <span class="account-label">{{ __('profile_page.member_since') }}</span>
                            <div>{{ $user->created_at?->format('F Y') ?? __('profile_page.recently_joined') }}</div>
                        </div>
                        <div>
                            <span class="account-label">{{ __('profile_page.phone') }}</span>
                            <div>{{ $user->phone ?: __('profile_page.phone_missing') }}</div>
                        </div>
                        <div>
                            <span class="account-label">{{ __('profile_page.member_code') }}</span>
                            <div>{{ $user->user_code ?: __('profile_page.member_code_missing') }}</div>
                        </div>
                        <div>
                            <span class="account-label">{{ __('profile_page.last_delivery_address') }}</span>
                            <div class="account-muted">{{ $latestOrder?->address ?: __('profile_page.no_delivery_orders') }}</div>
                        </div>
                    </div>
                </div>

                @if($featuredItems->isNotEmpty())
                    <div class="account-card">
                        <div class="account-section__header" style="margin-bottom: 1rem;">
                            <h3 class="account-section__title">{{ __('profile_page.suggested_for_you') }}</h3>
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
                                        <div style="font-weight: 700;">{{ $item->local_name }}</div>
                                        <div class="account-muted">{{ $item->category?->local_name ?: __('profile_page.featured_ritual') }}</div>
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
                            <h2 class="account-section__title">{{ __('profile_page.profile_details') }}</h2>
                            <p class="account-muted">{{ __('profile_page.profile_details_hint') }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="account-form-grid">


                            <div>
                                <label for="name" class="account-label">{{ __('profile_page.full_name') }}</label>
                                <input id="name" name="name" type="text" class="account-input" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="account-label">{{ __('profile_page.email_address') }}</label>
                                <input id="email" name="email" type="email" class="account-input" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="gender" class="account-label">{{ __('profile_page.gender') }}</label>
                                <select id="gender" name="gender" class="account-select" required>
                                    <option value="" disabled @selected(is_null(old('gender', $user->gender)))>{{ __('profile_page.select') }}</option>
                                    <option value="male" @selected(old('gender', $user->gender) === 'male')>{{ __('profile_page.gender_male') }}</option>
                                    <option value="female" @selected(old('gender', $user->gender) === 'female')>{{ __('profile_page.gender_female') }}</option>
                                </select>
                                @error('gender')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="account-field--full">
                                <label for="user_code" class="account-label">{{ __('profile_page.member_code') }}</label>
                                <input id="user_code" name="user_code" type="text" class="account-input" value="{{ old('user_code', $user->user_code) }}" placeholder="{{ __('profile_page.member_code_placeholder') }}">
                                @error('user_code')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="account-field--full">
                                <label class="account-label">{{ __('profile_page.phone_number') }}</label>
                                <div class="account-inline">
                                    <x-country-code-picker name="country_code" :value="$countryCode" variant="account" />
                                    <input name="phone_number" type="tel" class="account-input" value="{{ $phoneNumber }}" placeholder="{{ __('profile_page.phone_placeholder') }}">
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
                            <button type="submit" class="elx-btn elx-btn--primary">{{ __('profile_page.save_changes') }}</button>
                            <a href="{{ route('cart.index') }}" class="elx-btn elx-btn--glass">{{ __('profile_page.go_to_checkout') }}</a>
                        </div>
                    </form>
                </div>

                <div class="account-card" id="addresses">
                    <div class="account-section__header">
                        <div>
                            <h2 class="account-section__title">{{ __('profile_page.saved_addresses') }}</h2>
                            <p class="account-muted">{{ __('profile_page.saved_addresses_hint') }}</p>
                        </div>
                    </div>

                    @php $userAddresses = $user->addresses; @endphp

                    @if($userAddresses->count() > 0)
                        <div style="display: grid; gap: 1rem; margin-bottom: 2rem;">
                            @foreach($userAddresses as $address)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-radius: 18px; background: rgba(255, 255, 255, 0.03); border: 1px solid {{ $address->is_main ? 'var(--elx-cyan)' : 'rgba(255, 255, 255, 0.06)' }};">
                                    <div style="flex-grow: 1;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="color: var(--elx-white); font-weight: 500;">{{ $address->address }}</span>
                                            @if($address->is_main)
                                                <span style="font-size: 0.7rem; background: rgba(74, 200, 246, 0.2); color: var(--elx-cyan); padding: 0.2rem 0.6rem; border-radius: 10px;">{{ __('profile_page.main_badge') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 0.5rem;">
                                        @if(!$address->is_main)
                                            <form method="POST" action="{{ route('profile.addresses.main', $address) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="elx-btn" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; background: rgba(74, 200, 246, 0.1); border-color: rgba(74, 200, 246, 0.3); color: var(--elx-cyan);">{{ __('profile_page.set_main') }}</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('profile.addresses.destroy', $address) }}" onsubmit="return confirm(@json(__('profile_page.delete_address_confirm')))">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="elx-btn" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; background: rgba(255, 77, 77, 0.1); border-color: rgba(255, 77, 77, 0.3); color: #ff4d4d;">{{ __('profile_page.delete') }}</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="account-muted" style="margin-bottom: 2rem;">{{ __('profile_page.no_addresses') }}</p>
                    @endif

                    <form method="POST" action="{{ route('profile.addresses.store') }}">
                        @csrf
                        <div class="account-field--full">
                            <label for="address_input" class="account-label">{{ __('profile_page.add_new_address') }}</label>
                            <div style="display: flex; gap: 0.75rem;">
                                <input id="address_input" name="address" type="text" class="account-input" placeholder="{{ __('profile_page.address_placeholder') }}" required minlength="10">
                                <button type="submit" class="elx-btn elx-btn--primary" style="white-space: nowrap;">{{ __('profile_page.add_address') }}</button>
                            </div>
                            @error('address')
                                <div class="account-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>

                <div class="account-card" id="security">
                    <div class="account-section__header">
                        <div>
                            <h2 class="account-section__title">{{ __('profile_page.update_password') }}</h2>
                            <p class="account-muted">{{ __('profile_page.update_password_hint') }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="account-form-grid">
                            <div class="account-field--full">
                                <label for="current_password" class="account-label">{{ __('profile_page.current_password') }}</label>
                                <input id="current_password" name="current_password" type="password" class="account-input" autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="account-label">{{ __('profile_page.new_password') }}</label>
                                <input id="password" name="password" type="password" class="account-input" autocomplete="new-password">
                                @error('password', 'updatePassword')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="account-label">{{ __('profile_page.confirm_password') }}</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="account-input" autocomplete="new-password">
                            </div>
                        </div>

                        <button type="submit" class="elx-btn elx-btn--glass" style="margin-top: 1.5rem;">{{ __('profile_page.save') }}</button>
                    </form>
                </div>

                @if($user->role !== 'admin')
                    <details class="account-card account-danger" @if($errors->userDeletion->isNotEmpty()) open @endif>
                        <summary>
                            <div class="account-section__header" style="margin-bottom: 0;">
                                <div>
                                    <h2 class="account-section__title" style="color: #ffb1b1;">{{ __('profile_page.danger_zone') }}</h2>
                                    <p class="account-muted">{{ __('profile_page.danger_zone_hint') }}</p>
                                </div>
                                <span class="elx-btn" style="background: rgba(220, 53, 69, 0.12); border-color: rgba(220, 53, 69, 0.24); color: #ff9b9b;">{{ __('profile_page.delete_account') }}</span>
                            </div>
                        </summary>

                        <div class="account-danger__panel">
                            <form method="POST" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('DELETE')

                                <label for="delete_password" class="account-label">{{ __('profile_page.confirm_with_password') }}</label>
                                <input id="delete_password" name="password" type="password" class="account-input" placeholder="{{ __('profile_page.delete_password_placeholder') }}" required>
                                @error('password', 'userDeletion')
                                    <div class="account-error">{{ $message }}</div>
                                @enderror

                                <button type="submit" class="elx-btn" style="margin-top: 1rem; background: rgba(220, 53, 69, 0.16); border-color: rgba(220, 53, 69, 0.28); color: #ff9b9b;">{{ __('profile_page.permanently_delete') }}</button>
                            </form>
                        </div>
                    </details>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
