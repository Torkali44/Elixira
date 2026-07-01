<?php

use App\Models\Order;
use App\Models\User;

test('deploy ops route rejects invalid token', function () {
    config(['deploy-ops.token' => 'valid-secret-token']);

    $this->get('/ops/run/wrong-token')->assertNotFound();
});

test('deploy ops route runs configured artisan commands', function () {
    config([
        'deploy-ops.token' => 'valid-secret-token',
        'deploy-ops.commands' => ['config:clear'],
    ]);

    $this->get('/ops/run/valid-secret-token')
        ->assertOk()
        ->assertSee('config:clear')
        ->assertSee('Done.');
});

test('financials report works with mysql date expressions', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Order::query()->create([
        'customer_name' => 'Test Buyer',
        'customer_phone' => '0501234567',
        'address' => 'Riyadh',
        'total_amount' => 120.50,
        'status' => 'delivered',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.reports.financials'))
        ->assertOk();
});
