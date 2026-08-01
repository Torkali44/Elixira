@extends('layouts.framer')

@section('title', __('cart_page.page_title'))

@section('head')
    <style>
        .cart-container {
            max-width: 1380px !important;
            width: 100%;
            padding: 0 1rem;
        }

        .cart-layout-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.75fr) minmax(360px, 420px);
            gap: 2rem;
            align-items: start;
            margin-top: 0.5rem;
        }

        @media (max-width: 1100px) {
            .cart-layout-grid {
                grid-template-columns: 1fr;
            }
        }

        .cart-table-wrap {
            overflow-x: visible;
        }

        .cart-summary-card {
            position: sticky;
            top: 1.5rem;
        }

        .cart-summary-card .form-input,
        .cart-summary-card .code-select,
        .cart-summary-card textarea {
            font-size: 0.95rem;
        }

        .cart-card {
            background: var(--elx-glass);
            backdrop-filter: blur(42px);
            border: 1px solid var(--elx-border);
            border-radius: 16px;
            padding: 1.75rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        }

        .cart-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0 1rem;
        }

        .cart-table th {
            text-align: start;
            padding: 0.65rem 1.1rem 1.1rem;
            color: var(--elx-light);
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        /* Column widths — balanced spacing */
        .cart-table th:nth-child(1),
        .cart-table td:nth-child(1) {
            width: 24%;
            min-width: 170px;
        }

        .cart-table th:nth-child(2),
        .cart-table td:nth-child(2) {
            width: 10%;
            min-width: 88px;
        }

        .cart-table th:nth-child(3),
        .cart-table td:nth-child(3) {
            width: 12%;
            min-width: 88px;
        }

        .cart-table th:nth-child(4),
        .cart-table td:nth-child(4) {
            width: 8%;
            min-width: 64px;
        }

        .cart-table th:nth-child(5),
        .cart-table td:nth-child(5) {
            width: 10%;
            min-width: 84px;
        }

        .cart-table th:nth-child(6),
        .cart-table td:nth-child(6) {
            width: 14%;
            min-width: 100px;
        }

        .cart-table th:nth-child(2),
        .cart-table td:nth-child(2),
        .cart-table th:nth-child(3),
        .cart-table td:nth-child(3),
        .cart-table th:nth-child(6),
        .cart-table td:nth-child(6) {
            text-align: center;
        }

        .cart-table th:nth-child(4),
        .cart-table td:nth-child(4),
        .cart-table th:nth-child(5),
        .cart-table td:nth-child(5) {
            text-align: center;
        }

        .cart-table th:nth-child(7),
        .cart-table td:nth-child(7) {
            width: 6%;
            text-align: center;
        }

        .cart-col-price {
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
            font-size: 1rem;
            font-weight: 600;
            color: var(--elx-white);
            letter-spacing: 0.02em;
            padding-inline: 0.25rem;
            min-width: 5rem;
        }

        .cart-col-subtotal {
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--elx-cyan);
            letter-spacing: 0.02em;
            padding-inline: 0.25rem;
            min-width: 5.5rem;
        }

        .cart-col-points {
            font-size: 1rem;
            font-weight: 600;
            color: var(--elx-cyan);
            text-align: center;
        }

        .cart-row td {
            padding: 1.6rem 1.25rem;
            vertical-align: middle;
            text-align: start;
            min-height: 5.5rem;
        }

        .cart-product-cell {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            min-width: 0;
        }

        .cart-product-thumb {
            width: 72px;
            height: 72px;
            min-width: 72px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--elx-border);
            background: rgba(255, 255, 255, 0.03);
            flex-shrink: 0;
        }

        .cart-product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .cart-product-name {
            font-weight: 600;
            font-size: 1rem;
            line-height: 1.55;
            color: var(--elx-white);
            display: block;
            overflow: visible;
            min-width: 0;
            flex: 1;
            word-break: break-word;
        }

        .cart-row {
            background: rgba(255, 255, 255, 0.035);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            transition: var(--elx-transition);
        }

        .cart-row:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(74, 200, 246, 0.15);
        }

        .checkout-field-spacer {
            height: 1.25rem;
        }

        .cart-row td:first-child {
            border-radius: 15px 0 0 15px;
        }

        [dir="rtl"] .cart-row td:first-child {
            border-radius: 0 15px 15px 0;
        }

        .cart-row td:last-child {
            border-radius: 0 15px 15px 0;
        }

        [dir="rtl"] .cart-row td:last-child {
            border-radius: 15px 0 0 15px;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--elx-border);
            border-radius: 10px;
            color: var(--elx-white);
            margin-bottom: 1.25rem;
            outline: none;
            transition: var(--elx-transition);
            font-size: 1rem;
        }

        .form-input::placeholder {
            color: var(--elx-gray);
        }

        .form-input:focus {
            border-color: var(--elx-cyan);
            background: rgba(74, 200, 246, 0.08);
            box-shadow: 0 0 0 2px rgba(74, 200, 246, 0.1);
        }

        #selected_address {
            background-color: var(--elx-dark) !important;
            color: var(--elx-white);
        }

        #selected_address option {
            background-color: var(--elx-dark);
            color: var(--elx-white);
        }

        .remove-btn {
            background: rgba(220, 60, 60, 0.1);
            color: #ff8a8a;
            border: 1px solid rgba(220, 60, 60, 0.2);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .remove-btn:hover {
            background: rgba(220, 60, 60, 0.3);
            transform: scale(1.1);
        }

        /* Quantity — native number input */
        .cart-qty-form {
            margin: 0;
            display: inline-block;
        }

        .cart-qty-input {
            width: 4.5rem;
            padding: 0.5rem 0.35rem;
            text-align: center;
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--elx-white);
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--elx-border);
            border-radius: 8px;
            line-height: 1.2;
            -moz-appearance: auto;
            appearance: auto;
        }

        .cart-qty-input:focus {
            outline: none;
            border-color: var(--elx-cyan);
            box-shadow: 0 0 0 2px rgba(74, 200, 246, 0.15);
        }

        .cart-qty-input::-webkit-outer-spin-button,
        .cart-qty-input::-webkit-inner-spin-button {
            opacity: 1;
        }

        .cart-remove-form {
            display: inline-block;
            margin: 0;
        }

        .cart-variant-form {
            margin: 0;
        }

        .cart-flash {
            border-radius: 12px;
            padding: 0.85rem 1.15rem;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
        }

        .cart-flash--error {
            background: rgba(231, 76, 60, 0.12);
            border: 1px solid rgba(231, 76, 60, 0.35);
            color: #ffb4b4;
        }

        .cart-flash--success {
            background: rgba(46, 204, 113, 0.12);
            border: 1px solid rgba(46, 204, 113, 0.35);
            color: #9be7b4;
        }

        .cart-unavailable-banner {
            background: rgba(231, 76, 60, 0.12);
            border: 1px solid rgba(231, 76, 60, 0.35);
            color: #ffb4b4;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .cart-row--unavailable {
            outline: 1px solid rgba(231, 76, 60, 0.35);
        }

        .cart-size-select option:disabled {
            color: #888;
        }

        /* Code dropdown styles */
        .code-select {
            width: 100%;
            padding: 0.8rem 1.2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--elx-border);
            border-radius: 100px;
            color: var(--elx-white);
            margin-bottom: 0.5rem;
            outline: none;
            transition: var(--elx-transition);
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2399b5c5' viewBox='0 0 16 16'%3E%3Cpath d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.2rem center;
            padding-right: 2.5rem;
        }

        .code-select:focus {
            border-color: var(--elx-cyan);
        }

        [dir="rtl"] .code-select {
            background-position: left 1.2rem center;
            padding-right: 1.2rem;
            padding-left: 2.5rem;
        }

        .code-select option {
            background: #0d1a20;
            color: var(--elx-white);
            padding: 0.5rem;
        }

        .code-custom-input {
            display: none;
            margin-top: 0.5rem;
        }

        .code-custom-input.active {
            display: block;
        }

        .cart-size-cell {
            min-width: 88px;
            text-align: center;
            padding-inline: 0.35rem !important;
        }

        .cart-size-select {
            width: auto;
            min-width: 5.25rem;
            max-width: 7rem;
            padding: 0.45rem 1.55rem 0.45rem 0.5rem;
            background-color: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            color: #fff !important;
            -webkit-text-fill-color: #fff;
            outline: none;
            cursor: pointer;
            font-size: 0.88rem;
            font-weight: 600;
            line-height: 1.25;
            text-align: center;
            text-align-last: center;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='%23ffffff' viewBox='0 0 16 16'%3E%3Cpath d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.45rem center;
            padding-inline-end: 1.45rem;
        }

        [dir="rtl"] .cart-size-select {
            background-position: left 0.45rem center;
            padding-inline-end: 0.5rem;
            padding-inline-start: 1.45rem;
        }

        .cart-size-select:focus {
            border-color: var(--elx-cyan);
            box-shadow: 0 0 0 2px rgba(74, 200, 246, 0.15);
        }

        .cart-size-select option {
            background: #0d1a20;
            color: #fff;
            font-weight: 500;
        }

        .cart-size-label {
            display: inline-block;
            min-width: 5.25rem;
            max-width: 7rem;
            padding: 0.45rem 0.5rem;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            color: #fff;
            font-size: 0.88rem;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
        }

        /* Responsive Mobile Card Layout */
        @media (max-width: 850px) {
            .cart-table-wrap {
                overflow: visible;
            }

            .cart-table,
            .cart-table thead,
            .cart-table tbody,
            .cart-table tr,
            .cart-table td {
                display: block;
                width: 100% !important;
            }

            .cart-table thead {
                display: none;
            }

            .cart-row {
                position: relative;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid var(--elx-border);
                border-radius: 16px !important;
                padding: 1.25rem !important;
                margin-bottom: 1.25rem;
                display: flex !important;
                flex-direction: column;
                gap: 0.85rem;
            }

            .cart-row td {
                padding: 0 !important;
                border: none !important;
                border-radius: 0 !important;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 0.95rem;
                width: 100% !important;
                min-width: 0 !important;
            }

            /* Product column spans full width and has a line under it */
            .cart-row td:first-child {
                border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
                padding-bottom: 0.85rem !important;
                margin-bottom: 0.35rem;
                justify-content: flex-start;
            }

            .cart-product-cell {
                width: 100%;
            }

            .cart-product-name {
                font-size: 1rem;
            }

            /* Add labels on mobile */
            .cart-row td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--elx-gray);
                font-size: 0.85rem;
                text-transform: uppercase;
            }

            /* Remove labels for first (product) and last (delete) columns */
            .cart-row td:first-child::before,
            .cart-row td:last-child::before {
                content: none;
            }

            /* Position delete button nicely */
            .cart-row td:last-child {
                position: absolute;
                top: 1.25rem;
                right: 1.25rem;
                width: auto !important;
                justify-content: flex-end;
            }

            [dir="rtl"] .cart-row td:last-child {
                right: auto;
                left: 1.25rem;
            }
        }

        @media (max-width: 400px) {
            .cart-card {
                padding: 1.25rem;
            }

            .cart-row td {
                font-size: 0.9rem;
            }

            .cart-qty-input {
                width: 4rem;
                font-size: 1rem;
            }

            .cart-product-thumb {
                width: 70px;
                height: 70px;
                min-width: 70px;
            }
        }

        /* Eye-catching verify button styles & animation */
        @keyframes elxCheckBtnPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(74, 200, 246, 0.7), 0 0 10px rgba(74, 200, 246, 0.4);
                transform: scale(1);
            }

            50% {
                box-shadow: 0 0 20px 6px rgba(74, 200, 246, 0.9), 0 0 32px 10px rgba(0, 210, 255, 0.7);
                transform: scale(1.05);
                background: linear-gradient(135deg, #00d2ff 0%, #1f8db5 100%) !important;
            }

            100% {
                box-shadow: 0 0 0 0 rgba(74, 200, 246, 0.7), 0 0 10px rgba(74, 200, 246, 0.4);
                transform: scale(1);
            }
        }

        .elx-check-btn-eye-catching {
            animation: elxCheckBtnPulse 2.2s infinite ease-in-out;
            background: linear-gradient(135deg, #1f8db5 0%, #4ac8f6 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(74, 200, 246, 0.4);
        }

        .elx-check-btn-eye-catching:hover {
            transform: scale(1.08) !important;
            box-shadow: 0 0 25px rgba(74, 200, 246, 1) !important;
        }

        .elx-check-btn-eye-catching::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 50%;
            height: 200%;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.6) 50%,
                    rgba(255, 255, 255, 0) 100%);
            transform: rotate(30deg);
            animation: elxShine 3s infinite;
        }

        @keyframes elxShine {
            0% {
                left: -60%;
            }

            20% {
                left: 140%;
            }

            100% {
                left: 140%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <div class="elx-container cart-container">
            {{-- Section Header --}}
            <div class="elx-section__header" data-animate style="margin-bottom: 1.5rem;">
                <h1 class="elx-hero__title" style="font-size: 2rem; margin-bottom: 0;">
                    <span class="elx-hero__title-gradient">{{ __('cart_page.hero_title') }}</span>
                </h1>
            </div>

            @if(session('error'))
                <div class="cart-flash cart-flash--error" data-animate>{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="cart-flash cart-flash--success" data-animate>{{ session('success') }}</div>
            @endif

            @if(!empty($cartLines))
                @php
                    $cartPricing = app(\App\Support\ItemPricingService::class);
                @endphp
                @if($hasUnavailableItems)
                    <div class="cart-unavailable-banner" data-animate>
                        {{ __('cart_page.cart_has_unavailable_items') }}
                    </div>
                @endif
                <div class="cart-layout-grid">
                    {{-- Cart Items --}}
                    <div data-animate>
                        <div class="cart-card">
                            <div class="cart-table-wrap">
                                <table class="cart-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('cart_page.product') }}</th>
                                            <th>{{ __('cart_page.size') }}</th>
                                            <th>{{ __('cart_page.price') }}</th>
                                            <th>{{ __('cart_page.points') }}</th>
                                            <th>{{ __('cart_page.quantity') }}</th>
                                            <th>{{ __('cart_page.subtotal') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cartLines as $line)
                                            <tr class="cart-row @if($line['is_unavailable']) cart-row--unavailable @endif"
                                                data-id="{{ $line['id'] }}" data-max-qty="{{ $line['max_quantity'] }}">
                                                <td>
                                                    <div class="cart-product-cell">
                                                        <div class="cart-product-thumb">
                                                            @if(!empty($line['image']))
                                                                <img src="{{ asset('storage/' . $line['image']) }}"
                                                                    alt="{{ $line['display_name'] }}">
                                                            @else
                                                                <div
                                                                    style="width: 100%; height: 100%; background: #1a2e38; display: flex; align-items: center; justify-content: center; color: var(--elx-cyan); font-size: 1.5rem;">
                                                                    <i class="fas fa-leaf"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <span class="cart-product-name">{{ $line['display_name'] }}</span>
                                                    </div>
                                                </td>
                                                <td class="cart-size-cell" data-label="{{ __('cart_page.size') }}">
                                                    @if(!empty($line['variant_options']))
                                                        <form method="POST" action="{{ route('cart.update-variant') }}"
                                                            class="cart-variant-form">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="id" value="{{ $line['id'] }}">
                                                            <select name="country_price_id" class="cart-size-select"
                                                                aria-label="{{ __('cart_page.size') }}" onchange="this.form.submit()">
                                                                @foreach($line['variant_options'] as $option)
                                                                    <option value="{{ $option['id'] }}" @selected((int) $line['current_variant_id'] === (int) $option['id'])
                                                                        @disabled($option['stock'] <= 0)>
                                                                        {{ $option['label'] }}@if($option['stock'] <= 0)
                                                                        ({{ __('shop.out_of_stock') }})@endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </form>
                                                    @elseif(filled($line['current_size_label']))
                                                        <span class="cart-size-label">{{ $line['current_size_label'] }}</span>
                                                    @else
                                                        <span class="cart-size-label"
                                                            style="color: var(--elx-gray); font-weight: 500;">—</span>
                                                    @endif
                                                </td>
                                                <td class="cart-col-price" data-label="{{ __('cart_page.price') }}">
                                                    {{ $cartPricing->formatCompactPrice($line['price'], $line['currency_symbol']) }}
                                                </td>
                                                <td class="cart-col-points" data-label="{{ __('cart_page.points') }}">
                                                    {{ $line['points'] }}</td>
                                                <td data-label="{{ __('cart_page.quantity') }}" style="text-align: center;">
                                                    <form method="POST" action="{{ route('cart.update') }}" class="cart-qty-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="id" value="{{ $line['id'] }}">
                                                        <input type="number" name="quantity" class="cart-qty-input"
                                                            value="{{ $line['quantity'] }}" min="1"
                                                            max="{{ $line['max_quantity'] }}" inputmode="numeric"
                                                            aria-label="{{ __('cart_page.quantity') }}"
                                                            onchange="if (this.value !== '') { this.form.submit(); }">
                                                    </form>
                                                </td>
                                                <td class="cart-col-subtotal" data-label="{{ __('cart_page.subtotal') }}">
                                                    {{ $cartPricing->formatCompactPrice($line['subtotal'], $line['currency_symbol']) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    <form method="POST" action="{{ route('cart.remove') }}" class="cart-remove-form"
                                                        onsubmit="return confirm(@json(__('cart_page.remove_confirm')))">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="id" value="{{ $line['id'] }}">
                                                        <button type="submit" class="remove-btn"
                                                            aria-label="{{ __('cart_page.remove_confirm') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Order Summary & Checkout --}}
                    <div data-animate>
                        <div class="cart-card cart-summary-card">
                            <h3 class="elx-product-card__name"
                                style="font-size: 1.5rem; margin-bottom: 2rem; color: var(--elx-accent);">
                                {{ __('cart_page.order_summary') }}</h3>

                            <div
                                style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--elx-gray);">
                                <span>{{ __('cart_page.products_subtotal') }}</span>
                                <span id="cart-subtotal-display" style="color: var(--elx-white); font-weight: 600;">
                                    {{ $cartPricing->formatCompactPrice($total, $cartCurrency) }}
                                </span>
                            </div>
                            <div id="delivery-fee-row"
                                style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--elx-gray);">
                                <span>{{ __('cart_page.delivery_fee') }}</span>
                                <span id="cart-delivery-fee-display" style="color: var(--elx-cyan); font-weight: 600;">—</span>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--elx-gray);">
                                <span>{{ __('cart_page.total_amount') }}</span>
                                <span id="cart-grand-total-display" data-subtotal="{{ (float) $total }}"
                                    data-currency="{{ $cartCurrency }}" data-locale="{{ app()->getLocale() }}"
                                    style="color: var(--elx-white); font-weight: 700; font-size: 1.2rem;">
                                    {{ $cartPricing->formatCompactPrice($total, $cartCurrency) }}
                                </span>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--elx-gray);">
                                <span>{{ __('cart_page.total_points') }}</span>
                                <span
                                    style="color: var(--elx-cyan); font-weight: 700; font-size: 1.2rem;">{{ $totalPoints }}</span>
                            </div>

                            <hr style="border: none; border-top: 1px solid var(--elx-border); margin: 2rem 0;">

                            @if ($errors->any())
                                <div class="cart-flash cart-flash--error" style="margin-bottom: 1rem;" data-animate>
                                    <strong>{{ __('cart_page.error_title') }}</strong>
                                    <ul style="margin: 0.5rem 0 0; padding-inline-start: 1.2rem;">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('checkout') }}" method="POST">
                                @csrf
                                <input type="text" name="customer_name" class="form-input"
                                    placeholder="{{ __('cart_page.full_name') }}"
                                    value="{{ auth()->check() ? auth()->user()->name : old('customer_name') }}" required>
                                @error('customer_name')
                                    <div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">
                                {{ $message }}</div>@enderror

                                @php
                                    $phone = auth()->check() ? auth()->user()->phone : old('phone_number');
                                    $cCode = old('country_code', $cartPhoneCountry ?? '+966');
                                    $pNum = $phone;
                                    if ($phone && str_starts_with($phone, '+971')) {
                                        $cCode = '+971';
                                        $pNum = substr($phone, 4);
                                    } elseif ($phone && str_starts_with($phone, '+966')) {
                                        $cCode = '+966';
                                        $pNum = substr($phone, 4);
                                    }
                                @endphp
                                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; align-items: stretch;">
                                    <div style="flex: 0 0 auto; min-width: 8.75rem; max-width: 11rem;">
                                        <x-country-code-picker name="country_code" :value="$cCode" variant="cart" />
                                    </div>
                                    <input type="tel" name="phone_number" class="form-input"
                                        placeholder="{{ __('cart_page.phone') }}" value="{{ $pNum }}"
                                        style="flex: 1; margin-bottom: 0;" required>
                                </div>
                                @error('phone_number')
                                    <div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">
                                {{ $message }}</div>@enderror
                                @error('country_code')
                                    <div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">
                                {{ $message }}</div>@enderror

                                @if(!empty($deliveryCityOptions))
                                    <label for="delivery_city_id"
                                        style="display: block; color: var(--elx-light); font-size: 0.85rem; margin-bottom: 0.35rem;">{{ __('cart_page.delivery_city_label') }}</label>
                                    <select name="delivery_city_id" id="delivery_city_id" class="form-input"
                                        style="cursor: pointer; margin-bottom: 1rem;" required
                                        data-fees='@json($deliveryFeesById ?? [])'
                                        onchange="window.elxCartUpdateDeliveryFee && window.elxCartUpdateDeliveryFee()">
                                        <option value="">{{ __('cart_page.select_delivery_city') }}</option>
                                        @foreach($deliveryCityOptions as $cityOption)
                                            <option value="{{ $cityOption['id'] }}" data-fee="{{ $cityOption['delivery_fee'] }}"
                                                @selected((string) old('delivery_city_id') === (string) $cityOption['id'])>
                                                {{ $cityOption['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select name="delivery_city_id" id="delivery_city_id" class="form-input"
                                        style="display: none; margin-bottom: 1rem;" data-fees="{}">
                                        <option value=""></option>
                                    </select>
                                @endif
                                @error('delivery_city_id')
                                    <div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">
                                {{ $message }}</div>@enderror

                                <label for="shared_shipping_order_input"
                                    style="display: block; color: var(--elx-light); font-size: 0.85rem; margin-bottom: 0.35rem;">{{ __('cart_page.shared_shipping_order_label') }}</label>
                                <p style="color: var(--elx-gray); font-size: 0.8rem; margin: 0 0 0.5rem; line-height: 1.4;">
                                    {{ __('cart_page.shared_shipping_order_hint') }}</p>
                                @php
                                    $oldSharedReference = old('shared_shipping_reference');
                                    if (!$oldSharedReference && old('shared_shipping_order_id')) {
                                        $oldSharedReference = \App\Models\Order::query()
                                            ->whereKey(old('shared_shipping_order_id'))
                                            ->value('reference');
                                    }
                                @endphp
                                <div style="display: flex; gap: 0.5rem; margin-bottom: 0.75rem; align-items: stretch;">
                                    <input type="text" id="shared_shipping_order_input" class="form-input" autocomplete="off"
                                        maxlength="6" placeholder="{{ __('cart_page.shared_shipping_order_placeholder') }}"
                                        value="{{ $oldSharedReference }}"
                                        style="flex: 1; margin-bottom: 0; text-transform: uppercase; letter-spacing: 0.08em;">
                                    <button type="button" id="shared-shipping-check-btn"
                                        class="elx-btn elx-btn--primary elx-check-btn-eye-catching"
                                        style="padding: 0.85rem 1.1rem; white-space: nowrap;"
                                        onclick="window.elxCartCheckSharedShipping && window.elxCartCheckSharedShipping(); return false;">
                                        <i class="fas fa-check-circle me-1"></i> {{ __('cart_page.shared_shipping_check') }}
                                    </button>
                                    <button type="button" id="shared-shipping-clear-btn" class="elx-btn"
                                        style="display: none; padding: 0.85rem 1.1rem; white-space: nowrap; background: rgba(255,255,255,0.08); color: var(--elx-white); border: 1px solid var(--elx-border);"
                                        onclick="window.elxCartClearSharedShipping && window.elxCartClearSharedShipping(); return false;">
                                        {{ __('cart_page.shared_shipping_clear') }}
                                    </button>
                                </div>
                                <input type="hidden" name="shared_shipping_order_id" id="shared_shipping_order_id"
                                    value="{{ old('shared_shipping_order_id') }}">
                                <div id="shared-shipping-status"
                                    style="display: none; font-size: 0.85rem; margin-bottom: 1rem; line-height: 1.4;"></div>
                                @error('shared_shipping_order_id')
                                    <div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">
                                {{ $message }}</div>@enderror

                                @php
                                    $userCode = auth()->user()?->user_code ?? '';
                                    $defaultCode = $userCode ?: ($sponsorCodes->first()?->code ?? '');
                                @endphp
                                <input type="hidden" name="user_code" id="user_code_hidden"
                                    value="{{ old('user_code', $defaultCode) }}">

                                <select class="code-select" id="code_select" onchange="handleCodeSelect(this)">
                                    @if($userCode)
                                        <option value="{{ $userCode }}" selected>{{ $userCode }} ({{ __('cart_page.my_code') }})
                                        </option>
                                    @endif
                                    @foreach($sponsorCodes as $sc)
                                        @if($sc->code !== $userCode)
                                            <option value="{{ $sc->code }}" {{ !$userCode && $loop->first ? 'selected' : '' }}>
                                                {{ $sc->code }} — {{ $sc->sponsor_name }}
                                            </option>
                                        @endif
                                    @endforeach
                                    <option value="__other__">{{ __('cart_page.other_code') }}</option>
                                </select>
                                <div class="code-custom-input" id="code_custom_wrap">
                                    <input type="text" class="form-input" id="code_custom_input"
                                        placeholder="{{ __('cart_page.enter_code') }}" style="margin-bottom: 0;"
                                        oninput="document.getElementById('user_code_hidden').value = this.value.toUpperCase(); this.value = this.value.toUpperCase();">
                                </div>
                                @error('user_code')
                                    <div style="color: #ff8a8a; font-size: 0.8rem; margin-top: 0.25rem; margin-bottom: 1rem;">
                                {{ $message }}</div>@enderror

                                <div class="checkout-field-spacer"></div>

                                @auth
                                    @php
                                        $userAddresses = auth()->user()->addresses;
                                        $mainAddress = $userAddresses->where('is_main', true)->first();
                                        $mainAddressText = $mainAddress ? $mainAddress->address : '';
                                        $defaultAddress = old('address', $mainAddressText);
                                    @endphp
                                    @if($userAddresses->count() > 0)
                                        <select id="selected_address" class="form-input" style="cursor: pointer; margin-bottom: 0;">
                                            <option value="" disabled {{ !$mainAddress ? 'selected' : '' }}>
                                                {{ __('cart_page.select_address') }}</option>
                                            @foreach($userAddresses as $addr)
                                                <option value="{{ $addr->address }}" {{ ($defaultAddress === $addr->address || ($mainAddress && $addr->is_main && !old('address'))) ? 'selected' : '' }}>
                                                    {{ \Illuminate\Support\Str::limit($addr->address, 60) }}
                                                    {{ $addr->is_main ? __('cart_page.main_address') : '' }}
                                                </option>
                                            @endforeach
                                            <option value="__new__">{{ __('cart_page.add_new_address') }}</option>
                                        </select>
                                        <input type="hidden" name="address" id="address" value="{{ $defaultAddress }}">
                                        <input type="text" id="address_new" class="form-input"
                                            placeholder="{{ __('cart_page.shipping_address') }}"
                                            style="display: none; margin-top: 1rem;">
                                        <div id="new_address_controls"
                                            style="margin-top: 0.75rem; margin-bottom: 1rem; color: var(--elx-light); font-size: 0.9rem; display: none;">
                                            <label><input type="checkbox" name="save_address" value="1" checked>
                                                {{ __('cart_page.save_address') }}</label>
                                            &nbsp;&nbsp;
                                            <label><input type="checkbox" name="is_main_address" value="1" checked>
                                                {{ __('cart_page.set_main') }}</label>
                                        </div>
                                    @else
                                        <input type="text" name="address" class="form-input"
                                            placeholder="{{ __('cart_page.shipping_address') }}" value="{{ old('address') }}" required>
                                        <div id="new_address_controls"
                                            style="margin-bottom: 1rem; color: var(--elx-light); font-size: 0.9rem;">
                                            <label><input type="checkbox" name="save_address" value="1" checked>
                                                {{ __('cart_page.save_address') }}</label>
                                            &nbsp;&nbsp;
                                            <label><input type="checkbox" name="is_main_address" value="1" checked>
                                                {{ __('cart_page.set_main') }}</label>
                                        </div>
                                    @endif
                                @else
                                    <input type="text" name="address" class="form-input"
                                        placeholder="{{ __('cart_page.shipping_address') }}" value="{{ old('address') }}" required>
                                @endauth
                                @error('address')
                                    <div style="color: #ff8a8a; font-size: 0.8rem; margin-top: 0.35rem; margin-bottom: 1rem;">
                                {{ $message }}</div>@enderror

                                <textarea name="notes" class="form-input" style="border-radius: 15px;"
                                    placeholder="{{ __('cart_page.notes') }}" rows="2">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">
                                {{ $message }}</div>@enderror

                                <button type="submit" class="elx-btn elx-btn--primary"
                                    style="width: 100%; justify-content: center; padding: 1rem; margin-top: 1rem;"
                                    id="checkout-submit-btn" @if($hasUnavailableItems) disabled @endif>
                                    {{ __('cart_page.place_order') }}
                                </button>
                            </form>

                            @php
                                $cartCheckoutConfig = [
                                    'subtotal' => (float) $total,
                                    'locale' => app()->getLocale(),
                                    'currency' => $cartCurrency,
                                    'fees' => $deliveryFeesById ?? [],
                                    'sharedLookupUrl' => route('cart.shared-shipping-order'),
                                    'deliveryCitiesUrl' => route('delivery-cities.index'),
                                    'checkoutUrl' => route('checkout'),
                                    'hasUnavailableItems' => (bool) $hasUnavailableItems,
                                    'i18n' => [
                                        'sharedFree' => __('cart_page.shared_shipping_free'),
                                        'sharedApplied' => __('cart_page.shared_shipping_applied'),
                                        'sharedNotFound' => __('cart_page.shared_shipping_not_found'),
                                        'sharedShipped' => __('cart_page.shared_shipping_already_shipped'),
                                        'sharedCountryMismatch' => __('cart_page.shared_shipping_country_mismatch'),
                                        'sharedUnverifiedTitle' => __('cart_page.shared_unverified_warning_title'),
                                        'sharedUnverifiedText' => __('cart_page.shared_unverified_warning_text'),
                                        'sharedUnverifiedVerifyBtn' => __('cart_page.shared_unverified_verify_btn'),
                                        'sharedUnverifiedClearBtn' => __('cart_page.shared_unverified_clear_btn'),
                                        'loadingCities' => __('cart_page.loading_cities'),
                                        'selectCity' => __('cart_page.select_delivery_city'),
                                        'cityRequired' => __('cart_page.delivery_city_required'),
                                        'codeRequired' => __('cart_page.code_required'),
                                        'unavailable' => __('cart_page.cart_has_unavailable_items'),
                                        'errorTitle' => __('popups.error_title'),
                                    ],
                                ];
                            @endphp
                            <script type="application/json"
                                id="cart-checkout-config">{!! json_encode($cartCheckoutConfig, JSON_UNESCAPED_UNICODE) !!}</script>
                            @php
                                $cartCheckoutScript = public_path('js/cart-checkout.js');
                                $cartCheckoutVersion = file_exists($cartCheckoutScript) ? filemtime($cartCheckoutScript) : time();
                            @endphp
                            <script src="{{ asset('js/cart-checkout.js') }}?v={{ $cartCheckoutVersion }}"></script>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5" data-animate>
                    <div style="font-size: 4rem; color: rgba(74, 200, 246, 0.2); margin-bottom: 2rem;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3 style="font-size: 2rem; margin-bottom: 1rem;">{{ __('cart_page.empty_title') }}</h3>
                    <p style="color: var(--elx-gray); margin-bottom: 2rem;">{{ __('cart_page.empty_desc') }}</p>
                    <a href="{{ route('menu.index') }}"
                        class="elx-btn elx-btn--primary">{{ __('cart_page.shop_collections') }}</a>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    @if(!empty($cartLines))
        <script>
            function handleCodeSelect(sel) {
                var hidden = document.getElementById('user_code_hidden');
                var customWrap = document.getElementById('code_custom_wrap');
                var customInput = document.getElementById('code_custom_input');
                if (sel.value === '__other__') {
                    customWrap.classList.add('active');
                    customInput.value = '';
                    hidden.value = '';
                    customInput.focus();
                } else {
                    customWrap.classList.remove('active');
                    customInput.value = '';
                    hidden.value = sel.value;
                }
            }
        </script>
    @endif
@endsection