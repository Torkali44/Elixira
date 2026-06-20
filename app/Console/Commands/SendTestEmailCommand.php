<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmailCommand extends Command
{
    protected $signature = 'mail:test {email? : Recipient email address}';

    protected $description = 'Send a test email using the configured mail settings';

    public function handle(): int
    {
        $recipient = (string) ($this->argument('email') ?: config('company.email'));

        if ($recipient === '') {
            $this->error('No recipient email. Pass an email argument or set COMPANY_EMAIL in .env');

            return self::FAILURE;
        }

        if (config('mail.default') === 'log') {
            $this->warn('MAIL_MAILER is set to "log". Emails are only written to storage/logs/laravel.log.');
        }

        if (blank(config('mail.mailers.smtp.password'))) {
            $this->error('MAIL_PASSWORD is empty. Add your Gmail App Password to .env first.');

            return self::FAILURE;
        }

        try {
            Mail::raw(
                'This is a test email from '.config('app.name').'. If you received this, SMTP is configured correctly.',
                function ($message) use ($recipient): void {
                    $message->to($recipient)
                        ->subject(config('app.name').' — Test Email');
                }
            );
        } catch (\Throwable $exception) {
            $this->error('Failed to send email: '.$exception->getMessage());

            return self::FAILURE;
        }

        $this->info("Test email sent to {$recipient}");

        return self::SUCCESS;
    }
}
