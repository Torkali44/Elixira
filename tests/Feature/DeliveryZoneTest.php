<?php

use App\Models\Category;
use App\Models\DeliveryCity;
use App\Models\DeliveryCountry;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\DeliveryZoneSeeder;

it('returns delivery cities for uae phone country', function () {
    $this->seed(DeliveryZoneSeeder::class);

    $response = $this->getJson(route('delivery-cities.index', ['phone_country' => '+971']));

    $response->assertSuccessful()
        ->assertJsonPath('country', 'UAE')
        ->assertJsonPath('currency', 'AED');

    expect($response->json('cities'))->not->toBeEmpty()
        ->and(collect($response->json('cities'))->pluck('name'))->toContain('Ajman');
});

it('requires delivery city at checkout when cities exist', function () {
    $this->seed(DeliveryZoneSeeder::class);

    $user = User::factory()->create();
    $category = Category::query()->create(['name' => 'Cat', 'name_en' => 'Cat', 'name_ar' => 'قسم']);
    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Test Item',
        'name_en' => 'Test Item',
        'name_ar' => 'منتج',
        'description' => 'desc',
        'price' => 100,
        'stock' => 5,
        'status' => 'approved',
    ]);
    $item->countryPrices()->create(['country_code' => 'UAE', 'member_price' => 100, 'guest_price' => 120, 'stock' => 5]);

    session()->put('cart', [
        (string) $item->id => [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => 1,
            'price' => 100,
            'country_code' => 'UAE',
            'points' => 0,
            'image' => null,
        ],
    ]);

    $this->actingAs($user)->post(route('checkout'), [
        'customer_name' => 'Test Customer',
        'phone_number' => '501234567',
        'country_code' => '+971',
        'user_code' => 'TESTCODE',
        'address' => '123 Test Street, Building 4',
    ])->assertSessionHasErrors('delivery_city_id');
});

it('adds delivery fee to order total at checkout', function () {
    $this->seed(DeliveryZoneSeeder::class);

    $user = User::factory()->create();
    $category = Category::query()->create(['name' => 'Cat', 'name_en' => 'Cat', 'name_ar' => 'قسم']);
    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Test Item',
        'name_en' => 'Test Item',
        'name_ar' => 'منتج',
        'description' => 'desc',
        'price' => 100,
        'stock' => 5,
        'status' => 'approved',
    ]);
    $item->countryPrices()->create(['country_code' => 'UAE', 'member_price' => 100, 'guest_price' => 120, 'stock' => 5]);

    $country = DeliveryCountry::where('code', 'UAE')->first();
    $city = DeliveryCity::where('delivery_country_id', $country->id)->where('name_en', 'Ajman')->first();

    session()->put('cart', [
        (string) $item->id => [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => 1,
            'price' => 100,
            'country_code' => 'UAE',
            'points' => 0,
            'image' => null,
        ],
    ]);

    $this->actingAs($user)->post(route('checkout'), [
        'customer_name' => 'Test Customer',
        'phone_number' => '501234567',
        'country_code' => '+971',
        'user_code' => 'TESTCODE',
        'address' => '123 Test Street, Building 4',
        'delivery_city_id' => $city->id,
    ])->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'subtotal_amount' => 100,
        'delivery_fee' => (float) $city->delivery_fee,
        'total_amount' => 100 + (float) $city->delivery_fee,
        'delivery_city_id' => $city->id,
    ]);
});
