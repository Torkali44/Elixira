<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class MailDiagnoseCommand extends Command
{
    protected $signature = 'mail:diagnose {email : Recipient email address}';

    protected $description = 'Run a verbose SMTP send test and print transport details';

    public function handle(): int
    {
        $recipient = (string) $this->argument('email');
        $username = (string) config('mail.mailers.smtp.username');
        $password = (string) config('mail.mailers.smtp.password');
        $host = (string) config('mail.mailers.smtp.host');
        $port = (int) config('mail.mailers.smtp.port');
        $encryption = (string) (config('mail.mailers.smtp.encryption') ?: 'tls');

        $this->line('Mailer: '.config('mail.default'));
        $this->line("SMTP: {$host}:{$port} ({$encryption})");
        $this->line("From: {$username}");
        $this->line("To: {$recipient}");
        $this->newLine();

        if ($username === '' || $password === '') {
            $this->error('SMTP username/password are missing in .env');

            return self::FAILURE;
        }

        $encodedUser = rawurlencode($username);
        $encodedPass = rawurlencode($password);
        $dsn = "smtp://{$encodedUser}:{$encodedPass}@{$host}:{$port}?encryption={$encryption}&auth_mode=login";

        try {
            $transport = Transport::fromDsn($dsn);

            $email = (new Email)
                ->from($username)
                ->to($recipient)
                ->subject(config('app.name').' — SMTP Diagnose')
                ->text('If you received this message, SMTP delivery from Elixira is working. Time: '.now()->toDateTimeString());

            $transport->send($email);
        } catch (TransportExceptionInterface $exception) {
            $this->error('SMTP transport failed: '.$exception->getMessage());

            return self::FAILURE;
        } catch (\Throwable $exception) {
            $this->error('Send failed: '.$exception->getMessage());

            return self::FAILURE;
        }

        $this->info('SMTP server accepted the message for delivery.');
        $this->warn('If the inbox is empty, check Spam/Promotions and the Sent folder of the sender Gmail account.');

        return self::SUCCESS;
    }
}
