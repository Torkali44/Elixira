<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\VendorProfile;
use App\Support\UserNotifier;
use App\Support\VendorSubscriptionService;
use Illuminate\Console\Command;

class ProcessVendorSubscriptions extends Command
{
    protected $signature = 'vendors:process-subscriptions';

    protected $description = 'Notify vendors about expiring, grace, and expired subscriptions';

    public function handle(VendorSubscriptionService $subscriptions): int
    {
        $notified = 0;

        VendorProfile::query()
            ->with('user')
            ->where('status', 'approved')
            ->where('subscription_payment_status', 'confirmed')
            ->whereNotNull('subscription_ends_at')
            ->chunkById(50, function ($profiles) use ($subscriptions, &$notified) {
                foreach ($profiles as $profile) {
                    $user = $profile->user;

                    if ($user === null) {
                        continue;
                    }

                    if ($subscriptions->isExpiringSoon($profile)) {
                        if ($this->notifyOnce($user->id, 'vendor_subscription_expiring')) {
                            UserNotifier::send(
                                $user->id,
                                'vendor_subscription_expiring',
                                ['days' => (string) ($subscriptions->daysRemaining($profile) ?? 0)],
                                route('vendor.onboarding')
                            );
                            $notified++;
                        }

                        continue;
                    }

                    if ($subscriptions->isInGracePeriod($profile)) {
                        if ($this->notifyOnce($user->id, 'vendor_subscription_grace')) {
                            UserNotifier::send(
                                $user->id,
                                'vendor_subscription_grace',
                                ['days' => (string) ($subscriptions->graceDaysRemaining($profile) ?? 0)],
                                route('vendor.onboarding')
                            );
                            $notified++;
                        }

                        continue;
                    }

                    if ($subscriptions->isSubscriptionExpired($profile)) {
                        if ($this->notifyOnce($user->id, 'vendor_subscription_expired')) {
                            UserNotifier::send(
                                $user->id,
                                'vendor_subscription_expired',
                                [],
                                route('vendor.onboarding')
                            );
                            $notified++;
                        }
                    }
                }
            });

        $this->info("Processed vendor subscriptions. Sent {$notified} notification(s).");

        return self::SUCCESS;
    }

    private function notifyOnce(int $userId, string $key): bool
    {
        return ! Notification::query()
            ->where('user_id', $userId)
            ->where('message_key', "notifications.{$key}.message")
            ->where('created_at', '>=', now()->subDays(7))
            ->exists();
    }
}
