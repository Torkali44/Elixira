<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Requests\UpdateCartVariantRequest;
use App\Models\DeliveryCity;
use App\Models\DeliveryCountry;
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
        $sponsorCodes = \App\Models\DxnSponsorCode::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('code')
            ->get();
        $pricing = app(ItemPricingService::class);
        $packagePricing = app(PackagePricingService::class);

        $cart = $this->syncCartEntries($cart, $pricing, $packagePricing, auth()->user());
        $this->cartService->put($cart);

        $cartLines = $this->buildCartLineItems($cart, $pricing, $packagePricing);

        $total = collect($cartLines)->sum(fn (array $line) => $line['subtotal']);
        $totalPoints = collect($cartLines)->sum(fn (array $line) => $line['points'] * $line['quantity']);
        $hasUnavailableItems = collect($cartLines)->contains(fn (array $line) => $line['is_unavailable']);

        $cartCountry = session('shopping_country') ?? $pricing->detectUserCountry();
        $cartCurrency = $pricing->currencySymbol($cartCountry);
        $cartPhoneCountry = match ($cartCountry) {
            'UAE' => '+971',
            'KSA' => '+966',
            default => $this->resolveDefaultPhoneCountry(),
        };
        $deliveryCityOptions = $this->deliveryCityOptionsForPhoneCountry($cartPhoneCountry);
        $deliveryFeesById = collect($deliveryCityOptions)->mapWithKeys(
            fn (array $option) => [(string) $option['id'] => (float) $option['delivery_fee']]
        )->all();

        return view('cart.index', compact(
            'cart',
            'cartLines',
            'sponsorCodes',
            'total',
            'totalPoints',
            'hasUnavailableItems',
            'cartCountry',
            'cartCurrency',
            'cartPhoneCountry',
            'deliveryCityOptions',
            'deliveryFeesById',
        ));
    }

    public function add(AddToCartRequest $request)
    {
        $item = Item::with('countryPrices')->findOrFail($request->item_id);
        $pricing = app(ItemPricingService::class);
        $countryCode = $pricing->resolveCountryCodeForItem($item, $request->input('country_code'));
        $countryPriceId = $request->integer('country_price_id') ?: null;

        if ($countryCode === null) {
            $message = __('shop.product_missing_country_pricing');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message]);
            }

            return redirect()->back()->with('error', $message);
        }

        if (! $countryPriceId) {
            $countryPriceId = $pricing->resolveDefaultVariant($item, $countryCode)?->id;
        }

        if ($countryPriceId) {
            $variant = $pricing->findVariant($item, $countryPriceId);
            if (! $variant || $variant->country_code !== $countryCode) {
                return redirect()->back()->with('error', __('shop.invalid_size_selection'));
            }
        }

        $resolvedPrice = $pricing->resolvePrice($item, $request->user(), $countryCode, $countryPriceId);
        session(['shopping_country' => $countryCode]);

        $cart = $this->cartService->get();
        $quantity = $request->quantity ?? 1;
        $privateAllowance = $this->availablePrivateQuantity($request->user(), $item->id);
        $itemStock = $pricing->resolveStock($item, $countryCode, $countryPriceId);
        $maxAllowed = $itemStock + $privateAllowance;
        $cartKey = $this->buildItemCartKey($item->id, $countryPriceId);

        if ($maxAllowed <= 0) {
            $message = __('cart_page.out_of_stock_account');
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message]);
            }

            return redirect()->back()->with('error', $message);
        }

        $existingQty = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
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

        $variant = $countryPriceId ? $pricing->findVariant($item, $countryPriceId) : null;
        $displayName = $item->local_name;
        if ($variant?->local_size) {
            $displayName .= ' — '.$variant->local_size;
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'type' => 'item',
                'item_id' => $item->id,
                'name' => $displayName,
                'quantity' => $quantity,
                'price' => $resolvedPrice,
                'country_code' => $countryCode,
                'country_price_id' => $countryPriceId,
                'points' => $pricing->resolveRewardPoints($item, $countryCode, $countryPriceId),
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
            'country_price_id' => 'nullable|integer|exists:package_country_prices,id',
        ]);

        $package = Package::with('countryPrices')->where('is_active', true)->findOrFail($request->package_id);
        $pricing = app(PackagePricingService::class);
        $itemPricing = app(ItemPricingService::class);
        $countryCode = $itemPricing->resolveCountryCodeForPackage($package, $request->input('country_code'));
        $countryPriceId = $request->integer('country_price_id') ?: null;

        if ($countryCode === null) {
            $message = __('shop.package_missing_country_pricing');
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message]);
            }

            return redirect()->back()->with('error', $message);
        }

        if (! $countryPriceId) {
            $countryPriceId = $pricing->resolveDefaultVariant($package, $countryCode)?->id;
        }

        if ($countryPriceId) {
            $variant = $pricing->findVariant($package, $countryPriceId);
            if (! $variant || $variant->country_code !== $countryCode) {
                $message = __('shop.invalid_size_selection');
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message]);
                }

                return redirect()->back()->with('error', $message);
            }
        }

        $resolvedPrice = $pricing->resolvePrice($package, $request->user(), $countryCode, $countryPriceId);
        session(['shopping_country' => $countryCode]);

        $cart = $this->cartService->get();
        $cartKey = $this->buildPackageCartKey($package->id, $countryPriceId);
        $quantity = (int) ($request->quantity ?? 1);
        $pkgStock = $pricing->resolveStock($package, $countryCode, $countryPriceId);
        $maxAllowed = max(0, (int) $pkgStock);

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

        $variant = $countryPriceId ? $pricing->findVariant($package, $countryPriceId) : null;
        $displayName = $package->local_name;
        if ($variant?->local_size) {
            $displayName .= ' — '.$variant->local_size;
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['type'] = 'package';
            $cart[$cartKey]['package_id'] = $package->id;
            $cart[$cartKey]['country_price_id'] = $countryPriceId;
            $cart[$cartKey]['name'] = $displayName;
            $cart[$cartKey]['price'] = $resolvedPrice;
        } else {
            $cart[$cartKey] = [
                'type' => 'package',
                'package_id' => $package->id,
                'name' => $displayName,
                'quantity' => $quantity,
                'price' => $resolvedPrice,
                'country_code' => $countryCode,
                'country_price_id' => $countryPriceId,
                'points' => $itemPricing->resolvePackageRewardPoints($package, $countryCode, $countryPriceId),
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
            return $this->cartMutationResponse($request, __('cart_page.item_not_in_cart'), 404);
        }

        $entry = $cart[$request->id];
        $entryCountry = $entry['country_code'] ?? null;
        $countryPriceId = isset($entry['country_price_id']) ? (int) $entry['country_price_id'] : null;
        if ($this->isCartPackageEntry($request->id, $entry)) {
            $packageId = $this->resolvePackageIdFromCart($request->id, $entry);
            $package = $packageId ? Package::find($packageId) : null;
            $maxAllowed = $package ? app(PackagePricingService::class)->resolveStock($package, $entryCountry, $countryPriceId) : 0;
        } else {
            $itemId = $this->resolveItemIdFromCart($request->id, $entry);
            $item = $itemId ? Item::find($itemId) : null;
            $maxAllowed = $item ? app(ItemPricingService::class)->resolveStock($item, $entryCountry, $countryPriceId) + $this->availablePrivateQuantity($request->user(), (int) $itemId) : 0;
        }

        if ($maxAllowed <= 0) {
            return $this->cartMutationResponse($request, __('shop.out_of_stock'));
        }

        if ($request->quantity > $maxAllowed) {
            return $this->cartMutationResponse($request, __('cart_page.only_n_units_available', ['count' => max(0, $maxAllowed)]));
        }

        $cart[$request->id]['quantity'] = $request->quantity;
        $this->cartService->put($cart);

        return $this->cartMutationResponse($request);
    }

    public function updateVariant(UpdateCartVariantRequest $request)
    {
        $cart = $this->cartService->get();
        $cartKey = $request->id;

        if (! isset($cart[$cartKey])) {
            return $this->cartMutationResponse($request, __('cart_page.item_not_in_cart'), 404);
        }

        $entry = $cart[$cartKey];

        if ($this->isCartPackageEntry($cartKey, $entry)) {
            return $this->cartMutationResponse($request, __('shop.invalid_size_selection'));
        }

        $itemId = $this->resolveItemIdFromCart($cartKey, $entry);
        $item = $itemId ? Item::with('countryPrices')->find($itemId) : null;

        if (! $item) {
            return $this->cartMutationResponse($request, __('cart_page.item_not_in_cart'), 404);
        }

        $pricing = app(ItemPricingService::class);
        $countryCode = $entry['country_code'] ?? $pricing->resolveCountryCodeForItem($item, null);
        $countryPriceId = $request->integer('country_price_id');
        $variant = $pricing->findVariant($item, $countryPriceId);

        if (! $variant || $variant->country_code !== $countryCode) {
            return $this->cartMutationResponse($request, __('shop.invalid_size_selection'));
        }

        $quantity = (int) ($entry['quantity'] ?? 1);
        $maxAllowed = $pricing->resolveStock($item, $countryCode, $countryPriceId)
            + $this->availablePrivateQuantity($request->user(), (int) $itemId);

        if ($maxAllowed <= 0 || $quantity > $maxAllowed) {
            return $this->cartMutationResponse($request, __('cart_page.only_n_units_available', ['count' => max(0, $maxAllowed)]));
        }

        $newKey = $this->buildItemCartKey((int) $itemId, $countryPriceId);

        if ((string) $cartKey === (string) $newKey) {
            return $this->cartMutationResponse($request);
        }

        $existingQtyAtTarget = isset($cart[$newKey]) ? (int) $cart[$newKey]['quantity'] : 0;
        $combinedQty = $existingQtyAtTarget + $quantity;

        if ($combinedQty > $maxAllowed) {
            return $this->cartMutationResponse($request, __('cart_page.only_n_units_available', ['count' => max(0, $maxAllowed)]));
        }

        $updatedEntry = [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => $quantity,
            'price' => $pricing->resolvePrice($item, $request->user(), $countryCode, $countryPriceId),
            'country_code' => $countryCode,
            'country_price_id' => $countryPriceId,
            'points' => $pricing->resolveRewardPoints($item, $countryCode, $countryPriceId),
            'image' => $item->image,
        ];

        unset($cart[$cartKey]);

        if (isset($cart[$newKey])) {
            $cart[$newKey]['quantity'] += $quantity;
            $cart[$newKey]['price'] = $updatedEntry['price'];
            $cart[$newKey]['points'] = $updatedEntry['points'];
            $cart[$newKey]['name'] = $updatedEntry['name'];
        } else {
            $cart[$newKey] = $updatedEntry;
        }

        $this->cartService->put($cart);

        return $this->cartMutationResponse($request);
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
                $pkgStock = $pkg ? app(PackagePricingService::class)->resolveStock($pkg, $details['country_code'] ?? null) : 0;
                if (! $pkg || $pkgStock < $details['quantity']) {
                    $pkgName = $pkg ? $pkg->local_name : ($details['name'] ?? 'Package');
                    $available = $pkgStock;

                    return redirect()->back()->with('error', __('cart_page.package_stock_error', [
                        'name' => $pkgName,
                        'available' => $available,
                    ]));
                }

                continue;
            }

            $itemId = $this->resolveItemIdFromCart($id, $details);
            $item = $itemId ? Item::find($itemId) : null;
            $countryPriceId = isset($details['country_price_id']) ? (int) $details['country_price_id'] : null;
            $itemStock = $item ? app(ItemPricingService::class)->resolveStock($item, $details['country_code'] ?? null, $countryPriceId) : 0;
            $maxAllowed = $item ? $itemStock + $this->availablePrivateQuantity($request->user(), (int) $itemId) : 0;

            if (! $item || $details['quantity'] > $maxAllowed) {
                $name = $item ? ($details['name'] ?? $item->name) : __('cart_page.unknown_product');
                $available = $item ? $maxAllowed : 0;

                return redirect()->back()->with('error', __('cart_page.product_stock_error', [
                    'name' => $name,
                    'available' => $available,
                ]));
            }
        }

        $subtotal = collect($cart)->sum(fn (array $details) => $details['price'] * $details['quantity']);

        $fullPhone = $request->country_code.ltrim($request->phone_number, '0');
        $cartSessionCountry = session('shopping_country');
        $phoneCountry = app(ItemPricingService::class)->mapPhoneCountryCode($request->country_code);
        $shoppingCountry = (is_string($cartSessionCountry) && in_array($cartSessionCountry, ['KSA', 'UAE'], true))
            ? $cartSessionCountry
            : $phoneCountry;
        session(['shopping_country' => $shoppingCountry]);

        $deliveryCity = null;
        $deliveryFee = 0.0;
        $sharedShippingOrder = null;
        $sharedShippingOrderId = $request->filled('shared_shipping_order_id')
            ? $request->integer('shared_shipping_order_id')
            : null;

        if ($sharedShippingOrderId) {
            $lookup = $this->resolveSharedShippingOrder($sharedShippingOrderId, $shoppingCountry);

            if ($lookup['status'] === 'not_found') {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['shared_shipping_order_id' => __('cart_page.shared_shipping_not_found')]);
            }

            if ($lookup['status'] === 'already_shipped') {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['shared_shipping_order_id' => __('cart_page.shared_shipping_already_shipped')]);
            }

            if ($lookup['status'] === 'country_mismatch') {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['shared_shipping_order_id' => __('cart_page.shared_shipping_country_mismatch')]);
            }

            $sharedShippingOrder = $lookup['order'];
            $deliveryCity = $sharedShippingOrder->deliveryCity;
            $deliveryFee = 0.0;
        } elseif ($shoppingCountry) {
            $deliveryCountry = DeliveryCountry::query()
                ->where('code', $shoppingCountry)
                ->where('is_active', true)
                ->first();

            if ($deliveryCountry && $deliveryCountry->activeCities()->exists()) {
                if (! $request->filled('delivery_city_id')) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors(['delivery_city_id' => __('cart_page.delivery_city_required')]);
                }

                $deliveryCity = DeliveryCity::query()
                    ->where('id', $request->integer('delivery_city_id'))
                    ->where('delivery_country_id', $deliveryCountry->id)
                    ->where('is_active', true)
                    ->first();

                if (! $deliveryCity) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors(['delivery_city_id' => __('cart_page.invalid_delivery_city')]);
                }

                $deliveryFee = (float) $deliveryCity->delivery_fee;
            }
        }

        $total = $subtotal + $deliveryFee;
        $orderAddress = $sharedShippingOrder
            ? $sharedShippingOrder->address
            : $request->address;

        if (! $sharedShippingOrder && $deliveryCity) {
            $orderAddress = $deliveryCity->local_name.' — '.$orderAddress;
        }

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
                    $isSponsorCode = \App\Models\DxnSponsorCode::where('code', $resolvedUserCode)->exists();
                    if (! $isSponsorCode) {
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
                }

                if (! empty($updates)) {
                    $authenticatedUser->update($updates);
                }

                if (! $sharedShippingOrder && $request->filled('address') && $request->has('save_address')) {
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

            $orderCountryCode = $deliveryCity?->country?->code ?? $shoppingCountry;

            $order = Order::create([
                'user_id' => $authenticatedUser?->id,
                'user_code' => $resolvedUserCode,
                'customer_name' => $request->customer_name,
                'customer_phone' => $fullPhone,
                'address' => $orderAddress,
                'delivery_city_id' => $deliveryCity?->id ?? $sharedShippingOrder?->delivery_city_id,
                'country_code' => $orderCountryCode,
                'delivery_fee' => $deliveryFee,
                'shared_shipping_order_id' => $sharedShippingOrder?->id,
                'subtotal_amount' => $subtotal,
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
                        $pkg = Package::find($packageId);
                        if ($pkg) {
                            $countryCode = $details['country_code'] ?? null;
                            $pkgCountryPrice = $pkg->countryPrices()->where('country_code', $countryCode)->first();
                            if ($pkgCountryPrice && $pkgCountryPrice->stock !== null) {
                                $pkgCountryPrice->decrement('stock', (int) $details['quantity']);
                            }
                            $pkg->decrement('stock', (int) $details['quantity']);
                        }
                    }

                    continue;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $this->resolveItemIdFromCart($id, $details),
                    'product_name' => $details['name'] ?? null,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                $itemId = $this->resolveItemIdFromCart($id, $details);
                $item = $itemId ? Item::find($itemId) : null;

                if ($item) {
                    $countryCode = $details['country_code'] ?? null;
                    $countryPriceId = isset($details['country_price_id']) ? (int) $details['country_price_id'] : null;
                    $itemCountryPrice = $countryPriceId
                        ? $item->countryPrices()->find($countryPriceId)
                        : $item->countryPrices()->where('country_code', $countryCode)->first();
                    $resolvedStock = ($itemCountryPrice && $itemCountryPrice->stock !== null)
                        ? (int) $itemCountryPrice->stock
                        : (int) $item->stock;

                    $normalStockUsed = min($resolvedStock, (int) $details['quantity']);
                    $privateUnitsUsed = max(0, (int) $details['quantity'] - $normalStockUsed);

                    if ($normalStockUsed > 0) {
                        if ($itemCountryPrice && $itemCountryPrice->stock !== null) {
                            $itemCountryPrice->decrement('stock', $normalStockUsed);
                        }
                        $item->decrement('stock', $normalStockUsed);
                    }

                    if ($privateUnitsUsed > 0) {
                        $this->consumePrivateOffers($authenticatedUser, (int) $itemId, $privateUnitsUsed);
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

                    $itemId = $this->resolveItemIdFromCart($cartKey, $details);
                    $item = $itemId ? Item::find($itemId) : null;
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
            ])->with('success', __('orders_page.checkout_success', ['order' => $order->reference]));
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

    public function lookupSharedShippingOrder(Request $request)
    {
        $request->validate([
            'reference' => ['required', 'string', 'size:6', 'regex:/^[A-Za-z0-9]+$/'],
            'phone_country' => ['required', 'string', 'in:+966,+971'],
        ], [
            'reference.required' => __('cart_page.shared_shipping_reference_required'),
            'reference.size' => __('cart_page.shared_shipping_reference_size'),
            'reference.regex' => __('cart_page.shared_shipping_reference_format'),
            'phone_country.required' => __('cart_page.shared_shipping_country_required'),
            'phone_country.in' => __('cart_page.shared_shipping_country_required'),
        ]);

        $shoppingCountry = app(ItemPricingService::class)->mapPhoneCountryCode($request->string('phone_country')->toString());
        $lookup = $this->resolveSharedShippingOrderByReference($request->string('reference')->toString(), $shoppingCountry);

        if ($lookup['status'] === 'not_found') {
            return response()->json([
                'ok' => false,
                'status' => 'not_found',
                'message' => __('cart_page.shared_shipping_not_found'),
            ], 404);
        }

        if ($lookup['status'] === 'already_shipped') {
            return response()->json([
                'ok' => false,
                'status' => 'already_shipped',
                'message' => __('cart_page.shared_shipping_already_shipped'),
            ], 422);
        }

        if ($lookup['status'] === 'country_mismatch') {
            return response()->json([
                'ok' => false,
                'status' => 'country_mismatch',
                'message' => __('cart_page.shared_shipping_country_mismatch'),
            ], 422);
        }

        /** @var Order $order */
        $order = $lookup['order'];

        return response()->json([
            'ok' => true,
            'status' => 'pending',
            'order_id' => $order->id,
            'order_reference' => $order->reference,
            'address' => $order->address,
            'delivery_city_id' => $order->delivery_city_id,
            'delivery_fee_waived' => true,
            'message' => __('cart_page.shared_shipping_applied'),
        ]);
    }

    /**
     * @return array{status: string, order: ?Order}
     */
    private function resolveSharedShippingOrderByReference(string $reference, ?string $shoppingCountryCode = null): array
    {
        $reference = strtoupper(trim($reference));
        $order = Order::query()->with('deliveryCity.country')->where('reference', $reference)->first();

        if (! $order) {
            return ['status' => 'not_found', 'order' => null];
        }

        if (! $order->canShareShipping()) {
            return ['status' => 'already_shipped', 'order' => $order];
        }

        if ($shoppingCountryCode !== null && ! $this->sharedShippingCountriesMatch($order, $shoppingCountryCode)) {
            return ['status' => 'country_mismatch', 'order' => $order];
        }

        return ['status' => 'pending', 'order' => $order];
    }

    /**
     * @return array{status: string, order: ?Order}
     */
    private function resolveSharedShippingOrder(int $orderId, ?string $shoppingCountryCode = null): array
    {
        $order = Order::query()->with('deliveryCity.country')->find($orderId);

        if (! $order) {
            return ['status' => 'not_found', 'order' => null];
        }

        if (! $order->canShareShipping()) {
            return ['status' => 'already_shipped', 'order' => $order];
        }

        if ($shoppingCountryCode !== null && ! $this->sharedShippingCountriesMatch($order, $shoppingCountryCode)) {
            return ['status' => 'country_mismatch', 'order' => $order];
        }

        return ['status' => 'pending', 'order' => $order];
    }

    private function sharedShippingCountriesMatch(Order $parentOrder, string $shoppingCountryCode): bool
    {
        $parentCountry = $parentOrder->deliveryCountryCode();

        return $parentCountry !== null && $parentCountry === $shoppingCountryCode;
    }

    /**
     * @param  array<string, array<string, mixed>>  $cart
     * @return array<string, array<string, mixed>>
     */
    private function syncCartEntries(array $cart, ItemPricingService $pricing, PackagePricingService $packagePricing, ?User $user): array
    {
        foreach ($cart as $key => &$details) {
            $countryCode = $details['country_code']
                ?? session('shopping_country')
                ?? $pricing->detectUserCountry();

            if ($this->isCartPackageEntry($key, $details)) {
                $packageId = $this->resolvePackageIdFromCart($key, $details);
                $package = $packageId ? Package::with('countryPrices')->find($packageId) : null;

                if (! $package) {
                    continue;
                }

                $details['type'] = 'package';
                $details['package_id'] = $package->id;
                $details['name'] = $package->local_name;
                $details['price'] = $packagePricing->resolvePrice($package, $user, $countryCode);
                $details['points'] = $pricing->resolvePackageRewardPoints($package, $countryCode);
                $details['country_code'] = $countryCode;
                $details['image'] = $details['image'] ?? $package->image;

                continue;
            }

            $itemId = $this->resolveItemIdFromCart($key, $details);
            $item = $itemId ? Item::with('countryPrices')->find($itemId) : null;

            if (! $item) {
                continue;
            }

            $countryPriceId = isset($details['country_price_id']) ? (int) $details['country_price_id'] : null;

            if (! $countryPriceId) {
                $countryPriceId = $pricing->resolveDefaultVariant($item, $countryCode)?->id;
            }

            $details['type'] = 'item';
            $details['item_id'] = $item->id;
                $details['name'] = $this->cleanCartDisplayName($item->local_name);
            $details['price'] = $pricing->resolvePrice($item, $user, $countryCode, $countryPriceId);
            $details['points'] = $pricing->resolveRewardPoints($item, $countryCode, $countryPriceId);
            $details['country_code'] = $countryCode;
            $details['country_price_id'] = $countryPriceId;
            $details['image'] = $details['image'] ?? $item->image;
        }

        unset($details);

        return $cart;
    }

    /**
     * @param  array<string, array<string, mixed>>  $cart
     * @return list<array<string, mixed>>
     */
    private function buildCartLineItems(array $cart, ItemPricingService $pricing, PackagePricingService $packagePricing): array
    {
        $itemIds = collect($cart)->map(function ($details, $key) {
            if ($this->isCartPackageEntry($key, $details)) {
                return null;
            }

            return $this->resolveItemIdFromCart($key, $details);
        })->filter()->unique()->values()->all();

        $packageIds = collect($cart)->map(function ($details, $key) {
            if (! $this->isCartPackageEntry($key, $details)) {
                return null;
            }

            return $this->resolvePackageIdFromCart($key, $details);
        })->filter()->unique()->values()->all();

        $items = Item::with('countryPrices')->whereIn('id', $itemIds)->get()->keyBy('id');
        $packages = Package::with('countryPrices')->whereIn('id', $packageIds)->get()->keyBy('id');
        $user = auth()->user();

        $lines = [];

        foreach ($cart as $key => $details) {
            $quantity = max(1, (int) ($details['quantity'] ?? 1));
            $price = (float) ($details['price'] ?? 0);
            $points = (int) ($details['points'] ?? 0);
            $countryCode = $details['country_code']
                ?? session('shopping_country')
                ?? $pricing->detectUserCountry();
            $currencySymbol = $pricing->currencySymbol($countryCode);
            $isPackage = $this->isCartPackageEntry($key, $details);

            if ($isPackage) {
                $packageId = $this->resolvePackageIdFromCart($key, $details);
                $package = $packageId ? $packages->get($packageId) : null;
                $stock = $package ? $packagePricing->resolveStock($package, $countryCode) : 0;
                $packageCountryPrice = $package?->countryPrices->firstWhere('country_code', $countryCode);
                $currentSizeLabel = $packageCountryPrice?->local_size;

                $lines[] = [
                    'id' => (string) $key,
                    'display_name' => $package?->local_name ?? ($details['name'] ?? __('shop.package_badge')),
                    'image' => $details['image'] ?? $package?->image,
                    'price' => $price,
                    'quantity' => $quantity,
                    'points' => $points,
                    'subtotal' => round($price * $quantity, 2),
                    'currency_symbol' => $currencySymbol,
                    'stock' => $stock,
                    'max_quantity' => $stock > 0 ? max($quantity, $stock) : $quantity,
                    'variant_options' => [],
                    'current_variant_id' => null,
                    'current_size_label' => $currentSizeLabel,
                    'is_package' => true,
                    'is_out_of_stock' => $stock <= 0,
                    'is_unavailable' => $stock <= 0 || $quantity > $stock,
                ];

                continue;
            }

            $itemId = $this->resolveItemIdFromCart($key, $details);
            $item = $itemId ? $items->get($itemId) : null;
            $currentVariantId = isset($details['country_price_id']) ? (int) $details['country_price_id'] : null;
            $variants = $item ? $pricing->variantsForCountry($item, $countryCode) : collect();

            $variantOptions = $variants->count() > 1
                ? $variants->map(fn ($variant) => [
                    'id' => $variant->id,
                    'label' => $variant->local_size ?: __('shop.default_size'),
                    'stock' => $pricing->resolveStock($item, $countryCode, $variant->id),
                    'price' => $pricing->resolvePrice($item, $user, $countryCode, $variant->id),
                ])->values()->all()
                : [];

            $currentSizeLabel = null;
            if ($currentVariantId) {
                $matchedOption = collect($variantOptions)->firstWhere('id', $currentVariantId);
                $currentSizeLabel = $matchedOption['label']
                    ?? $variants->firstWhere('id', $currentVariantId)?->local_size;
            }

            $privateQty = $item ? $this->availablePrivateQuantity($user, (int) $itemId) : 0;
            $stock = $item ? ($pricing->resolveStock($item, $countryCode, $currentVariantId) + $privateQty) : 0;
            $displayName = $this->cleanCartDisplayName($item?->local_name ?? ($details['name'] ?? ''));

            if (! $displayName) {
                $displayName = __('shop.package_badge');
            }

            $lines[] = [
                'id' => (string) $key,
                'display_name' => $displayName,
                'image' => $details['image'] ?? $item?->image,
                'price' => $price,
                'quantity' => $quantity,
                'points' => $points,
                'subtotal' => round($price * $quantity, 2),
                'currency_symbol' => $currencySymbol,
                'stock' => $stock,
                'max_quantity' => $stock > 0 ? max($quantity, $stock) : $quantity,
                'variant_options' => $variantOptions,
                'current_variant_id' => $currentVariantId,
                'current_size_label' => $currentSizeLabel,
                'is_package' => false,
                'is_out_of_stock' => $stock <= 0,
                'is_unavailable' => $stock <= 0 || $quantity > $stock,
            ];
        }

        return $lines;
    }

    private function resolveDefaultPhoneCountry(): string
    {
        $country = old('country_code');

        if ($country) {
            return (string) $country;
        }

        $phone = auth()->user()?->phone;

        if ($phone && str_starts_with($phone, '+971')) {
            return '+971';
        }

        if ($phone && str_starts_with($phone, '+966')) {
            return '+966';
        }

        return '+966';
    }

    /**
     * @return list<array{id: int, label: string, delivery_fee: float}>
     */
    private function deliveryCityOptionsForPhoneCountry(string $phoneCountry): array
    {
        $countryCode = match ($phoneCountry) {
            '+971' => 'UAE',
            '+966' => 'KSA',
            default => null,
        };

        if ($countryCode === null) {
            return [];
        }

        $country = DeliveryCountry::query()
            ->where('code', $countryCode)
            ->where('is_active', true)
            ->first();

        if (! $country) {
            return [];
        }

        $currencyLabel = $country->local_currency_label;
        $locale = app()->getLocale();

        return $country->activeCities->map(function (DeliveryCity $city) use ($currencyLabel, $locale) {
            $name = $locale === 'ar'
                ? ($city->name_ar ?: $city->name_en)
                : ($city->name_en ?: $city->name_ar);
            $fee = number_format((float) $city->delivery_fee, 0);

            return [
                'id' => $city->id,
                'label' => "{$name} — {$fee} {$currencyLabel}",
                'delivery_fee' => (float) $city->delivery_fee,
            ];
        })->values()->all();
    }

    private function cleanCartDisplayName(?string $name): string
    {
        if ($name === null || $name === '') {
            return '';
        }

        $clean = trim((string) preg_replace('/\s*(Brief description:|Description:).*$/is', '', $name));

        return $clean !== '' ? $clean : trim($name);
    }

    private function cartMutationResponse(Request $request, ?string $error = null, int $errorStatus = 422)
    {
        if ($error !== null) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $error], $errorStatus);
            }

            return redirect()->route('cart.index')->with('error', $error);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('cart.index')->with('success', __('cart_page.updated'));
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

    private function buildItemCartKey(int $itemId, ?int $countryPriceId = null): string
    {
        return $countryPriceId ? "{$itemId}_v_{$countryPriceId}" : (string) $itemId;
    }

    private function buildPackageCartKey(int $packageId, ?int $countryPriceId = null): string
    {
        return $countryPriceId ? "p_{$packageId}_v_{$countryPriceId}" : 'p_'.$packageId;
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function resolveItemIdFromCart(string|int $cartKey, array $details): ?int
    {
        if (isset($details['item_id'])) {
            return (int) $details['item_id'];
        }

        if (preg_match('/^(\d+)(?:_v_\d+)?$/', (string) $cartKey, $matches)) {
            return (int) $matches[1];
        }

        return is_numeric($cartKey) ? (int) $cartKey : null;
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

        if (preg_match('/^p_(\d+)(?:_v_\d+)?$/', (string) $cartKey, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }
}
