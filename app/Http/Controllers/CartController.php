<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SpecialItemOffer;
use App\Models\User;
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
        $privateAllowance = $this->availablePrivateQuantity($request->user(), $item->id);
        $maxAllowed = $item->stock + $privateAllowance;

        if ($maxAllowed <= 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This product is currently out of stock for your account.']);
            }

            return redirect()->back()->with('error', 'This product is currently out of stock for your account.');
        }

        $existingQty = isset($cart[$item->id]) ? $cart[$item->id]['quantity'] : 0;
        $totalQty = $existingQty + $quantity;

        if ($totalQty > $maxAllowed) {
            $remaining = $maxAllowed - $existingQty;

            if ($remaining <= 0) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'You already have the maximum available quantity of this product in your cart.']);
                }

                return redirect()->back()->with('error', 'You already have the maximum allowed quantity of this product in your cart.');
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

        $maxAllowed = $item ? $item->stock + $this->availablePrivateQuantity($request->user(), $item->id) : 0;

        if ($item && $request->quantity > $maxAllowed) {
            return response()->json([
                'success' => false,
                'message' => 'Only ' . $maxAllowed . ' units available for your account.',
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
            $maxAllowed = $item ? $item->stock + $this->availablePrivateQuantity($request->user(), (int) $id) : 0;

            if (!$item || $details['quantity'] > $maxAllowed) {
                $name = $item ? $item->name : 'Unknown product';
                $available = $item ? $maxAllowed : 0;

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
                    $exists = User::where('user_code', $resolvedUserCode)->where('id', '!=', $authenticatedUser->id)->exists();
                    if ($exists) {
                        return redirect()
                            ->back()
                            ->withInput()
                            ->withErrors([
                                'user_code' => 'This member code is already assigned to another account.',
                            ]);
                    }
                    $updates['user_code'] = $resolvedUserCode;
                }

                if (!empty($updates)) {
                    $authenticatedUser->update($updates);
                }
                
                if ($request->filled('address') && $request->has('save_address')) {
                    $addr = \App\Models\UserAddress::firstOrCreate([
                        'user_id' => $authenticatedUser->id,
                        'address' => $request->address,
                    ]);
                    
                    if ($request->has('is_main_address')) {
                        \App\Models\UserAddress::where('user_id', $authenticatedUser->id)->update(['is_main' => false]);
                        $addr->update(['is_main' => true]);
                    }
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
                    $normalStockUsed = min((int) $item->stock, (int) $details['quantity']);
                    $privateUnitsUsed = max(0, (int) $details['quantity'] - $normalStockUsed);

                    if ($normalStockUsed > 0) {
                        $item->decrement('stock', $normalStockUsed);
                    }

                    if ($privateUnitsUsed > 0) {
                        $this->consumePrivateOffers($authenticatedUser, (int) $item->id, $privateUnitsUsed);
                    }

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
            \Log::error('Checkout Error: ' . $exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->with('error', 'Something went wrong while placing your order: ' . $exception->getMessage());
        }
    }

    private function availablePrivateQuantity(?User $user, int $itemId): int
    {
        if (!$user) {
            return 0;
        }

        $phoneDigits = $this->normalizePhone((string) $user->phone);
        $email = strtolower((string) $user->email);

        return (int) SpecialItemOffer::query()
            ->where('item_id', $itemId)
            ->where('is_active', true)
            ->whereColumn('used_quantity', '<', 'quantity')
            ->where(function ($query) use ($user, $phoneDigits, $email) {
                $query->where('user_id', $user->id);

                if ($phoneDigits !== '') {
                    $query->orWhere('target_phone', $phoneDigits);
                }

                if ($email !== '') {
                    $query->orWhereRaw('LOWER(target_email) = ?', [$email]);
                }
            })
            ->get()
            ->sum(fn (SpecialItemOffer $offer) => $offer->remainingQuantity());
    }

    private function consumePrivateOffers(?User $user, int $itemId, int $quantity): void
    {
        if (!$user || $quantity <= 0) {
            return;
        }

        $phoneDigits = $this->normalizePhone((string) $user->phone);
        $email = strtolower((string) $user->email);

        $offers = SpecialItemOffer::query()
            ->where('item_id', $itemId)
            ->where('is_active', true)
            ->whereColumn('used_quantity', '<', 'quantity')
            ->where(function ($query) use ($user, $phoneDigits, $email) {
                $query->where('user_id', $user->id);

                if ($phoneDigits !== '') {
                    $query->orWhere('target_phone', $phoneDigits);
                }

                if ($email !== '') {
                    $query->orWhereRaw('LOWER(target_email) = ?', [$email]);
                }
            })
            ->orderBy('created_at')
            ->lockForUpdate()
            ->get();

        $remaining = $quantity;

        foreach ($offers as $offer) {
            if ($remaining <= 0) {
                break;
            }

            $canUse = min($remaining, $offer->remainingQuantity());

            if ($canUse <= 0) {
                continue;
            }

            $offer->increment('used_quantity', $canUse);
            $remaining -= $canUse;

            $offer->refresh();

            if ($offer->used_quantity >= $offer->quantity) {
                $offer->update(['is_active' => false]);
            }
        }
    }

    private function normalizePhone(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }
}
