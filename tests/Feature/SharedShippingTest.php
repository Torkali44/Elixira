<?php

use App\Models\Category;
use App\Models\DeliveryCity;
use App\Models\DeliveryCountry;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use App\Support\OrderReferenceGenerator;
use Database\Seeders\DeliveryZoneSeeder;

function createCheckoutItem(string $country = 'UAE'): Item
{
    $category = Category::query()->create(['name' => 'Cat', 'name_en' => 'Cat', 'name_ar' => 'قسم']);

    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Test Item',
        'name_en' => 'Test Item',
        'name_ar' => 'منتج',
        'description' => 'desc',
        'price' => 100,
        'stock' => 20,
        'status' => 'approved',
    ]);

    $item->countryPrices()->create([
        'country_code' => $country,
        'member_price' => 100,
        'guest_price' => 120,
        'stock' => 20,
    ]);

    return $item;
}

function putItemInCart(Item $item, string $country = 'UAE'): void
{
    session()->put('cart', [
        (string) $item->id => [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => 1,
            'price' => 100,
            'country_code' => $country,
            'points' => 0,
            'image' => null,
        ],
    ]);
}

it('generates a unique six character order reference on create', function () {
    $first = Order::query()->create([
        'customer_name' => 'Buyer',
        'customer_phone' => '+971501111111',
        'address' => 'Test address',
        'subtotal_amount' => 50,
        'total_amount' => 50,
        'status' => 'pending',
    ]);

    $second = Order::query()->create([
        'customer_name' => 'Buyer 2',
        'customer_phone' => '+971501111112',
        'address' => 'Test address 2',
        'subtotal_amount' => 60,
        'total_amount' => 60,
        'status' => 'pending',
    ]);

    expect($first->reference)->toMatch('/^[23456789ABCDEFGHJKLMNPQRSTUVWXYZ]{6}$/');
    expect($second->reference)->toMatch('/^[23456789ABCDEFGHJKLMNPQRSTUVWXYZ]{6}$/');
    expect($first->reference)->not->toBe($second->reference);
});

it('looks up a pending order for shared shipping by reference code', function () {
    $this->seed(DeliveryZoneSeeder::class);

    $country = DeliveryCountry::where('code', 'UAE')->first();
    $city = DeliveryCity::where('delivery_country_id', $country->id)->first();

    $order = Order::query()->create([
        'customer_name' => 'First Sibling',
        'customer_phone' => '+971501111111',
        'address' => 'Ajman — Home Street 12',
        'delivery_city_id' => $city->id,
        'delivery_fee' => 15,
        'subtotal_amount' => 100,
        'total_amount' => 115,
        'status' => 'pending',
    ]);

    $this->getJson(route('cart.shared-shipping-order', [
        'reference' => $order->reference,
        'phone_country' => '+971',
    ]))
        ->assertSuccessful()
        ->assertJsonPath('ok', true)
        ->assertJsonPath('status', 'pending')
        ->assertJsonPath('order_id', $order->id)
        ->assertJsonPath('order_reference', $order->reference)
        ->assertJsonPath('delivery_fee_waived', true)
        ->assertJsonPath('address', 'Ajman — Home Street 12');
});

it('rejects shared shipping when the first order is no longer pending', function () {
    $order = Order::query()->create([
        'customer_name' => 'First Sibling',
        'customer_phone' => '+971501111111',
        'address' => 'Home Street 12',
        'delivery_fee' => 15,
        'subtotal_amount' => 100,
        'total_amount' => 115,
        'status' => 'delivered',
    ]);

    $this->getJson(route('cart.shared-shipping-order', [
        'reference' => $order->reference,
        'phone_country' => '+971',
    ]))
        ->assertUnprocessable()
        ->assertJsonPath('ok', false)
        ->assertJsonPath('status', 'already_shipped');
});

it('waives delivery fee when checkout links a pending shared shipping order', function () {
    $this->seed(DeliveryZoneSeeder::class);

    $user = User::factory()->create();
    $item = createCheckoutItem();
    putItemInCart($item);

    $country = DeliveryCountry::where('code', 'UAE')->first();
    $city = DeliveryCity::where('delivery_country_id', $country->id)->where('name_en', 'Ajman')->first();

    $firstOrder = Order::query()->create([
        'customer_name' => 'First Sibling',
        'customer_phone' => '+971501111111',
        'address' => 'Ajman — Shared Home Address',
        'delivery_city_id' => $city->id,
        'delivery_fee' => (float) $city->delivery_fee,
        'subtotal_amount' => 100,
        'total_amount' => 100 + (float) $city->delivery_fee,
        'status' => 'pending',
    ]);

    $this->actingAs($user)->post(route('checkout'), [
        'customer_name' => 'Second Sibling',
        'phone_number' => '509999999',
        'country_code' => '+971',
        'user_code' => 'SIB2CODE',
        'address' => 'Different address attempt',
        'delivery_city_id' => $city->id,
        'shared_shipping_order_id' => $firstOrder->id,
    ])->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'customer_name' => 'Second Sibling',
        'shared_shipping_order_id' => $firstOrder->id,
        'delivery_fee' => 0,
        'subtotal_amount' => 100,
        'total_amount' => 100,
        'address' => 'Ajman — Shared Home Address',
        'delivery_city_id' => $city->id,
    ]);
});

