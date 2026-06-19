<?php

namespace App\Support;

class CartService
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function get(): array
    {
        $cart = session()->get('cart', []);

        if (! is_array($cart)) {
            $cart = [];
        }

        $user = auth()->user();

        if (! $user) {
            return $cart;
        }

        $savedCart = is_array($user->cart_data) ? $user->cart_data : [];

        if ($cart === [] && $savedCart !== []) {
            session()->put('cart', $savedCart);

            return $savedCart;
        }

        if ($cart !== $savedCart) {
            $user->forceFill(['cart_data' => $cart])->save();
        }

        return $cart;
    }

    /**
     * @param  array<string, array<string, mixed>>  $cart
     */
    public function put(array $cart): void
    {
        session()->put('cart', $cart);

        if ($user = auth()->user()) {
            $user->forceFill(['cart_data' => $cart])->save();
        }
    }

    public function forget(): void
    {
        session()->forget('cart');

        if ($user = auth()->user()) {
            $user->forceFill(['cart_data' => null])->save();
        }
    }
}
