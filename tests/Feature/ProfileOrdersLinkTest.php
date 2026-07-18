<?php

use App\Models\Order;
use App\Models\User;

it('renders profile order detail and invoice urls with a slash before the id', function () {
    $user = User::factory()->create(['phone' => '+966501234567']);

    $order = Order::query()->create([
        'user_id' => $user->id,
        'customer_name' => $user->name,
        'customer_phone' => '+966501234567',
        'address' => 'Riyadh',
        'subtotal_amount' => 100,
        'total_amount' => 100,
        'status' => 'pending',
    ]);

    $guestLinkedOrder = Order::query()->create([
        'user_id' => null,
        'customer_name' => 'Guest Match',
        'customer_phone' => '+966501234567',
        'address' => 'Jeddah',
        'subtotal_amount' => 80,
        'total_amount' => 80,
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->get(route('profile.orders.index'))
        ->assertSuccessful()
        ->assertSee('/profile/orders/'.$order->id, false)
        ->assertDontSee('/profile/orders'.$order->id.'"', false);

    $this->actingAs($user)
        ->get(url('/profile/orders/'.$order->id))
        ->assertSuccessful();

    $this->actingAs($user)
        ->get(url('/profile/orders/'.$order->id.'/invoice'))
        ->assertSuccessful();

    $this->actingAs($user)
        ->get(url('/profile/orders/'.$guestLinkedOrder->id))
        ->assertSuccessful();
});

it('marks a notification as read so the unread count drops site-wide', function () {
    $user = User::factory()->create();
    $notification = $user->notifications()->create([
        'title' => 'Order update',
        'message' => 'Your order moved forward',
        'url' => '/profile',
        'is_read' => false,
    ]);

    $this->actingAs($user)
        ->postJson(route('notifications.read', $notification))
        ->assertSuccessful()
        ->assertJson([
            'success' => true,
            'unread_count' => 0,
        ]);

    expect($notification->fresh()->is_read)->toBeTrue();
    expect($user->unreadNotifications()->count())->toBe(0);
});

it('opens a notification via get and marks it read before redirecting', function () {
    $user = User::factory()->create();
    $notification = $user->notifications()->create([
        'title' => 'Package update',
        'message' => 'Your package was approved',
        'url' => '/profile',
        'is_read' => false,
    ]);

    // Simulate MySQL string user_id without relying on strict types.
    $notification->setRawAttributes(array_merge($notification->getAttributes(), [
        'user_id' => (string) $user->id,
    ]));

    $this->actingAs($user)
        ->get(route('notifications.open', $notification->id))
        ->assertRedirect('/profile');

    expect($notification->fresh()->is_read)->toBeTrue()
        ->and($user->unreadNotifications()->count())->toBe(0);
});

it('forbids opening another users notification', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $notification = $owner->notifications()->create([
        'title' => 'Private',
        'message' => 'Not yours',
        'url' => '/profile',
        'is_read' => false,
    ]);

    $this->actingAs($other)
        ->get(route('notifications.open', $notification))
        ->assertForbidden();
});
