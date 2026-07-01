<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

test('forgot password screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertOk();
});

test('reset code can be requested', function () {
    Mail::fake();

    $user = User::factory()->create();

    $response = $this->post('/forgot-password', ['email' => $user->email]);

    $response->assertRedirect(route('password.verify'));
});

test('password can be reset with otp flow', function () {
    $user = User::factory()->create();
    $code = '654321';

    $user->forceFill([
        'password_reset_code' => Hash::make($code),
        'password_reset_code_expires_at' => now()->addMinutes(5),
    ])->save();

    $this->withSession(['password_reset_email' => $user->email])
        ->post('/forgot-password/verify', ['code' => $code])
        ->assertRedirect(route('password.reset.form'));

    $this->withSession(['password_reset_verified_user_id' => $user->id])
        ->post('/reset-password', [
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertRedirect(route('login'));

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});
