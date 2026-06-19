<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\SpecialItemOffer;
use App\Models\User;
use App\Models\UserAddress;
use App\Support\ItemPricingService;
use App\Support\PackagePricingService;
use App\Support\RewardPointsService;
use App\Support\UserNotifier;
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
        $item = Item::with('countryPrices')->findOrFail($request->item_id);
        $pricing = app(ItemPricingService::class);
        $countryCode = $pricing->resolveCountryCode($request->input('country_code'));

        if (! $pricing->isAvailableInCountry($item, $countryCode)) {
            $message = __('shop.not_available_in_country');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message]);
            }

            return redirect()->back()->with('error', $message);
        }

        $resolvedPrice = $pricing->resolvePrice($item, $request->user(), $countryCode);
        session(['shopping_country' => $countryCode]);

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
                return response()->json(['success' => false, 'message' => 'Only '.$remaining.' more unit(s) available. You already have '.$existingQty.' in your cart.']);
            }

            return redirect()->back()->with('error', 'Only '.$remaining.' more unit(s) available. You already have '.$existingQty.' in your cart.');
        }

        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] += $quantity;
        } else {
            $cart[$item->id] = [
                'name' => $item->local_name,
                'quantity' => $quantity,
                'price' => $resolvedPrice,
                'country_code' => $countryCode,
                'points' => $item->points ?? 0,
                'image' => $item->image,
            ];
        }

        session()->put('cart', $cart);

        if ($request->boolean('buy_now')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Added to your cart.',
                    'cartCount' => count($cart),
                    'redirect' => route('cart.index'),
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Added to your cart.');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Added to your cart.', 'cartCount' => count($cart)]);
        }

        return redirect()->back()->with('success', 'Added to your cart.');
    }

    public function addPackage(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'quantity' => 'nullable|integer|min:1|max:50',
            'country_code' => 'nullable|in:KSA,UAE',
        ]);

        $package = Package::with('countryPrices')->where('is_active', true)->findOrFail($request->package_id);
        $pricing = app(PackagePricingService::class);
        $countryCode = app(ItemPricingService::class)->resolveCountryCode($request->input('country_code'));
        $resolvedPrice = $pricing->resolvePrice($package, $request->user(), $countryCode);
        session(['shopping_country' => $countryCode]);

        $cart = session()->get('cart', []);
        $cartKey = 'p_'.$package->id;
        $quantity = (int) ($request->quantity ?? 1);
        $maxAllowed = max(0, (int) $package->stock);

        if ($maxAllowed <= 0) {
            return redirect()->back()->with('error', __('shop.package_out_of_stock'));
        }

        $existingQty = $cart[$cartKey]['quantity'] ?? 0;
        if ($existingQty + $quantity > $maxAllowed) {
            return redirect()->back()->with('error', __('shop.maximum_units', ['count' => $maxAllowed]));
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['type'] = 'package';
            $cart[$cartKey]['package_id'] = $package->id;
        } else {
            $cart[$cartKey] = [
                'type' => 'package',
                'package_id' => $package->id,
                'name' => $package->local_name,
                'quantity' => $quantity,
                'price' => $resolvedPrice,
                'country_code' => $countryCode,
                'points' => $package->reward_points ?? 0,
                'image' => $package->image,
            ];
        }

        session()->put('cart', $cart);

        if ($request->boolean('buy_now')) {
            return redirect()->route('cart.index')->with('success', __('cart_page.added'));
        }

        return redirect()->back()->with('success', __('cart_page.added'));
    }

    public function update(UpdateCartRequest $request)
    {
        $cart = session()->get('cart', []);

        if (! isset($cart[$request->id])) {
            return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 404);
        }

        $entry = $cart[$request->id];
        if ($this->isCartPackageEntry($request->id, $entry)) {
            $packageId = $this->resolvePackageIdFromCart($request->id, $entry);
            $package = $packageId ? Package::find($packageId) : null;
            $maxAllowed = $package ? (int) $package->stock : 0;
        } else {
            $item = Item::find($request->id);
            $maxAllowed = $item ? $item->stock + $this->availablePrivateQuantity($request->user(), (int) $item->id) : 0;
        }

        if ($request->quantity > $maxAllowed) {
            return response()->json([
                'success' => false,
                'message' => 'Only '.$maxAllowed.' units available for your account.',
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

        if (! isset($cart[$id])) {
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
            if ($this->isCartPackageEntry($id, $details)) {
                $packageId = $this->resolvePackageIdFromCart($id, $details);
                $pkg = $packageId ? Package::find($packageId) : null;
                if (! $pkg || $pkg->stock < $details['quantity']) {
                    $pkgName = $pkg ? $pkg->local_name : ($details['name'] ?? 'Package');
                    $available = $pkg ? (int) $pkg->stock : 0;

                    return redirect()->back()->with('error', "'{$pkgName}' only has {$available} units available. Please update your cart.");
                }

                continue;
            }

            $item = Item::find($id);
            $maxAllowed = $item ? $item->stock + $this->availablePrivateQuantity($request->user(), (int) $id) : 0;

            if (! $item || $details['quantity'] > $maxAllowed) {
                $name = $item ? $item->name : 'Unknown product';
                $available = $item ? $maxAllowed : 0;

                return redirect()->back()->with('error', "'{$name}' only has {$available} units available. Please update your cart.");
            }
        }

        $total = collect($cart)->sum(fn (array $details) => $details['price'] * $details['quantity']);

        $fullPhone = $request->country_code.ltrim($request->phone_number, '0');
        session(['shopping_country' => app(ItemPricingService::class)->mapPhoneCountryCode($request->country_code)]);
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

                if (! $authenticatedUser->phone) {
                    $updates['phone'] = $fullPhone;
                }

                if (! $authenticatedUser->user_code && $resolvedUserCode) {
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

                if (! empty($updates)) {
                    $authenticatedUser->update($updates);
                }

                if ($request->filled('address') && $request->has('save_address')) {
                    $addr = UserAddress::firstOrCreate([
                        'user_id' => $authenticatedUser->id,
                        'address' => $request->address,
                    ]);

                    if ($request->has('is_main_address')) {
                        UserAddress::where('user_id', $authenticatedUser->id)->update(['is_main' => false]);
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
                if ($this->isCartPackageEntry($id, $details)) {
                    $packageId = $this->resolvePackageIdFromCart($id, $details);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'package_id' => $packageId,
                        'product_name' => $details['name'],
                        'quantity' => $details['quantity'],
                        'price' => $details['price'],
                    ]);

                    if ($packageId) {
                        Package::query()->where('id', $packageId)->decrement('stock', (int) $details['quantity']);
                    }

                    continue;
                }

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

                    // Increment item popularity counter (old "points" field)
                    $item->increment('points', 1);
                }
            }

            RewardPointsService::awardForOrder($order);

            // Create notifications for customer and vendors
            try {
                if ($authenticatedUser) {
                    UserNotifier::send($authenticatedUser->id, 'order_placed', [
                        'order' => (string) $order->id,
                    ], route('profile.orders.show', $order->id));
                }

                $vendorsToNotify = [];
                foreach ($cart as $cartKey => $details) {
                    if ($this->isCartPackageEntry($cartKey, $details)) {
                        continue;
                    }

                    $item = Item::find($cartKey);
                    if ($item && $item->vendor) {
                        $vendorsToNotify[$item->vendor->id] = $item->vendor;
                    }
                }

                foreach ($vendorsToNotify as $vendorId => $vendor) {
                    UserNotifier::send($vendorId, 'new_order_vendor', [
                        'order' => (string) $order->id,
                    ], route('vendor.orders'));
                }
            } catch (\Throwable $e) {
                // Log and ignore to prevent checkout failure
                \Log::error('Checkout notification failed: '.$e->getMessage());
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('orders.track', [
                'order_id' => $order->id,
                'phone' => $order->customer_phone,
            ])->with('success', __('orders_page.checkout_success', ['order' => $order->id]));
        } catch (\Throwable $exception) {
            DB::rollBack();
            \Log::error('Checkout Error: '.$exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Something went wrong while placing your order: '.$exception->getMessage());
        }
    }

    private function availablePrivateQuantity(?User $user, int $itemId): int
    {
        if (! $user) {
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
        if (! $user || $quantity <= 0) {
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

    /**
     * @param  array<string, mixed>  $details
     */
    private function isCartPackageEntry(string|int $cartKey, array $details): bool
    {
        return ($details['type'] ?? null) === 'package'
            || str_starts_with((string) $cartKey, 'p_')
            || isset($details['package_id']);
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function resolvePackageIdFromCart(string|int $cartKey, array $details): ?int
    {
        if (isset($details['package_id'])) {
            return (int) $details['package_id'];
        }

        if (preg_match('/^p_(\d+)$/', (string) $cartKey, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }
}
