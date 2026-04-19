@extends('layouts.framer')

@section('title', 'Your cart — Elixira')

@section('head')
<style>
    .cart-card {
        background: var(--elx-glass);
        backdrop-filter: blur(42px);
        border: 1px solid var(--elx-border);
        border-radius: var(--elx-radius-sm);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .cart-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 1rem;
    }
    .cart-table th {
        text-align: left;
        padding: 0 1rem;
        color: var(--elx-light);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    .cart-row {
        background: rgba(255, 255, 255, 0.03);
        transition: var(--elx-transition);
    }
    .cart-row:hover {
        background: rgba(255, 255, 255, 0.06);
    }
    .cart-row td {
        padding: 1.5rem 1rem;
    }
    .cart-row td:first-child { border-radius: 15px 0 0 15px; }
    .cart-row td:last-child { border-radius: 0 15px 15px 0; }
    
    .form-input {
        width: 100%;
        padding: 0.8rem 1.2rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--elx-border);
        border-radius: 100px;
        color: var(--elx-white);
        margin-bottom: 1rem;
        outline: none;
        transition: var(--elx-transition);
    }
    .form-input:focus { border-color: var(--elx-cyan); }
    
    .qty-input {
        width: 60px;
        text-align: center;
        background: transparent;
        border: 1px solid var(--elx-border);
        color: var(--elx-white);
        border-radius: 5px;
        padding: 0.3rem;
    }
    .remove-btn {
        background: rgba(220, 60, 60, 0.1);
        color: #ff8a8a;
        border: 1px solid rgba(220, 60, 60, 0.2);
        width: 35px; height: 35px;
        border-radius: 50%;
        cursor: pointer;
        transition: 0.3s;
    }
    .remove-btn:hover {
        background: rgba(220, 60, 60, 0.3);
        transform: scale(1.1);
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        {{-- Section Header --}}
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">Shopping Bag</span>
            </h1>
        </div>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="elx-insights__grid" style="grid-template-columns: 2fr 1.2fr; gap: 2rem; align-items: start;">
                {{-- Cart Items --}}
                <div data-animate>
                    <div class="cart-card">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0 @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity'] @endphp
                                    <tr class="cart-row" data-id="{{ $id }}">
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 1rem;">
                                                <div style="width: 60px; height: 60px; border-radius: 10px; overflow: hidden; border: 1px solid var(--elx-border);">
                                                    @if(isset($details['image']) && $details['image'])
                                                        <img src="{{ asset('storage/' . $details['image']) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="">
                                                    @else
                                                        <div style="width: 100%; height: 100%; background: #1a2e38; display: flex; align-items: center; justify-content: center; color: var(--elx-cyan);">
                                                            <i class="fas fa-leaf"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <span style="font-weight: 600;">{{ $details['name'] }}</span>
                                            </div>
                                        </td>
                                        <td>SAR {{ number_format($details['price'], 2) }}</td>
                                        <td>
                                            <input type="number" value="{{ $details['quantity'] }}" class="qty-input update-cart" min="1" max="50">
                                        </td>
                                        <td style="color: var(--elx-cyan); font-weight: 700;">SAR {{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                        <td style="text-align: right;">
                                            <button class="remove-btn remove-from-cart" data-id="{{ $id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Order Summary & Checkout --}}
                <div data-animate>
                    <div class="cart-card">
                        <h3 class="elx-product-card__name" style="font-size: 1.5rem; margin-bottom: 2rem; color: var(--elx-accent);">Order Summary</h3>
                        
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--elx-gray);">
                            <span>Total Amount</span>
                            <span style="color: var(--elx-white); font-weight: 700; font-size: 1.2rem;">SAR {{ number_format($total, 2) }}</span>
                        </div>
                        
                        <hr style="border: none; border-top: 1px solid var(--elx-border); margin: 2rem 0;">

                        <form action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <input type="text" name="customer_name" class="form-input" placeholder="Full Name *" required>
                            <input type="tel" name="customer_phone" class="form-input" placeholder="Phone Number *" required>
                            <input type="text" name="address" class="form-input" placeholder="Shipping Address *" required>
                            <textarea name="notes" class="form-input" style="border-radius: 15px;" placeholder="Notes (optional)" rows="2"></textarea>
                            
                            <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem; margin-top: 1rem;">
                                Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5" data-animate>
                <div style="font-size: 4rem; color: rgba(74, 200, 246, 0.2); margin-bottom: 2rem;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h3 style="font-size: 2rem; margin-bottom: 1rem;">Your bag is empty</h3>
                <p style="color: var(--elx-gray); margin-bottom: 2rem;">Browse the shop and add products you love.</p>
                <a href="{{ route('menu.index') }}" class="elx-btn elx-btn--primary">Shop Collections</a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    $('.update-cart').on('change', function (e) {
        var id = $(this).closest('tr').attr('data-id');
        var quantity = $(this).val();
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: 'PATCH',
            data: { _token: '{{ csrf_token() }}', id: id, quantity: quantity },
            success: function () { window.location.reload(); }
        });
    });

    $('.remove-from-cart').on('click', function (e) {
        if (confirm('Remove this item from your bag?')) {
            var id = $(this).attr('data-id');
            $.ajax({
                url: '{{ route('cart.remove') }}',
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}', id: id },
                success: function () { window.location.reload(); }
            });
        }
    });
});
</script>
@endsection
