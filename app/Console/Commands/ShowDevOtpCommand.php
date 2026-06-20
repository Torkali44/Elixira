<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\EmailVerificationOtpService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ShowDevOtpCommand extends Command
{
    protected $signature = 'verification:show-otp {email : The user email address}';

    protected $description = 'Show the latest local-dev OTP for a user (local environment only)';

    public function handle(): int
    {
        if (! app()->environment('local')) {
            $this->error('This command is only available in the local environment.');

            return self::FAILURE;
        }

        $user = User::query()->where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('No user found with that email.');

            return self::FAILURE;
        }

        $code = cache()->get("dev_otp:{$user->id}");

        if (! $code) {
            $this->warn('No cached OTP found. Run: php artisan verification:send-otp '.$user->email);

            return self::FAILURE;
        }

        $otpService = app(EmailVerificationOtpService::class);

        if (! $otpService->hasValidCode($user)) {
            $this->warn('The cached OTP has expired. Resend a new code first.');

            return self::FAILURE;
        }

        $this->info("OTP for {$user->email}: {$code}");
        $this->line('Also logged in storage/logs/otp.log');

        Log::channel('otp')->info("OTP lookup for {$user->email}: {$code}");

        return self::SUCCESS;
    }
}
