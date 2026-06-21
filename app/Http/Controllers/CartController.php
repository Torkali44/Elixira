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
use App\Support\CartService;
use App\Support\ItemPricingService;
use App\Support\PackagePricingService;
use App\Support\RewardPointsService;
use App\Support\UserNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index()
    {
        $cart = $this->cartService->get();

        return view('cart.index', compact('cart'));
    }

    public function add(AddToCartRequest $request)
    {
        $item = Item::with('countryPrices')->findOrFail($request->item_id);
        $pricing = app(ItemPricingService::class);
        $countryCode = $pricing->resolveCountryCodeForItem($item, $request->input('country_code'));

        if ($countryCode === null) {
            $message = __('shop.product_missing_country_pricing');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message]);
            }

            return redirect()->back()->with('error', $message);
        }

        $resolvedPrice = $pricing->resolvePrice($item, $request->user(), $countryCode);
        session(['shopping_country' => $countryCode]);

        $cart = $this->cartService->get();
        $quantity = $request->quantity ?? 1;
        $privateAllowance = $this->availablePrivateQuantity($request->user(), $item->id);
        $maxAllowed = $item->stock + $privateAllowance;

        if ($maxAllowed <= 0) {
            $message = __('cart_page.out_of_stock_account');
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message]);
            }

            return redirect()->back()->with('error', $message);
        }

        $existingQty = isset($cart[$item->id]) ? $cart[$item->id]['quantity'] : 0;
        $totalQty = $existingQty + $quantity;

        if ($totalQty > $maxAllowed) {
            $remaining = $maxAllowed - $existingQty;

            if ($remaining <= 0) {
                $message = __('cart_page.max_qty_in_cart');
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message]);
                }

                return redirect()->back()->with('error', __('cart_page.max_qty_allowed'));
            }

            $message = __('cart_page.only_n_more_available', [
                'remaining' => $remaining,
                'existing' => $existingQty,
            ]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message]);
            }

            return redirect()->back()->with('error', $message);
        }

        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] += $quantity;
        } else {
            $cart[$item->id] = [
                'type' => 'item',
                'name' => $item->local_name,
                'quantity' => $quantity,
                'price' => $resolvedPrice,
                'country_code' => $countryCode,
                'points' => $item->reward_points ?? 0,
                'image' => $item->image,
            ];
        }

        $this->cartService->put($cart);

        if ($request->boolean('buy_now')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('cart_page.added'),
                    'cartCount' => count($cart),
                    'redirect' => route('cart.index'),
                ]);
            }

            return redirect()->route('cart.index')->with('success', __('cart_page.added'));
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => __('cart_page.added'), 'cartCount' => count($cart)]);
        }

        return redirect()->back()->with('success', __('cart_page.added'));
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
        $itemPricing = app(ItemPricingService::class);
        $countryCode = $itemPricing->resolveCountryCodeForPackage($package, $request->input('country_code'));

        if ($countryCode === null) {
            $message = __('shop.package_missing_country_pricing');
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message]);
            }

            return redirect()->back()->with('error', $message);
        }

        $resolvedPrice = $pricing->resolvePrice($package, $request->user(), $countryCode);
        session(['shopping_country' => $countryCode]);

        $cart = $this->cartService->get();
        $cartKey = 'p_'.$package->id;
        $quantity = (int) ($request->quantity ?? 1);
        $maxAllowed = max(0, (int) $package->stock);

        if ($maxAllowed <= 0) {
            $message = __('shop.package_out_of_stock');
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message]);
            }

            return redirect()->back()->with('error', $message);
        }

        $existingQty = $cart[$cartKey]['quantity'] ?? 0;
        if ($existingQty + $quantity > $maxAllowed) {
            $message = __('shop.maximum_units', ['count' => $maxAllowed]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message]);
            }

            return redirect()->back()->with('error', $message);
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

        $this->cartService->put($cart);

        if ($request->boolean('buy_now')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('cart_page.added'),
                    'cartCount' => count($cart),
                    'redirect' => route('cart.index'),
                ]);
            }

            return redirect()->route('cart.index')->with('success', __('cart_page.added'));
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => __('cart_page.added'), 'cartCount' => count($cart)]);
        }

        return redirect()->back()->with('success', __('cart_page.added'));
    }

    public function update(UpdateCartRequest $request)
    {
        $cart = $this->cartService->get();

        if (! isset($cart[$request->id])) {
            return response()->json(['success' => false, 'message' => __('cart_page.item_not_in_cart')], 404);
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
                'message' => __('cart_page.only_n_units_available', ['count' => $maxAllowed]),
            ], 422);
        }

        $cart[$request->id]['quantity'] = $request->quantity;
        $this->cartService->put($cart);

        return response()->json(['success' => true]);
    }

    public function remove(RemoveFromCartRequest $request)
    {
        $id = $request->id;
        $cart = $this->cartService->get();

        if (! isset($cart[$id])) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('cart_page.item_not_in_cart')], 404);
            }

            return redirect()->back()->with('error', __('cart_page.item_not_in_cart'));
        }

        unset($cart[$id]);
        $this->cartService->put($cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', __('cart_page.item_removed'));
    }

    public function checkout(CheckoutRequest $request)
    {
        $authenticatedUser = $request->user();

        if ($authenticatedUser && ! $authenticatedUser->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $cart = $this->cartService->get();

        if (empty($cart)) {
            return redirect()->back()->with('error', __('cart_page.cart_empty'));
        }

        foreach ($cart as $id => $details) {
            if ($this->isCartPackageEntry($id, $details)) {
                $packageId = $this->resolvePackageIdFromCart($id, $details);
                $pkg = $packageId ? Package::find($packageId) : null;
                if (! $pkg || $pkg->stock < $details['quantity']) {
                    $pkgName = $pkg ? $pkg->local_name : ($details['name'] ?? 'Package');
                    $available = $pkg ? (int) $pkg->stock : 0;

                    return redirect()->back()->with('error', __('cart_page.package_stock_error', [
                        'name' => $pkgName,
                        'available' => $available,
                    ]));
                }

                continue;
            }

            $item = Item::find($id);
            $maxAllowed = $item ? $item->stock + $this->availablePrivateQuantity($request->user(), (int) $id) : 0;

            if (! $item || $details['quantity'] > $maxAllowed) {
                $name = $item ? $item->name : __('cart_page.unknown_product');
                $available = $item ? $maxAllowed : 0;

                return redirect()->back()->with('error', __('cart_page.product_stock_error', [
                    'name' => $name,
                    'available' => $available,
                ]));
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
                    'user_code' => __('cart_page.user_code_mismatch'),
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
                        DB::rollBack();

                        return redirect()
                            ->back()
                            ->withInput()
                            ->withErrors([
                                'user_code' => __('cart_page.user_code_taken'),
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
            $this->cartService->forget();

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

            return redirect()->back()->withInput()->with('error', __('orders_page.checkout_failed'));
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
