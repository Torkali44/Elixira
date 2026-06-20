<?php

use App\Models\SpecialItemOffer;
use App\Models\User;

test('user with private offer can add out of stock item to cart', function () {
    $user = User::factory()->create();

    $item = createTestItem([
        'name' => 'Private Item',
        'name_en' => 'Private Item',
        'stock' => 0,
    ]);

    SpecialItemOffer::query()->create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'quantity' => 1,
        'used_quantity' => 0,
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('cart.add'), [
            'item_id' => $item->id,
            'quantity' => 1,
        ]);

    $response->assertSessionHas('success');
    expect(session('cart')[$item->id]['quantity'])->toBe(1);
});

test('user without private offer cannot add out of stock item to cart', function () {
    $user = User::factory()->create();

    $item = createTestItem([
        'name' => 'Unavailable Item',
        'name_en' => 'Unavailable Item',
        'stock' => 0,
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('cart.add'), [
            'item_id' => $item->id,
            'quantity' => 1,
        ]);

    $response->assertSessionHas('error');
});
