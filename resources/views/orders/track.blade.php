@extends('layouts.framer')

@section('title', __('track.page_title'))

@section('head')
<style>
    .track-card {
        background: var(--elx-glass);
        backdrop-filter: blur(42px);
        border: 1px solid var(--elx-border);
        border-radius: var(--elx-radius-sm);
        padding: 3rem;
        transition: var(--elx-transition);
    }
    .order-box {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--elx-border);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        cursor: pointer;
        transition: var(--elx-transition);
        display: block;
        text-decoration: none;
        color: inherit;
    }
    .order-box:hover {
        background: rgba(255, 255, 255, 0.07);
        border-color: var(--elx-cyan);
        transform: translateY(-3px);
    }
    .form-input {
        width: 100%;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--elx-border);
        border-radius: 100px;
        color: var(--elx-white);
        margin-bottom: 2rem;
        outline: none;
        transition: var(--elx-transition);
        text-align: center;
        font-size: 1.2rem;
    }
    .form-input:focus { border-color: var(--elx-cyan); }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 100px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .bg-pending { background: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3); }
    .bg-confirmed { background: rgba(13, 202, 240, 0.15); color: #0dcaf0; border: 1px solid rgba(13, 202, 240, 0.3); }
    .bg-preparing { background: rgba(102, 16, 242, 0.15); color: #6610f2; border: 1px solid rgba(102, 16, 242, 0.3); }
    .bg-ready { background: rgba(25, 135, 84, 0.15); color: #198754; border: 1px solid rgba(25, 135, 84, 0.3); }
    .bg-delivered { background: rgba(25, 135, 84, 0.15); color: #198754; border: 1px solid rgba(25, 135, 84, 0.3); }
    .bg-cancelled { background: rgba(220, 53, 69, 0.15); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); }

    /* Light theme fixes */
    body.light-mode .elx-hero__subtitle {
        color: #3d4f5c !important;
    }
    body.light-mode .track-card {
        background: rgba(255, 255, 255, 0.92);
        border-color: rgba(0, 0, 0, 0.08);
    }
    body.light-mode .track-card h2 {
        color: #13252d;
    }
    body.light-mode .form-input {
        background: #f1f5f8;
        color: #13252d;
        border-color: rgba(0, 0, 0, 0.12);
    }
    body.light-mode .form-input::placeholder {
        color: #6c7a86;
    }
    body.light-mode .order-box {
        background: #fff;
        border-color: rgba(0, 0, 0, 0.08);
        color: #13252d;
    }
    body.light-mode .order-box:hover {
        background: #f8fafb;
    }
    body.light-mode .order-box span {
        color: inherit;
    }
    body.light-mode .order-box div[style*="elx-white"] {
        color: #13252d !important;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">{{ __('track.hero_title') }}</span>
            </h1>
            <p class="elx-hero__subtitle">{{ __('track.hero_subtitle') }}</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(!isset($orders))
                    <div class="track-card" data-animate>
                        <div style="text-align: center; margin-bottom: 2.5rem;">
                            <i class="fas fa-mobile-screen-button fa-3x" style="color: var(--elx-cyan); margin-bottom: 1.5rem;"></i>
                            <h2 style="font-size: 1.5rem;">{{ __('track.lookup_title') }}</h2>
                        </div>

                        <form action="{{ route('orders.track') }}" method="GET">
                            <input type="tel" class="form-input" name="phone" placeholder="{{ __('track.phone_placeholder') }}" value="{{ request('phone') }}" required>
                            <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1.2rem;">
                                <i class="fas fa-search"></i> {{ __('track.view_orders') }}
                            </button>
                        </form>

                        @if(session('error'))
                            <div style="background: rgba(220, 53, 69, 0.1); color: #ff8a8a; padding: 1rem; border-radius: 10px; margin-top: 1.5rem; text-align: center; border: 1px solid rgba(220, 53, 69, 0.2);">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                @else
                    <div data-animate>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                            <h2 style="font-size: 1.3rem; color: var(--elx-accent);">{{ __('track.found_orders', ['count' => $orders->count()]) }}</h2>
                            <a href="{{ route('orders.track') }}" class="elx-btn elx-btn--glass" style="padding: 0.5rem 1.2rem;">{{ __('track.new_search') }}</a>
                        </div>

                        @php
                            $statusMap = [
                                'pending' => 'pending', 'confirmed' => 'confirmed',
                                'preparing' => 'preparing', 'ready' => 'ready',
                                'delivered' => 'delivered', 'cancelled' => 'cancelled',
                            ];
                        @endphp

                        @foreach($orders as $order)
                        <a href="{{ route('orders.track', ['order_id' => $order->id, 'phone' => request('phone')]) }}" class="order-box">
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--elx-cyan); letter-spacing: 1px; font-weight: 700;">{{ __('track.order_label', ['id' => $order->id]) }}</span>
                                    <span style="display: block; font-size: 1.2rem; font-weight: 600; margin: 0.3rem 0;">{{ $order->created_at->format('M j, Y') }}</span>
                                    <span style="color: var(--elx-gray); font-size: 0.9rem;">{{ __('track.items_count', ['count' => $order->orderItems->count()]) }}</span>
                                </div>
                                <div style="text-align: right;">
                                    <span class="status-badge bg-{{ $statusMap[$order->status] ?? 'pending' }}">
                                        {{ __('notifications.status.'.$order->status) }}
                                    </span>
                                    <div style="margin-top: 0.5rem; font-weight: 700; color: var(--elx-white);">﷼ {{ number_format($order->total_amount, 2) }}</div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
