<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        return view('cart.index', compact('cart'));
    }

    public function add(AddToCartRequest $request)
    {
        $item = Item::findOrFail($request->item_id);
        $cart = session()->get('cart', []);
        $quantity = $request->quantity ?? 1;

        if ($item->stock <= 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This product is currently out of stock.']);
            }

            return redirect()->back()->with('error', 'This product is currently out of stock. The administration has been notified to restock it soon.');
        }

        $existingQty = isset($cart[$item->id]) ? $cart[$item->id]['quantity'] : 0;
        $totalQty = $existingQty + $quantity;

        if ($totalQty > $item->stock) {
            $remaining = $item->stock - $existingQty;

            if ($remaining <= 0) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'You already have the maximum available quantity of this product in your cart.']);
                }

                return redirect()->back()->with('error', 'You already have the maximum available quantity of this product in your cart.');
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Only ' . $remaining . ' more unit(s) available. You already have ' . $existingQty . ' in your cart.']);
            }

            return redirect()->back()->with('error', 'Only ' . $remaining . ' more unit(s) available. You already have ' . $existingQty . ' in your cart.');
        }

        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] += $quantity;
        } else {
            $cart[$item->id] = [
                'name' => $item->name,
                'quantity' => $quantity,
                'price' => $item->price,
                'points' => $item->points ?? 0,
                'image' => $item->image,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Added to your cart.', 'cartCount' => count($cart)]);
        }

        return redirect()->back()->with('success', 'Added to your cart.');
    }

    public function update(UpdateCartRequest $request)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$request->id])) {
            return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 404);
        }

        $item = Item::find($request->id);

        if ($item && $request->quantity > $item->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Only ' . $item->stock . ' units available.',
            ], 422);
        }

        $cart[$request->id]['quantity'] = $request->quantity;
        session()->put('cart', $cart);

        return response()->json(['success' => true]);
    }

    public function remove(RemoveFromCartRequest $request)
    {
        $id = $request->id;
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 404);
            }

            return redirect()->back()->with('error', 'Item not found in cart.');
        }

        unset($cart[$id]);
        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Item removed from your cart.');
    }

    public function checkout(CheckoutRequest $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        foreach ($cart as $id => $details) {
            $item = Item::find($id);

            if (!$item || $details['quantity'] > $item->stock) {
                $name = $item ? $item->name : 'Unknown product';
                $available = $item ? $item->stock : 0;

                return redirect()->back()->with('error', "'{$name}' only has {$available} units available. Please update your cart.");
            }
        }

        $total = collect($cart)->sum(fn (array $details) => $details['price'] * $details['quantity']);

        $fullPhone = $request->country_code . ltrim($request->phone_number, '0');
        $authenticatedUser = $request->user();
        $resolvedUserCode = $request->filled('user_code')
            ? $request->user_code
            : $authenticatedUser?->user_code;

        if ($authenticatedUser?->user_code && $request->filled('user_code') && $resolvedUserCode !== $authenticatedUser->user_code) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'user_code' => 'Use the member code already saved on your account, or update it from your profile first.',
                ]);
        }

        DB::beginTransaction();

        try {

            if ($authenticatedUser) {
                $updates = [];

                if (!$authenticatedUser->phone) {
                    $updates['phone'] = $fullPhone;
                }

                if (!$authenticatedUser->user_code && $resolvedUserCode) {
                    $updates['user_code'] = $resolvedUserCode;
                }

                if (!empty($updates)) {
                    $authenticatedUser->update($updates);
                }
            }

            $order = Order::create([
                'user_id' => $authenticatedUser?->id,
                'user_code' => $resolvedUserCode,
                'customer_name' => $request->customer_name,
                'customer_phone' => $fullPhone,
                'address' => $request->address,
                'total_amount' => $total,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                $item = Item::find($id);

                if ($item) {
                    $item->decrement('stock', $details['quantity']);
                    $item->increment('points', 1);
                }
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('orders.track', [
                'order_id' => $order->id,
                'phone' => $order->customer_phone,
            ])->with('success', 'Thank you! Your order #' . $order->id . ' has been placed.');
        } catch (\Throwable $exception) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Something went wrong while placing your order. Please try again.');
        }
    }
}
