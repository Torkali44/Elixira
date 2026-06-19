<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('orders_page.invoice_title', ['id' => $order->id]) }} – Elixira</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ── RESET & BASE ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── SCREEN: Dark background wrapper ── */
        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            background: #0b1520;
            min-height: 100vh;
            padding: 2rem 1rem;
            color: #1a2550;
        }

        /* ── SCREEN CONTROLS ── */
        .screen-controls {
            max-width: 860px;
            margin: 0 auto 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .ctrl-group {
            display: flex;
            gap: 0.6rem;
            align-items: center;
        }
        .ctrl-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1.2rem;
            border-radius: 100px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s ease;
            line-height: 1;
        }
        .ctrl-btn--glass {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
        }
        .ctrl-btn--glass:hover {
            background: rgba(255,255,255,0.12);
            border-color: rgba(74,200,246,0.5);
            color: #4ac8f6;
        }
        .ctrl-btn--primary {
            background: linear-gradient(135deg, #4ac8f6, #2563eb);
            color: #fff;
        }
        .ctrl-btn--primary:hover {
            opacity: 0.88;
            transform: translateY(-1px);
        }
        .ctrl-btn--lang {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.12);
            color: #adb5bd;
            font-size: 0.8rem;
            padding: 0.45rem 0.9rem;
        }
        .ctrl-btn--lang:hover {
            border-color: rgba(74,200,246,0.4);
            color: #4ac8f6;
        }

        /* ── INVOICE CARD ── */
        .inv {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4), 0 4px 16px rgba(0,0,0,0.2);
            max-width: 860px;
            margin: 0 auto;
            padding: 52px 56px;
            font-size: 14px;
            line-height: 1.6;
            color: #1a2550;
        }

        /* ── HEADER: logo + INVOICE ── */
        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 36px;
        }
        .inv-logo img { height: 36px; width: auto; }
        .inv-title {
            font-size: 2.5rem;
            font-weight: 900;
            letter-spacing: 4px;
            color: #1a2550;
            text-transform: uppercase;
            line-height: 1;
        }

        /* ── META ROW ── */
        .inv-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 28px;
        }
        .inv-co-name {
            font-weight: 700;
            font-size: 1rem;
            color: #1a2550;
            margin-bottom: 6px;
        }
        .inv-co-detail {
            color: #6b7280;
            font-size: 0.85rem;
            line-height: 1.75;
        }
        .inv-info-tbl {
            margin-left: auto;
            border-collapse: collapse;
        }
        .inv-info-tbl td { padding: 2px 0; font-size: 0.85rem; }
        .inv-info-tbl td:first-child {
            color: #9ca3af;
            padding-right: 16px;
            white-space: nowrap;
            text-align: left;
        }
        .inv-info-tbl td:last-child {
            font-weight: 600;
            color: #1a2550;
            text-align: right;
        }
        .inv-num  { color: #2563eb !important; font-weight: 700 !important; }
        .status-pending   { color: #f59e0b !important; }
        .status-confirmed, .status-delivered { color: #10b981 !important; }
        .status-cancelled { color: #ef4444 !important; }

        /* ── DIVIDER ── */
        .inv-hr {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 28px 0;
        }

        /* ── BILL TO ── */
        .bill-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1.8px;
            color: #2563eb;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .bill-name  { font-weight: 700; font-size: 1rem; color: #1a2550; margin-bottom: 4px; }
        .bill-info  { color: #6b7280; font-size: 0.85rem; line-height: 1.75; }

        /* ── ITEMS TABLE ── */
        .inv-tbl { width: 100%; border-collapse: collapse; margin: 28px 0; }
        .inv-tbl thead tr   { background: #1a2550; color: #fff; }
        .inv-tbl thead th   {
            padding: 13px 16px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            text-align: left;
        }
        .inv-tbl thead th:first-child { border-radius: 9px 0 0 9px; width: 44px; text-align: center; }
        .inv-tbl thead th:last-child  { border-radius: 0 9px 9px 0; text-align: right; }
        .inv-tbl thead th.c  { text-align: center; }
        .inv-tbl thead th.r  { text-align: right; }
        .inv-tbl tbody tr   { border-bottom: 1px solid #f3f4f6; }
        .inv-tbl tbody tr:last-child { border-bottom: none; }
        .inv-tbl tbody td   { padding: 13px 16px; color: #374151; font-size: 0.9rem; }
        .inv-tbl tbody td:first-child { text-align: center; color: #9ca3af; font-weight: 600; }
        .inv-tbl tbody td:nth-child(3) { text-align: center; }
        .inv-tbl tbody td:nth-child(4) { text-align: right; }
        .inv-tbl tbody td:last-child   { text-align: right; font-weight: 600; color: #1a2550; }

        /* ── SUMMARY ── */
        .inv-summary { display: flex; justify-content: flex-end; margin-top: 4px; }
        .inv-summary-box { width: 320px; }
        .sum-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 0;
            font-size: 0.875rem;
            color: #6b7280;
            border-bottom: 1px solid #f3f4f6;
        }
        .sum-row:last-of-type { border-bottom: none; }
        .sum-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            background: #eff6ff;
            border-radius: 10px;
            margin-top: 10px;
        }
        .sum-total-label {
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #2563eb;
        }
        .sum-total-val { font-size: 1.3rem; font-weight: 800; color: #2563eb; }

        /* ── FOOTER ── */
        .inv-footer { text-align: center; padding-top: 32px; }
        .inv-footer-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 2px solid #2563eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 1.1rem;
            margin-bottom: 12px;
        }
        .inv-footer-title { font-weight: 700; font-size: 1rem; color: #1a2550; margin-bottom: 4px; }
        .inv-footer-sub   { color: #9ca3af; font-size: 0.85rem; }

        /* ── RTL SUPPORT ── */
        [dir="rtl"] .inv-info-tbl td:first-child { text-align: right; padding-right: 0; padding-left: 16px; }
        [dir="rtl"] .inv-info-tbl td:last-child  { text-align: left; }
        [dir="rtl"] .inv-tbl thead th { text-align: right; }
        [dir="rtl"] .inv-tbl thead th:first-child { border-radius: 0 9px 9px 0; text-align: center; }
        [dir="rtl"] .inv-tbl thead th:last-child  { border-radius: 9px 0 0 9px; text-align: left; }
        [dir="rtl"] .inv-tbl tbody td:last-child   { text-align: left; }
        [dir="rtl"] .inv-tbl tbody td:nth-child(4) { text-align: left; }
        [dir="rtl"] .sum-row { flex-direction: row; }
        [dir="rtl"] .ctrl-group { flex-direction: row-reverse; }

        /* ══════════════════════════════════════════
           PRINT STYLES — only the white invoice
        ══════════════════════════════════════════ */
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm 14mm;
            }

            html, body {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color-adjust: exact;
            }

            /* Hide everything except the invoice card */
            .screen-controls { display: none !important; }

            .inv {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                margin: 0 !important;
            }

            /* Force table header dark background in print */
            .inv-tbl thead tr { background: #1a2550 !important; color: #fff !important; }
            .inv-tbl thead th { color: #fff !important; }

            /* Force summary total blue */
            .sum-total { background: #eff6ff !important; }

            /* Avoid page breaks inside rows */
            .inv-tbl tbody tr { page-break-inside: avoid; }
            .inv-summary { page-break-inside: avoid; }
            .inv-footer  { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

    {{-- ══ SCREEN CONTROLS (hidden on print) ══ --}}
    <div class="screen-controls">
        {{-- Left: Back button --}}
        <div class="ctrl-group">
            <a href="{{ route('profile.orders.show', $order) }}" class="ctrl-btn ctrl-btn--glass">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
                {{ __('orders_page.back_to_order') }}
            </a>
        </div>

        {{-- Right: Language + Print --}}
        <div class="ctrl-group">
            @if(app()->getLocale() === 'ar')
                <a href="{{ route('lang.switch', 'en') }}" class="ctrl-btn ctrl-btn--lang">
                    <i class="fas fa-language"></i> English
                </a>
            @else
                <a href="{{ route('lang.switch', 'ar') }}" class="ctrl-btn ctrl-btn--lang">
                    <i class="fas fa-language"></i> العربية
                </a>
            @endif

            <button onclick="window.print()" class="ctrl-btn ctrl-btn--primary">
                <i class="fas fa-print"></i> {{ __('orders_page.print_invoice') }}
            </button>
        </div>
    </div>

    {{-- ══ INVOICE CARD ══ --}}
    <div class="inv">

        {{-- Header --}}
        <div class="inv-header">
            <div class="inv-logo">
                <img src="https://framerusercontent.com/images/uXbQX90j2iRjfRCUW6NdMiNzUVM.png" alt="Elixira">
            </div>
            <h1 class="inv-title">{{ __('orders_page.invoice') }}</h1>
        </div>

        {{-- Company Info + Invoice Details --}}
        <div class="inv-meta">
            <div>
                <div class="inv-co-name">Elixira</div>
                <div class="inv-co-detail">
                    Dubai, United Arab Emirates<br>
                    +971 14 784 165<br>
                    www.elixira.com
                </div>
            </div>
            <div>
                <table class="inv-info-tbl">
                    <tr>
                        <td>{{ __('orders_page.invoice_number') }}</td>
                        <td class="inv-num">INV-{{ $order->created_at->format('Y-m-d') }}-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('orders_page.invoice_date') }}</td>
                        <td>{{ $order->created_at->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('orders_page.time') }}</td>
                        <td>{{ $order->created_at->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('orders_page.status') }}</td>
                        <td class="status-{{ $order->status }}">
                            {{ __('notifications.status.' . $order->status) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <hr class="inv-hr">

        {{-- Bill To --}}
        <div style="margin-bottom: 6px;">
            <div class="bill-label">{{ __('orders_page.bill_to') }}</div>
            <div class="bill-name">{{ $order->customer_name }}</div>
            <div class="bill-info">
                <x-phone-flag :phone="$order->customer_phone" />
                @if($order->customer_email)
                    <br>{{ $order->customer_email }}
                @endif
                @if($order->address)
                    <br>{{ $order->address }}
                @endif
            </div>
        </div>

        {{-- Items Table --}}
        <table class="inv-tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('orders_page.description') }}</th>
                    <th class="c">{{ __('orders_page.qty') }}</th>
                    <th class="r">{{ __('orders_page.unit_price') }}</th>
                    <th class="r">{{ __('orders_page.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $i => $orderItem)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            @if($orderItem->product_name)
                                {{ $orderItem->product_name }}
                            @elseif($orderItem->item)
                                {{ $orderItem->item->local_name }}
                            @else
                                {{ __('orders_page.product_removed') }}
                            @endif
                        </td>
                        <td>{{ $orderItem->quantity }}</td>
                        <td>﷼ {{ number_format($orderItem->price, 2) }}</td>
                        <td>﷼ {{ number_format($orderItem->price * $orderItem->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Summary --}}
        <div class="inv-summary">
            <div class="inv-summary-box">
                @php
                    $subtotal = $order->orderItems->sum(fn($i) => $i->price * $i->quantity);
                    $discount = (float) ($order->discount_amount ?? 0);
                    $shipping = (float) ($order->shipping_amount ?? 0);
                @endphp
                <div class="sum-row">
                    <span>{{ __('orders_page.subtotal') }}</span>
                    <span>﷼ {{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="sum-row">
                    <span>{{ __('orders_page.discount') }}</span>
                    <span>﷼ {{ number_format($discount, 2) }}</span>
                </div>
                <div class="sum-row">
                    <span>{{ __('orders_page.shipping') }}</span>
                    <span>﷼ {{ number_format($shipping, 2) }}</span>
                </div>
                <div class="sum-total">
                    <span class="sum-total-label">{{ __('orders_page.grand_total') }}</span>
                    <span class="sum-total-val">﷼ {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <hr class="inv-hr" style="margin-top: 36px;">
        <div class="inv-footer">
            <div class="inv-footer-icon"><i class="fas fa-heart"></i></div>
            <div class="inv-footer-title">{{ __('orders_page.thank_you_footer') }}</div>
            <div class="inv-footer-sub">{{ __('orders_page.contact_hint') }}</div>
        </div>

    </div>{{-- .inv --}}

</body>
</html>
