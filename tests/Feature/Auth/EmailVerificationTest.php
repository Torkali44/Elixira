<?php

use App\Mail\EmailVerificationOtpMail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

test('email verification screen can be rendered', function () {
    Mail::fake();

    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/verify-email');

    $response->assertStatus(200);
    Mail::assertSent(EmailVerificationOtpMail::class);
});

test('email can be verified with a valid otp code', function () {
    $user = User::factory()->unverified()->create();
    $code = '482913';

    $user->forceFill([
        'email_verification_code' => Hash::make($code),
        'email_verification_code_expires_at' => now()->addMinutes(5),
    ])->save();

    Event::fake();

    $response = $this->actingAs($user)->post(route('verification.verify'), [
        'code' => $code,
    ]);

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
});

test('email is not verified with an invalid otp code', function () {
    $user = User::factory()->unverified()->create();

    $user->forceFill([
        'email_verification_code' => Hash::make('111111'),
        'email_verification_code_expires_at' => now()->addMinutes(5),
    ])->save();

    $this->actingAs($user)->post(route('verification.verify'), [
        'code' => '999999',
    ])->assertSessionHasErrors('code');

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('verification otp can be resent', function () {
    Mail::fake();

    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->post(route('verification.send'))
        ->assertRedirect()
        ->assertSessionHas('status', 'verification-otp-sent');

    Mail::assertSent(EmailVerificationOtpMail::class);
});

test('verify page still loads when otp delivery fails', function () {
    $user = User::factory()->unverified()->create();

    $this->mock(\App\Support\EmailVerificationOtpService::class, function ($mock): void {
        $mock->shouldReceive('hasValidCode')->andReturn(false);
        $mock->shouldReceive('send')->andReturn(false);
    });

    $this->actingAs($user)
        ->get('/verify-email')
        ->assertOk()
        ->assertSee(__('app.auth.verify_otp_send_failed'), false);
});
