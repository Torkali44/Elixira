<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\EmailVerificationOtpService;
use Illuminate\Console\Command;

class SendVerificationOtpCommand extends Command
{
    protected $signature = 'verification:send-otp {email : The user email address}';

    protected $description = 'Send a verification OTP email to a registered user';

    public function handle(EmailVerificationOtpService $otpService): int
    {
        $user = User::query()->where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('No user found with that email.');

            return self::FAILURE;
        }

        if ($user->hasVerifiedEmail()) {
            $this->warn('This user is already verified.');

            return self::SUCCESS;
        }

        try {
            $otpService->send($user);
        } catch (\Throwable $exception) {
            $this->error('Failed to send OTP: '.$exception->getMessage());

            return self::FAILURE;
        }

        $this->info("Verification OTP sent to {$user->email}");

        if (app()->environment('local')) {
            $this->line('Local dev: php artisan verification:show-otp '.$user->email);
            $this->line('Or read storage/logs/otp.log');
        }

        return self::SUCCESS;
    }
}
