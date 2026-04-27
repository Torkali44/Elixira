<?php

use App\Models\Order;
use App\Models\Review;
use App\Models\User;

test('admin orders index returns 200 and peak stats work on sqlite', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Order::query()->create([
        'customer_name' => 'Client',
        'customer_phone' => '0500000000',
        'address' => 'Riyadh',
        'total_amount' => 100.50,
        'status' => 'delivered',
        'notes' => null,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.orders.index'))
        ->assertOk();
});

test('pending direct review appears on admin reviews index', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Review::query()->create([
        'type' => 'direct',
        'avatar' => 'https://framerusercontent.com/images/test.png',
        'name' => 'CloudPendingUser',
        'age' => '30',
        'skin_type' => 'dry',
        'rating' => 5,
        'content' => 'Needs approval',
        'status' => 'pending',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.reviews.index'))
        ->assertOk()
        ->assertSee('CloudPendingUser');
});
