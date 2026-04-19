<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
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

        // Check if item is out of stock
        if ($item->stock <= 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This product is currently out of stock.']);
            }
            return redirect()->back()->with('error', 'This product is currently out of stock. The administration has been notified to restock it soon.');
        }

        // Calculate total quantity (existing in cart + new)
        $existingQty = isset($cart[$item->id]) ? $cart[$item->id]['quantity'] : 0;
        $totalQty = $existingQty + $quantity;

        // Check if total quantity exceeds available stock
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
                'image' => $item->image
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
        $cart = session()->get('cart');
        if (isset($cart[$request->id])) {
            // Validate stock before updating
            $item = Item::find($request->id);
            if ($item && $request->quantity > $item->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only ' . $item->stock . ' units available.'
                ], 422);
            }

            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function remove(Request $request)
    {
        $id = $request->id;

        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            return redirect()->back()->with('success', 'Item removed from your cart.');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false], 404);
        }
        return redirect()->back()->with('error', 'Item not found in cart.');
    }

    public function checkout(CheckoutRequest $request)
    {
        $cart = session()->get('cart');
        
        if (!$cart) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Re-validate stock before checkout
        foreach ($cart as $id => $details) {
            $item = Item::find($id);
            if (!$item || $details['quantity'] > $item->stock) {
                $name = $item ? $item->name : 'Unknown product';
                $available = $item ? $item->stock : 0;
                return redirect()->back()->with('error', "'{$name}' only has {$available} units available. Please update your cart.");
            }
        }

        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'address' => $request->address,
                'total_amount' => $total,
                'status' => 'pending',
                'notes' => $request->notes
            ]);

            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price']
                ]);

                // Decrement stock and increment points for each purchased item
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
                'phone' => $order->customer_phone
            ])->with('success', 'Thank you! Your order #' . $order->id . ' has been placed.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong while placing your order. Please try again.');
        }
    }
}
