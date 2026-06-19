<?php

use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use App\Models\UserPointsTransaction;
use App\Support\RewardPointsService;

test('checkout awards reward points per product to authenticated user', function () {
    $user = User::factory()->create();
    $category = Category::query()->create([
        'name' => 'Vitamins',
        'name_en' => 'Vitamins',
        'name_ar' => 'فيتامينات',
    ]);

    $itemA = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Product A',
        'name_en' => 'Product A',
        'name_ar' => 'منتج أ',
        'description' => 'desc',
        'price' => 50,
        'stock' => 10,
        'reward_points' => 50,
    ]);

    $itemB = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Product B',
        'name_en' => 'Product B',
        'name_ar' => 'منتج ب',
        'description' => 'desc',
        'price' => 40,
        'stock' => 10,
        'reward_points' => 40,
    ]);

    session()->put('cart', [
        $itemA->id => [
            'name' => $itemA->name,
            'quantity' => 1,
            'price' => $itemA->price,
            'points' => 0,
            'image' => null,
        ],
        $itemB->id => [
            'name' => $itemB->name,
            'quantity' => 1,
            'price' => $itemB->price,
            'points' => 0,
            'image' => null,
        ],
    ]);

    $this->actingAs($user)->post(route('checkout'), [
        'customer_name' => 'Test User',
        'phone_number' => '501234567',
        'country_code' => '+966',
        'address' => '123 Test Street, Riyadh, Saudi Arabia',
    ])->assertRedirect();

    $user->refresh();

    expect($user->total_points)->toBe(90)
        ->and(UserPointsTransaction::query()->where('user_id', $user->id)->count())->toBe(2);
});

test('reward points are not awarded twice for the same order', function () {
    $user = User::factory()->create(['total_points' => 0]);
    $category = Category::query()->create(['name' => 'Cat', 'name_en' => 'Cat']);

    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Points Item',
        'name_en' => 'Points Item',
        'description' => 'desc',
        'price' => 20,
        'stock' => 5,
        'reward_points' => 25,
    ]);

    $order = Order::query()->create([
        'user_id' => $user->id,
        'customer_name' => 'Buyer',
        'customer_phone' => '+966501234567',
        'address' => '123 Test Street, Riyadh',
        'total_amount' => 20,
        'status' => 'pending',
    ]);

    $order->orderItems()->create([
        'item_id' => $item->id,
        'quantity' => 2,
        'price' => 20,
    ]);

    RewardPointsService::awardForOrder($order);
    RewardPointsService::awardForOrder($order->fresh());

    $user->refresh();

    expect($user->total_points)->toBe(50)
        ->and(UserPointsTransaction::query()->where('order_id', $order->id)->count())->toBe(1);
});

test('admin can update reward points without providing arabic name', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::query()->create([
        'name' => 'Skincare',
        'name_en' => 'Skincare',
    ]);

    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Legacy Product',
        'name_en' => 'Legacy Product',
        'description' => 'desc',
        'price' => 99,
        'stock' => 3,
        'reward_points' => 0,
    ]);

    $this->actingAs($admin)
        ->put(route('admin.items.update', $item), [
            'category_id' => $category->id,
            'name_en' => 'Legacy Product',
            'name_ar' => 'منتج قديم',
            'description_en' => 'Updated description',
            'description_ar' => 'وصف محدث',
            'stock' => 3,
            'reward_points' => 75,
            'country_prices' => [
                'KSA' => [
                    'enabled' => '1',
                    'member_price' => 99,
                    'guest_price' => 99,
                ],
            ],
        ])
        ->assertRedirect(route('admin.items.index'));

    $item->refresh();

    expect($item->reward_points)->toBe(75)
        ->and($item->name_ar)->toBe('منتج قديم');
});

test('buy now redirects user to cart page', function () {
    $user = User::factory()->create();
    $category = Category::query()->create(['name' => 'Cat', 'name_en' => 'Cat']);

    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Buy Now Item',
        'name_en' => 'Buy Now Item',
        'description' => 'desc',
        'price' => 15,
        'stock' => 5,
        'status' => 'approved',
    ]);
    $item->countryPrices()->create(['country_code' => 'KSA', 'member_price' => 12, 'guest_price' => 15]);

    $this->actingAs($user)
        ->post(route('cart.add'), [
            'item_id' => $item->id,
            'quantity' => 1,
            'country_code' => 'KSA',
            'buy_now' => 1,
        ])
        ->assertRedirect(route('cart.index'));
});

test('item local name follows application locale', function () {
    $item = Item::query()->create([
        'category_id' => Category::query()->create([
            'name' => 'Cat',
            'name_en' => 'English Cat',
            'name_ar' => 'قسم عربي',
        ])->id,
        'name' => 'Fallback',
        'name_en' => 'English Name',
        'name_ar' => 'اسم عربي',
        'description' => 'legacy',
        'description_en' => 'English description',
        'description_ar' => 'وصف عربي',
        'price' => 10,
        'stock' => 1,
    ]);

    app()->setLocale('en');
    expect($item->fresh()->local_name)->toBe('English Name')
        ->and($item->fresh()->local_description)->toBe('English description');

    app()->setLocale('ar');
    expect($item->fresh()->local_name)->toBe('اسم عربي')
        ->and($item->fresh()->local_description)->toBe('وصف عربي');
});
