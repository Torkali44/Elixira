@extends('layouts.framer')

@section('title', 'Track your order — Elixira')

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
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        {{-- Section Header --}}
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">Order Tracking</span>
            </h1>
            <p class="elx-hero__subtitle">Enter the phone number you used at checkout to see your status.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(!isset($orders))
                    <div class="track-card" data-animate>
                        <div style="text-align: center; margin-bottom: 2.5rem;">
                            <i class="fas fa-mobile-screen-button fa-3x" style="color: var(--elx-cyan); margin-bottom: 1.5rem;"></i>
                            <h2 style="font-size: 1.5rem;">Look up orders</h2>
                        </div>
                        
                        <form action="{{ route('orders.track') }}" method="GET">
                            <input type="tel" class="form-input" name="phone" placeholder="+1 (555) 123-4567" value="{{ request('phone') }}" required>
                            <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1.2rem;">
                                <i class="fas fa-search"></i> View My Orders
                            </button>
                        </form>

                        @if(session('error'))
                            <div style="background: rgba(220, 53, 69, 0.1); color: #ff8a8a; padding: 1rem; border-radius: 10px; margin-top: 1.5rem; text-align: center; border: 1px solid rgba(220, 53, 69, 0.2);">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Orders List --}}
                    <div data-animate>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                            <h2 style="font-size: 1.3rem; color: var(--elx-accent);">✦ Found {{ $orders->count() }} orders</h2>
                            <a href="{{ route('orders.track') }}" class="elx-btn elx-btn--glass" style="padding: 0.5rem 1.2rem;">New Search</a>
                        </div>

                        @php
                            $statusMap = [
                                'pending' => 'pending', 'confirmed' => 'confirmed',
                                'preparing' => 'preparing', 'ready' => 'ready',
                                'delivered' => 'delivered', 'cancelled' => 'cancelled'
                            ];
                        @endphp

                        @foreach($orders as $order)
                        <a href="{{ route('orders.track', ['order_id' => $order->id, 'phone' => request('phone')]) }}" class="order-box">
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--elx-cyan); letter-spacing: 1px; font-weight: 700;">ORDER #{{ $order->id }}</span>
                                    <span style="display: block; font-size: 1.2rem; font-weight: 600; margin: 0.3rem 0;">{{ $order->created_at->format('M j, Y') }}</span>
                                    <span style="color: var(--elx-gray); font-size: 0.9rem;">{{ $order->orderItems->count() }} items</span>
                                </div>
                                <div style="text-align: right;">
                                    <span class="status-badge bg-{{ $statusMap[$order->status] ?? 'pending' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <div style="margin-top: 0.5rem; font-weight: 700; color: var(--elx-white);">SAR {{ number_format($order->total_amount, 2) }}</div>
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
