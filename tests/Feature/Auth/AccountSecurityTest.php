<?php

use App\Models\User;
use App\Support\PasswordResetOtpService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

it('blocks suspended users from logging in', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
        'is_suspended' => true,
        'email_verified_at' => now(),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('logs out suspended users on next request', function () {
    $user = User::factory()->create([
        'is_suspended' => false,
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user)->get('/')->assertOk();

    $user->update(['is_suspended' => true]);

    $this->actingAs($user->fresh())->get('/')->assertRedirect(route('login'));
});

it('shows forgot password page without vite guest layout', function () {
    $this->get('/forgot-password')->assertOk()->assertSee(__('app.auth.forgot_password_title'));
});

it('sends password reset otp and allows reset flow', function () {
    Mail::fake();

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->post('/forgot-password', ['email' => $user->email])
        ->assertRedirect(route('password.verify'));

    $user->refresh();
    expect($user->password_reset_code)->not->toBeNull();

    $code = '123456';
    $user->forceFill([
        'password_reset_code' => Hash::make($code),
        'password_reset_code_expires_at' => now()->addMinutes(5),
    ])->save();

    $this->withSession(['password_reset_email' => $user->email])
        ->post('/forgot-password/verify', ['code' => $code])
        ->assertRedirect(route('password.reset.form'));

    $this->withSession(['password_reset_verified_user_id' => $user->id])
        ->post('/reset-password', [
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])
        ->assertRedirect(route('login'));

    expect(Hash::check('NewPassword123!', $user->fresh()->password))->toBeTrue();
});

it('remember me stores a remember token on login', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'remember' => 'on',
    ])->assertRedirect('/');

    expect($user->fresh()->remember_token)->not->toBeNull();
});
