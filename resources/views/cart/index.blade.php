@extends('layouts.framer')

@section('title', 'Your cart - Elixira')

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

    #selected_address {
        background-color: var(--elx-dark) !important;
        color: var(--elx-white);
    }

    #selected_address option {
        background-color: var(--elx-dark);
        color: var(--elx-white);
    }
    
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
                                    <th>Points</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; $totalPoints = 0; @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php 
                                        $total += $details['price'] * $details['quantity'];
                                        $points = isset($details['points']) ? $details['points'] : 0;
                                        $totalPoints += $points * $details['quantity'];
                                    @endphp
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
                                        <td>﷼ {{ number_format($details['price'], 2) }}</td>
                                        <td style="color: var(--elx-cyan);">{{ $points }}</td>
                                        <td>
                                            <input type="number" value="{{ $details['quantity'] }}" class="qty-input update-cart" min="1" max="50">
                                        </td>
                                        <td style="color: var(--elx-cyan); font-weight: 700;">﷼ {{ number_format($details['price'] * $details['quantity'], 2) }}</td>
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
                            <span style="color: var(--elx-white); font-weight: 700; font-size: 1.2rem;">﷼ {{ number_format($total, 2) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--elx-gray);">
                            <span>Total Points</span>
                            <span style="color: var(--elx-cyan); font-weight: 700; font-size: 1.2rem;">{{ $totalPoints }}</span>
                        </div>
                        
                        <hr style="border: none; border-top: 1px solid var(--elx-border); margin: 2rem 0;">

                        <form action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <input type="text" name="customer_name" class="form-input" placeholder="Full Name *" value="{{ auth()->check() ? auth()->user()->name : old('customer_name') }}" required>
                            @error('customer_name')<div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">{{ $message }}</div>@enderror
                            
                            @php
                                $phone = auth()->check() ? auth()->user()->phone : old('phone_number');
                                $cCode = old('country_code', '+966');
                                $pNum = $phone;
                                if($phone && str_starts_with($phone, '+971')) {
                                    $cCode = '+971';
                                    $pNum = substr($phone, 4);
                                } elseif($phone && str_starts_with($phone, '+966')) {
                                    $cCode = '+966';
                                    $pNum = substr($phone, 4);
                                }
                            @endphp
                            <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; align-items: stretch;">
                                <div style="flex: 0 0 auto; min-width: 8.75rem; max-width: 11rem;">
                                    <x-country-code-picker name="country_code" :value="$cCode" variant="cart" />
                                </div>
                                <input type="tel" name="phone_number" class="form-input" placeholder="Phone Number *" value="{{ $pNum }}" style="flex: 1; margin-bottom: 0;" required>
                            </div>
                            @error('phone_number')<div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">{{ $message }}</div>@enderror
                            @error('country_code')<div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">{{ $message }}</div>@enderror

                            <input type="text" name="user_code" class="form-input" placeholder="User Code (optional)" value="{{ old('user_code', auth()->user()?->user_code ?? '') }}">
                            @error('user_code')<div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">{{ $message }}</div>@enderror
                            
                            @auth
                                @php
                                    $userAddresses = auth()->user()->addresses;
                                    $mainAddress = $userAddresses->where('is_main', true)->first();
                                    $mainAddressText = $mainAddress ? $mainAddress->address : '';
                                    $defaultAddress = old('address', $mainAddressText);
                                @endphp
                                @if($userAddresses->count() > 0)
                                    <select id="selected_address" class="form-input" style="cursor: pointer;" onchange="
                                        let addrInput = document.getElementById('address');
                                        let newAddrControls = document.getElementById('new_address_controls');
                                        if(this.value === 'new') {
                                            addrInput.value = '';
                                            addrInput.readOnly = false;
                                            addrInput.focus();
                                            newAddrControls.style.display = 'block';
                                        } else {
                                            addrInput.value = this.value;
                                            addrInput.readOnly = true;
                                            newAddrControls.style.display = 'none';
                                        }
                                    ">
                                        <option value="" disabled {{ !$mainAddress ? 'selected' : '' }}>Select previous address</option>
                                        @foreach($userAddresses as $addr)
                                            <option value="{{ $addr->address }}" {{ $addr->is_main ? 'selected' : '' }}>
                                                {{ \Illuminate\Support\Str::limit($addr->address, 50) }} {{ $addr->is_main ? '(Main)' : '' }}
                                            </option>
                                        @endforeach
                                        <!-- <option value="new">Add new address</option> -->
                                    </select>
                                @endif
                                
                                <input type="text" id="address" name="address" class="form-input" placeholder="Shipping Address *" value="{{ $defaultAddress }}" {{ $userAddresses->count() > 0 && $mainAddress ? 'readonly' : '' }} required>
                                
                                <div id="new_address_controls" style="margin-bottom: 1rem; color: var(--elx-light); font-size: 0.9rem; {{ $userAddresses->count() > 0 && $mainAddress ? 'display: none;' : '' }}">
                                    <label><input type="checkbox" name="save_address" value="1" checked> Save this address</label>
                                    &nbsp;&nbsp;
                                    <label><input type="checkbox" name="is_main_address" value="1" checked> Set as main</label>
                                </div>
                            @else
                                <input type="text" name="address" class="form-input" placeholder="Shipping Address *" value="{{ old('address') }}" required>
                            @endauth
                            
                            <textarea name="notes" class="form-input" style="border-radius: 15px;" placeholder="Notes (optional)" rows="2">{{ old('notes') }}</textarea>
                            @error('notes')<div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">{{ $message }}</div>@enderror
                            
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
        Swal.fire({
            icon: 'warning',
            title: 'Please confirm',
            text: 'Remove this item from your bag?',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
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
});
</script>
@endsection
