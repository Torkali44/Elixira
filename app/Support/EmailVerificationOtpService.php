<?php

namespace App\Support;

use App\Mail\EmailVerificationOtpMail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailVerificationOtpService
{
    public function ttlMinutes(): int
    {
        return max(1, (int) config('verification.otp_ttl_minutes', 5));
    }

    public function hasValidCode(User $user): bool
    {
        return $user->email_verification_code !== null
            && $user->email_verification_code_expires_at !== null
            && $user->email_verification_code_expires_at->isFuture();
    }

    public function send(User $user): bool
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            $user->forceFill([
                'email_verification_code' => Hash::make($code),
                'email_verification_code_expires_at' => now()->addMinutes($this->ttlMinutes()),
            ])->save();
        } catch (Throwable $exception) {
            Log::error('Verification OTP could not be saved.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }

        try {
            Mail::mailer(config('mail.default'))->to($user->email)->send(new EmailVerificationOtpMail($user, $code));

            Log::info('Verification OTP email sent.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'mailer' => config('mail.default'),
            ]);

            if (app()->environment('local')) {
                Log::channel('otp')->info("OTP for {$user->email}: {$code}");
                cache()->put("dev_otp:{$user->id}", $code, now()->addMinutes($this->ttlMinutes()));
            }

            return true;
        } catch (Throwable $exception) {
            Log::error('Verification OTP email failed.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function verify(User $user, string $code): bool
    {
        if ($user->hasVerifiedEmail()) {
            return true;
        }

        if (! $this->hasValidCode($user)) {
            return false;
        }

        if (! Hash::check($code, (string) $user->email_verification_code)) {
            return false;
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_verification_code' => null,
            'email_verification_code_expires_at' => null,
        ])->save();

        event(new Verified($user));

        return true;
    }
}
