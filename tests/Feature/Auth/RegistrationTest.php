<?php

use App\Mail\EmailVerificationOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200)
        ->assertSee('data-elx-phone-picker', false)
        ->assertSee(__('shop.country_ksa'))
        ->assertSee(__('shop.country_uae'));
});

test('new users can register and must verify email', function () {
    Mail::fake();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone_country_code' => '+971',
        'phone' => '501234567',
        'password' => 'password',
        'password_confirmation' => 'password',
        'account_type' => 'customer',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('verification.notice'));

    $user = User::query()->where('email', 'test@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->phone)->toBe('+971501234567')
        ->and($user->hasVerifiedEmail())->toBeFalse();

    Mail::assertSent(EmailVerificationOtpMail::class, function (EmailVerificationOtpMail $mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test('unverified users can re-register with the same email', function () {
    Mail::fake();

    $existing = User::factory()->unverified()->create([
        'email' => 'retry@example.com',
        'name' => 'Old Name',
    ]);

    $this->post('/register', [
        'name' => 'New Name',
        'email' => 'retry@example.com',
        'phone_country_code' => '+966',
        'phone' => '501112233',
        'password' => 'password',
        'password_confirmation' => 'password',
        'account_type' => 'customer',
    ])->assertRedirect(route('verification.notice'));

    expect(User::query()->where('email', 'retry@example.com')->count())->toBe(1)
        ->and($existing->fresh()->name)->toBe('New Name')
        ->and($existing->fresh()->hasVerifiedEmail())->toBeFalse();

    Mail::assertSent(EmailVerificationOtpMail::class);
});

test('verified emails cannot be registered again', function () {
    User::factory()->create([
        'email' => 'taken@example.com',
        'email_verified_at' => now(),
    ]);

    $this->post('/register', [
        'name' => 'Someone',
        'email' => 'taken@example.com',
        'phone_country_code' => '+966',
        'phone' => '501112233',
        'password' => 'password',
        'password_confirmation' => 'password',
        'account_type' => 'customer',
    ])->assertSessionHasErrors('email');
});
