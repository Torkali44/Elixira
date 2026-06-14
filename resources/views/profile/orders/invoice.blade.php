@extends('layouts.framer')

@section('title', __('orders_page.invoice_title', ['id' => $order->id]))

@section('content')
<div class="page-content">
    <div class="elx-container" style="max-width: 900px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; gap:1rem;">
            <h1 class="elx-hero__title" style="font-size:2rem; margin:0;"><span class="elx-hero__title-gradient">{{ __('orders_page.invoice_title', ['id' => $order->id]) }}</span></h1>
            <div style="display:flex; gap:.6rem;">
                <button onclick="window.print()" class="elx-btn elx-btn--glass">{{ __('orders_page.print_invoice') }}</button>
                <a href="{{ route('profile.orders.show', $order) }}" class="elx-btn elx-btn--primary">{{ __('orders_page.back_to_order') }}</a>
            </div>
        </div>

        <div style="border:1px solid rgba(255,255,255,.1); border-radius:24px; padding:1.3rem; background:rgba(255,255,255,.03);">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div>
                    <div style="color:var(--elx-gray);">{{ __('orders_page.customer') }}</div>
                    <strong>{{ $order->customer_name }}</strong>
                    <div style="display:flex; align-items:center; gap:.35rem;"><x-phone-flag :phone="$order->customer_phone" /></div>
                    @if($order->customer_email)<div>{{ $order->customer_email }}</div>@endif
                </div>
                <div style="text-align:right;">
                    <div style="color:var(--elx-gray);">{{ __('orders_page.date') }}</div>
                    <strong>{{ $order->created_at->format('Y-m-d H:i') }}</strong>
                    <div style="margin-top:.2rem; color:var(--elx-gray);">{{ __('orders_page.status') }}: {{ __('notifications.status.' . $order->status) }}</div>
                </div>
            </div>

            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left; padding:.7rem; border-bottom:1px solid rgba(255,255,255,.1);">{{ __('orders_page.item') }}</th>
                        <th style="text-align:center; padding:.7rem; border-bottom:1px solid rgba(255,255,255,.1);">{{ __('orders_page.qty') }}</th>
                        <th style="text-align:right; padding:.7rem; border-bottom:1px solid rgba(255,255,255,.1);">{{ __('orders_page.price') }}</th>
                        <th style="text-align:right; padding:.7rem; border-bottom:1px solid rgba(255,255,255,.1);">{{ __('orders_page.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $orderItem)
                        <tr>
                            <td style="padding:.7rem;">{{ $orderItem->item?->local_name ?: __('orders_page.product_removed') }}</td>
                            <td style="text-align:center; padding:.7rem;">{{ $orderItem->quantity }}</td>
                            <td style="text-align:right; padding:.7rem;">﷼ {{ number_format($orderItem->price, 2) }}</td>
                            <td style="text-align:right; padding:.7rem;">﷼ {{ number_format($orderItem->price * $orderItem->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="display:flex; justify-content:flex-end; margin-top:1rem;">
                <div style="min-width:260px;">
                    <div style="display:flex; justify-content:space-between; padding:.4rem 0;">
                        <span style="color:var(--elx-gray);">{{ __('orders_page.grand_total') }}</span>
                        <strong>﷼ {{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