it('charges delivery fee when linked order is already shipped', function () {
    $this->seed(DeliveryZoneSeeder::class);

    $user = User::factory()->create();
    $item = createCheckoutItem();
    putItemInCart($item);

    $country = DeliveryCountry::where('code', 'UAE')->first();
    $city = DeliveryCity::where('delivery_country_id', $country->id)->where('name_en', 'Ajman')->first();

    $firstOrder = Order::query()->create([
        'customer_name' => 'First Sibling',
        'customer_phone' => '+971501111111',
        'address' => 'Ajman — Shared Home Address',
        'delivery_city_id' => $city->id,
        'delivery_fee' => (float) $city->delivery_fee,
        'subtotal_amount' => 100,
        'total_amount' => 100 + (float) $city->delivery_fee,
        'status' => 'delivered',
    ]);

    $this->actingAs($user)->post(route('checkout'), [
        'customer_name' => 'Second Sibling',
        'phone_number' => '509999999',
        'country_code' => '+971',
        'user_code' => 'SIB2CODE',
        'address' => '123 Test Street, Building 4',
        'delivery_city_id' => $city->id,
        'shared_shipping_order_id' => $firstOrder->id,
    ])->assertSessionHasErrors('shared_shipping_order_id');
});

it('includes delivery fee map on cart page for selected cities', function () {
    $this->seed(DeliveryZoneSeeder::class);

    $item = createCheckoutItem('KSA');
    putItemInCart($item, 'KSA');
    session()->put('shopping_country', 'KSA');

    $city = DeliveryCity::query()
        ->whereHas('country', fn ($q) => $q->where('code', 'KSA'))
        ->where('name_en', 'Jeddah')
        ->first();

    $response = $this->get(route('cart.index'));

    $response->assertSuccessful()
        ->assertSee('id="delivery_city_id"', false)
        ->assertSee('data-fee="'.(float) $city->delivery_fee.'"', false)
        ->assertSee('shared_shipping_order_input', false)
        ->assertSee('id="cart-checkout-config"', false)
        ->assertSee('js/cart-checkout.js', false);
});

it('rejects shared shipping when the linked order is in a different delivery country', function () {
    $this->seed(DeliveryZoneSeeder::class);

    $country = DeliveryCountry::where('code', 'UAE')->first();
    $city = DeliveryCity::where('delivery_country_id', $country->id)->first();

    $order = Order::query()->create([
        'customer_name' => 'First Sibling',
        'customer_phone' => '+971501111111',
        'address' => 'Ajman — Home Street 12',
        'delivery_city_id' => $city->id,
        'delivery_fee' => 15,
        'subtotal_amount' => 100,
        'total_amount' => 115,
        'status' => 'pending',
    ]);

    app()->setLocale('ar');

    $this->getJson(route('cart.shared-shipping-order', [
        'reference' => $order->reference,
        'phone_country' => '+966',
    ]))
        ->assertUnprocessable()
        ->assertJsonPath('ok', false)
        ->assertJsonPath('status', 'country_mismatch')
        ->assertJsonPath('message', __('cart_page.shared_shipping_country_mismatch'));
});

it('rejects checkout when shared shipping order is in another country', function () {
    $this->seed(DeliveryZoneSeeder::class);

    $user = User::factory()->create();
    $item = createCheckoutItem('KSA');
    putItemInCart($item, 'KSA');
    session()->put('shopping_country', 'KSA');

    $country = DeliveryCountry::where('code', 'UAE')->first();
    $city = DeliveryCity::where('delivery_country_id', $country->id)->first();

    $firstOrder = Order::query()->create([
        'customer_name' => 'UAE Sibling',
        'customer_phone' => '+971501111111',
        'address' => 'Ajman — Shared Home Address',
        'delivery_city_id' => $city->id,
        'delivery_fee' => (float) $city->delivery_fee,
        'subtotal_amount' => 100,
        'total_amount' => 100 + (float) $city->delivery_fee,
        'status' => 'pending',
    ]);

    $this->actingAs($user)->post(route('checkout'), [
        'customer_name' => 'KSA Sibling',
        'phone_number' => '509999999',
        'country_code' => '+966',
        'user_code' => 'SIB2CODE',
        'address' => 'Riyadh address',
        'delivery_city_id' => DeliveryCity::query()
            ->whereHas('country', fn ($q) => $q->where('code', 'KSA'))
            ->value('id'),
        'shared_shipping_order_id' => $firstOrder->id,
    ])->assertSessionHasErrors('shared_shipping_order_id');
});

it('returns a translated validation message for invalid shared shipping reference length', function () {
    app()->setLocale('ar');

    $this->getJson(route('cart.shared-shipping-order', [
        'reference' => 'ABC',
        'phone_country' => '+971',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['reference'])
        ->assertJsonPath('errors.reference.0', __('cart_page.shared_shipping_reference_size'));
});

it('generates unique references via the generator helper', function () {
    $references = collect(range(1, 20))->map(fn () => OrderReferenceGenerator::generate());

    expect($references->unique()->count())->toBe(20);
    $references->each(fn (string $reference) => expect($reference)->toHaveLength(6));
});
