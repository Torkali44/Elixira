<?php

namespace App\Support;

use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PasswordResetOtpService
{
    public function ttlMinutes(): int
    {
        return max(1, (int) config('verification.otp_ttl_minutes', 5));
    }

    public function hasValidCode(User $user): bool
    {
        return $user->password_reset_code !== null
            && $user->password_reset_code_expires_at !== null
            && $user->password_reset_code_expires_at->isFuture();
    }

    public function send(User $user): bool
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            $user->forceFill([
                'password_reset_code' => Hash::make($code),
                'password_reset_code_expires_at' => now()->addMinutes($this->ttlMinutes()),
            ])->save();
        } catch (Throwable $exception) {
            Log::error('Password reset OTP could not be saved.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }

        try {
            Mail::mailer(config('mail.default'))->to($user->email)->send(new PasswordResetOtpMail($user, $code));

            Log::info('Password reset OTP email sent.', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return true;
        } catch (Throwable $exception) {
            Log::error('Password reset OTP email failed.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function verify(User $user, string $code): bool
    {
        if (! $this->hasValidCode($user)) {
            return false;
        }

        if (! Hash::check($code, (string) $user->password_reset_code)) {
            return false;
        }

        return true;
    }

    public function clear(User $user): void
    {
        $user->forceFill([
            'password_reset_code' => null,
            'password_reset_code_expires_at' => null,
        ])->save();
    }
}
