<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone_country_code' => '+966',
        'phone' => '512345678',
        'password' => 'password',
        'password_confirmation' => 'password',
        'account_type' => 'customer',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    expect(auth()->user()->phone)->toBe('+966512345678');
});
