@extends('layouts.framer')

@section('title', 'Order #' . $order->id . ' - My Account')

@section('head')
<style>
    .account-order-page {
        display: grid;
        gap: 1.5rem;
    }

    .account-order-card {
        background: linear-gradient(180deg, rgba(19, 37, 45, 0.96), rgba(10, 26, 34, 0.96));
        border: 1px solid var(--elx-border);
        border-radius: 28px;
        padding: 2rem;
        box-shadow: 0 24px 70px rgba(0, 0, 0, 0.25);
    }

    .account-order-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .account-order-kpis {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .account-order-kpi {
        padding: 1rem;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .account-order-kpi span {
        display: block;
    }

    .account-order-kpi__label {
        color: var(--elx-light);
        font-size: 0.8rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.35rem;
    }

    .account-order-kpi__value {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--elx-white);
    }

    .account-status {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 0.95rem;
        border-radius: 999px;
        font-size: 0.8rem;
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

    .account-order-grid {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 1.5rem;
    }

    .account-order-items {
        display: grid;
        gap: 1rem;
    }

    .account-order-item {
        display: grid;
        grid-template-columns: 84px minmax(0, 1fr) auto;
        gap: 1rem;
        align-items: center;
        padding: 1rem;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .account-order-item__thumb {
        width: 84px;
        height: 84px;
        border-radius: 18px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .account-order-item__thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .account-order-summary {
        display: grid;
        gap: 1rem;
    }

    .account-order-block {
        padding: 1.2rem;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .account-order-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.6rem 0;
        color: rgba(255, 255, 255, 0.78);
    }

    .account-order-row + .account-order-row {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }

    .account-timeline {
        display: grid;
        gap: 0.85rem;
        margin-top: 1.2rem;
    }

    .account-timeline__item {
        display: grid;
        grid-template-columns: 18px minmax(0, 1fr);
        gap: 1rem;
        opacity: 0.45;
    }

    .account-timeline__item.is-active {
        opacity: 1;
    }

    .account-timeline__dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        margin-top: 0.2rem;
        background: rgba(255, 255, 255, 0.16);
        border: 3px solid rgba(255, 255, 255, 0.06);
    }

    .account-timeline__item.is-active .account-timeline__dot {
        background: var(--elx-cyan);
        box-shadow: 0 0 18px rgba(74, 200, 246, 0.35);
    }

    @media (max-width: 900px) {
        .account-order-grid,
        .account-order-kpis {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .account-order-card {
            padding: 1.4rem;
        }

        .account-order-item {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .account-order-item__thumb {
            margin: 0 auto;
        }
    }
</style>
@endsection

@section('content')
@php
    $statusClasses = [
        'pending' => 'account-status--pending',
        'confirmed' => 'account-status--confirmed',
        'preparing' => 'account-status--preparing',
        'ready' => 'account-status--ready',
        'delivered' => 'account-status--delivered',
        'cancelled' => 'account-status--cancelled',
    ];
    $timeline = [
        ['status' => 'pending', 'title' => 'Order received', 'description' => 'Your order is safely in the system.'],
        ['status' => 'confirmed', 'title' => 'Confirmed', 'description' => 'The team reviewed and confirmed the order.'],
        ['status' => 'preparing', 'title' => 'Preparing', 'description' => 'Your products are being prepared right now.'],
        ['status' => 'ready', 'title' => 'Ready', 'description' => 'The package is packed and ready for the next step.'],
        ['status' => 'delivered', 'title' => 'Delivered', 'description' => 'The order reached its destination successfully.'],
    ];
    $activeReached = true;
@endphp

<div class="page-content">
    <div class="elx-container">
        <div class="account-order-page">
            <div class="elx-section__header" data-animate>
                <h1 class="elx-hero__title">
                    <span class="elx-hero__title-gradient">Order #{{ $order->id }}</span>
                </h1>
                <p class="elx-hero__subtitle">{{ $order->created_at->format('F j, Y \\a\\t g:i A') }}</p>
            </div>

            <div class="account-order-card" data-animate>
                <div class="account-order-header">
                    <div>
                        <span class="account-status {{ $statusClasses[$order->status] ?? 'account-status--pending' }}">{{ ucfirst($order->status) }}</span>
                        <h2 style="margin-top: 0.9rem; font-size: 1.8rem;">{{ $order->customer_name }}</h2>
                        <p style="color: rgba(255, 255, 255, 0.72);">Placed with {{ $order->customer_phone }}</p>
                    </div>

                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <a href="{{ route('profile.orders.index') }}" class="elx-btn elx-btn--glass">Back To Orders</a>
                        <a href="{{ route('profile.orders.invoice', $order) }}" class="elx-btn elx-btn--glass">Invoice</a>
                        <a href="{{ route('menu.index') }}" class="elx-btn elx-btn--primary">Shop Again</a>
                    </div>
                </div>

                <div class="account-order-kpis">
                    <div class="account-order-kpi">
                        <span class="account-order-kpi__label">Order Total</span>
                        <span class="account-order-kpi__value">﷼ {{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="account-order-kpi">
                        <span class="account-order-kpi__label">Items</span>
                        <span class="account-order-kpi__value">{{ $order->orderItems->sum('quantity') }}</span>
                    </div>
                    <div class="account-order-kpi">
                        <span class="account-order-kpi__label">Member Code</span>
                        <span class="account-order-kpi__value">{{ $order->user_code ?: 'Not used' }}</span>
                    </div>
                </div>
            </div>

            <div class="account-order-grid">
                <div class="account-order-card" data-animate>
                    <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 1.2rem;">
                        <h3 style="font-size: 1.3rem; color: var(--elx-accent); margin: 0;">Ordered Items</h3>
                        <span style="color: var(--elx-light);">{{ $order->orderItems->count() }} line items</span>
                    </div>

                    <div class="account-order-items">
                        @foreach($order->orderItems as $orderItem)
                            <div class="account-order-item">
                                <div class="account-order-item__thumb">
                                    @if($orderItem->item?->image)
                                        <img src="{{ asset('storage/' . $orderItem->item->image) }}" alt="{{ $orderItem->item->name }}">
                                    @else
                                        <div style="width: 100%; height: 100%; display: grid; place-items: center; color: var(--elx-cyan);">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <h4 style="margin-bottom: 0.35rem;">{{ $orderItem->item?->name ?: 'Product removed' }}</h4>
                                    <div style="color: var(--elx-gray);">Qty {{ $orderItem->quantity }} • ﷼ {{ number_format($orderItem->price, 2) }} each</div>
                                </div>

                                <div style="text-align: right; font-size: 1.1rem; font-weight: 700; color: var(--elx-cyan);">
                                    ﷼ {{ number_format($orderItem->price * $orderItem->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="account-order-summary">
                    <div class="account-order-card" data-animate>
                        <h3 style="font-size: 1.2rem; color: var(--elx-accent); margin-bottom: 1rem;">Delivery Summary</h3>

                        <div class="account-order-block">
                            <div style="display: grid; gap: 0.95rem;">
                                <div>
                                    <div style="color: var(--elx-light); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em;">Shipping Address</div>
                                    <div style="margin-top: 0.35rem;">{{ $order->address }}</div>
                                </div>
                                <div>
                                    <div style="color: var(--elx-light); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em;">Notes</div>
                                    <div style="margin-top: 0.35rem; color: rgba(255, 255, 255, 0.78);">{{ $order->notes ?: 'No extra notes added.' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="account-order-block" style="margin-top: 1rem;">
                            <div class="account-order-row">
                                <span>Order number</span>
                                <strong>#{{ $order->id }}</strong>
                            </div>
                            <div class="account-order-row">
                                <span>Placed on</span>
                                <strong>{{ $order->created_at->format('M j, Y') }}</strong>
                            </div>
                            <div class="account-order-row">
                                <span>Total amount</span>
                                <strong>﷼ {{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="account-order-card" data-animate>
                        <h3 style="font-size: 1.2rem; color: var(--elx-accent); margin-bottom: 0.4rem;">Status Progress</h3>
                        <p style="color: rgba(255, 255, 255, 0.68);">A quick view of where the order currently stands.</p>

                        <div class="account-timeline">
                            @foreach($timeline as $step)
                                <div class="account-timeline__item {{ $activeReached ? 'is-active' : '' }}">
                                    <div class="account-timeline__dot"></div>
                                    <div>
                                        <h4 style="margin-bottom: 0.25rem;">{{ $step['title'] }}</h4>
                                        <p style="color: rgba(255, 255, 255, 0.65);">{{ $step['description'] }}</p>
                                    </div>
                                </div>
                                @if($order->status === $step['status'])
                                    @php($activeReached = false)
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
