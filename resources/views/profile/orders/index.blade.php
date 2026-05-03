@extends('layouts.framer')

@section('title', 'My Orders - Elixira')

@section('head')
<style>
    .orders-wrap { display: grid; gap: 1.2rem; }
    .orders-card {
        border-radius: 24px;
        padding: 1.4rem;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
    }
    .orders-row { display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .orders-meta { color: var(--elx-light); font-size: 0.82rem; letter-spacing: 0.08em; text-transform: uppercase; }
    .orders-status {
        display: inline-flex; align-items: center; padding: 0.45rem 0.9rem; border-radius: 999px;
        font-size: 0.78rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
    }
    .orders-status--pending { color:#ffd36a; background: rgba(255, 193, 7, 0.12); }
    .orders-status--confirmed, .orders-status--preparing { color:#8fdfff; background: rgba(13, 202, 240, 0.12); }
    .orders-status--ready, .orders-status--delivered { color:#7ef0bf; background: rgba(25, 135, 84, 0.12); }
    .orders-status--cancelled { color:#ff9b9b; background: rgba(220, 53, 69, 0.12); }
</style>
@endsection

@section('content')
@php
    $statusClasses = [
        'pending' => 'orders-status--pending',
        'confirmed' => 'orders-status--confirmed',
        'preparing' => 'orders-status--preparing',
        'ready' => 'orders-status--ready',
        'delivered' => 'orders-status--delivered',
        'cancelled' => 'orders-status--cancelled',
    ];
@endphp
<div class="page-content">
    <div class="elx-container">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title"><span class="elx-hero__title-gradient">My Orders</span></h1>
            <p class="elx-hero__subtitle">All your linked orders are in one place.</p>
        </div>

        <div style="display:flex; gap:.75rem; margin-bottom:1.2rem;">
            <a href="{{ route('profile.edit') }}" class="elx-btn elx-btn--glass">Back To Account</a>
            <a href="{{ route('orders.track') }}" class="elx-btn elx-btn--glass">Track By Phone</a>
        </div>

        @if($orders->count())
            <div class="orders-wrap">
                @foreach($orders as $order)
                    @php
                        $statusClass = $statusClasses[$order->status] ?? 'orders-status--pending';
                        $itemsPreview = $order->orderItems->map(fn ($orderItem) => $orderItem->item?->name)->filter()->implode(' • ');
                    @endphp
                    <article class="orders-card" data-animate>
                        <div class="orders-row">
                            <div>
                                <div class="orders-meta">Order #{{ $order->id }} • {{ $order->created_at->format('M j, Y') }}</div>
                                <h3 style="margin:.4rem 0 0;">{{ $order->customer_name }}</h3>
                            </div>
                            <div style="text-align:right;">
                                <span class="orders-status {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                <div style="margin-top:.6rem; color:var(--elx-cyan); font-size:1.2rem; font-weight:700;">﷼ {{ number_format($order->total_amount, 2) }}</div>
                            </div>
                        </div>
                        <div style="margin-top:.8rem; color:rgba(255,255,255,.78);">
                            {{ $order->order_items_count }} item{{ $order->order_items_count === 1 ? '' : 's' }} •
                            {{ $itemsPreview ?: 'Items available in order details' }}
                        </div>
                        <div class="orders-row" style="margin-top:1rem;">
                            <span style="color:var(--elx-gray); display:inline-flex; align-items:center; gap:.35rem;">
                                <x-phone-flag :phone="$order->customer_phone" />
                            </span>
                            <div style="display:flex; gap:.5rem;">
                                <a href="{{ route('profile.orders.invoice', $order) }}" class="elx-btn elx-btn--glass">Invoice</a>
                                <a href="{{ route('profile.orders.show', $order) }}" class="elx-btn elx-btn--glass">View Details</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div style="margin-top:1.2rem;">
                {{ $orders->links() }}
            </div>
        @else
            <div class="orders-card">
                <h3>No orders linked to this account yet.</h3>
                <p style="color:var(--elx-gray);">Once you place an order while signed in, it will appear here automatically.</p>
                <a href="{{ route('menu.index') }}" class="elx-btn elx-btn--primary">Start Shopping</a>
            </div>
        @endif
    </div>
</div>
@endsection
