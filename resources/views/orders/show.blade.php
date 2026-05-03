@extends('layouts.framer')

@section('title', 'Order #' . $order->id . ' - Elixira')

@section('head')
<style>
    .order-detail-card {
        background: var(--elx-glass);
        backdrop-filter: blur(42px);
        border: 1px solid var(--elx-border);
        border-radius: var(--elx-radius-sm);
        padding: 2.5rem;
        margin-bottom: 2rem;
    }
    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .timeline {
        position: relative;
        padding-left: 2rem;
        margin-top: 2rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: rgba(255, 255, 255, 0.1);
    }
    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
        padding-left: 1.5rem;
        opacity: 0.3;
        transition: 0.3s;
    }
    .timeline-item.active { opacity: 1; }
    .timeline-dot {
        position: absolute;
        left: -20px;
        top: 5px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--elx-border);
        border: 3px solid var(--elx-dark);
        z-index: 10;
        transition: 0.3s;
    }
    .timeline-item.active .timeline-dot {
        background: var(--elx-cyan);
        box-shadow: 0 0 10px var(--elx-cyan);
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--elx-border);
    }
    .info-group label {
        display: block;
        color: var(--elx-gray);
        font-size: 0.8rem;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    .info-group p {
        font-weight: 600;
        color: var(--elx-white);
    }
    .item-table {
        width: 100%;
        margin-top: 2rem;
    }
    .item-table th {
        text-align: left;
        color: var(--elx-gray);
        font-size: 0.8rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--elx-border);
    }
    .item-table td {
        padding: 1rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        {{-- Section Header --}}
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">Order #{{ $order->id }}</span>
            </h1>
            <p class="elx-hero__subtitle">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="order-detail-card" data-animate>
                    {{-- Status Header --}}
                    <div class="status-header">
                        <div>
                            <span style="color: var(--elx-gray);">Current Status</span>
                            <h2 style="color: var(--elx-accent); margin-top: 0.5rem; font-size: 1.8rem;">✧ {{ ucfirst($order->status) }}</h2>
                        </div>
                        <div style="text-align: right;">
                            <span style="color: var(--elx-gray);">Order Total</span>
                            <h2 style="color: var(--elx-cyan); margin-top: 0.5rem; font-size: 1.8rem;">﷼ {{ number_format($order->total_amount, 2) }}</h2>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="timeline">
                        @php
                            $steps = [
                                ['status' => 'pending', 'label' => 'Order Received', 'desc' => 'We have received your order.'],
                                ['status' => 'confirmed', 'label' => 'Confirmed', 'desc' => 'Your order has been confirmed.'],
                                ['status' => 'preparing', 'label' => 'Preparing', 'desc' => 'We are preparing your items.'],
                                ['status' => 'ready', 'label' => 'Ready to Ship', 'desc' => 'Your package is ready.'],
                                ['status' => 'delivered', 'label' => 'Delivered', 'desc' => 'Enjoy your Elixira ritual.'],
                            ];
                            $reached = true;
                        @endphp
                        @foreach($steps as $step)
                            <div class="timeline-item {{ $reached ? 'active' : '' }}">
                                <div class="timeline-dot"></div>
                                <h4 style="margin-bottom: 0.3rem;">{{ $step['label'] }}</h4>
                                <p style="color: var(--elx-gray); font-size: 0.9rem;">{{ $step['desc'] }}</p>
                            </div>
                            @if($order->status == $step['status']) @php $reached = false; @endphp @endif
                        @endforeach
                    </div>

                    {{-- Customer Info --}}
                    <div class="info-grid">
                        <div class="info-group">
                            <label>Customer Name</label>
                            <p>{{ $order->customer_name }}</p>
                        </div>
                        <div class="info-group">
                            <label>Phone Number</label>
                            <p style="display: flex; align-items: center; gap: 0.4rem;"><x-phone-flag :phone="$order->customer_phone" /></p>
                        </div>
                        <div class="info-group" style="grid-column: span 2;">
                            <label>Shipping Address</label>
                            <p>{{ $order->address }}</p>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div style="margin-top: 4rem;">
                        <h3 style="font-size: 1.2rem; color: var(--elx-accent); margin-bottom: 1rem;">✧ Order Items</h3>
                        <table class="item-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th style="text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;">{{ $item->item->name }}</div>
                                        <div style="font-size: 0.8rem; color: var(--elx-gray);">﷼ {{ number_format($item->price, 2) }} each</div>
                                    </td>
                                    <td>x{{ $item->quantity }}</td>
                                    <td style="text-align: right; font-weight: 700; color: var(--elx-cyan);">﷼ {{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
                    <a href="{{ route('orders.track', ['phone' => $order->customer_phone]) }}" class="elx-btn elx-btn--glass">
                        <i class="fas fa-arrow-left"></i> All My Orders
                    </a>
                    <a href="{{ route('home') }}" class="elx-btn elx-btn--primary">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
