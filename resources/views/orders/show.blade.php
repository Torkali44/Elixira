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

@inject('pricing', 'App\Support\ItemPricingService')
@php
    $currencySymbol = $pricing->currencySymbol($order->deliveryCountryCode());
@endphp

@section('content')
<div class="page-content">
    <div class="elx-container">
        {{-- Section Header --}}
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">{{ __('track.order_heading', ['id' => $order->reference]) }}</span>
            </h1>
            <p class="elx-hero__subtitle">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="order-detail-card" data-animate>
                    {{-- Status Header --}}
                    <div class="status-header">
                        <div>
                            <span style="color: var(--elx-gray);">{{ __('track.current_status') }}</span>
                            <h2 style="color: var(--elx-accent); margin-top: 0.5rem; font-size: 1.8rem;">✧ {{ __('notifications.status.' . $order->status) }}</h2>
                        </div>
                        <div style="text-align: right;">
                            <span style="color: var(--elx-gray);">{{ __('track.order_total') }}</span>
                            <h2 style="color: var(--elx-cyan); margin-top: 0.5rem; font-size: 1.8rem;">{{ $currencySymbol }} {{ number_format($order->total_amount, 2) }}</h2>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="timeline">
                        @php
                            $steps = [
                                ['status' => 'pending', 'label' => __('orders_page.timeline_pending_title'), 'desc' => __('orders_page.timeline_pending_desc')],
                                ['status' => 'confirmed', 'label' => __('orders_page.timeline_confirmed_title'), 'desc' => __('orders_page.timeline_confirmed_desc')],
                                ['status' => 'preparing', 'label' => __('orders_page.timeline_preparing_title'), 'desc' => __('orders_page.timeline_preparing_desc')],
                                ['status' => 'ready', 'label' => __('orders_page.timeline_ready_title'), 'desc' => __('orders_page.timeline_ready_desc')],
                                ['status' => 'delivered', 'label' => __('orders_page.timeline_delivered_title'), 'desc' => __('orders_page.timeline_delivered_desc')],
                            ];
                            if ($order->status === 'cancelled') {
                                $steps[] = ['status' => 'cancelled', 'label' => __('orders_page.timeline_cancelled_title'), 'desc' => __('orders_page.timeline_cancelled_desc')];
                            }
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
                            <label>{{ __('track.customer_name') }}</label>
                            <p>{{ $order->customer_name }}</p>
                        </div>
                        <div class="info-group">
                            <label>{{ __('track.phone_number') }}</label>
                            <p style="display: flex; align-items: center; gap: 0.4rem;"><x-phone-flag :phone="$order->customer_phone" /></p>
                        </div>
                        <div class="info-group" style="grid-column: span 2;">
                            <label>{{ __('track.shipping_address') }}</label>
                            <p>{{ $order->address }}</p>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div style="margin-top: 4rem;">
                        <h3 style="font-size: 1.2rem; color: var(--elx-accent); margin-bottom: 1rem;">✧ {{ __('track.order_items') }}</h3>
                        <table class="item-table">
                            <thead>
                                <tr>
                                    <th>{{ __('track.product') }}</th>
                                    <th>{{ __('track.quantity') }}</th>
                                    <th style="text-align: right;">{{ __('track.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $orderItem)
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;">{{ $orderItem->product_name ?: ($orderItem->item?->local_name ?? __('orders_page.product_removed')) }}</div>
                                        <div style="font-size: 0.8rem; color: var(--elx-gray);">{{ $currencySymbol }} {{ number_format($orderItem->price, 2) }} {{ __('track.each') }}</div>
                                    </td>
                                    <td>x{{ $orderItem->quantity }}</td>
                                    <td style="text-align: right; font-weight: 700; color: var(--elx-cyan);">{{ $currencySymbol }} {{ number_format($orderItem->price * $orderItem->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
                    @if($order->status === 'pending')
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" data-confirm="{{ __('orders_page.cancel_confirm') }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="phone" value="{{ $order->customer_phone }}">
                            <button type="submit" class="elx-btn elx-btn--danger" style="background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.4); color: #ff8a8a;">
                                <i class="fas fa-times me-1"></i> {{ __('orders_page.cancel_order') }}
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('orders.track', ['phone' => $order->customer_phone]) }}" class="elx-btn elx-btn--glass">
                        <i class="fas fa-arrow-left"></i> {{ __('track.all_my_orders') }}
                    </a>
                    <a href="{{ route('orders.invoice', ['order' => $order->id, 'phone' => $order->customer_phone]) }}" target="_blank" class="elx-btn elx-btn--glass">
                        <i class="fas fa-print"></i> {{ __('track.print_invoice') }}
                    </a>
                    <a href="{{ route('home') }}" class="elx-btn elx-btn--primary">{{ __('track.back_to_home') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
