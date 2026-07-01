<?php

namespace App\Support;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NavUserData
{
    public static function unreadCount(?User $user): int
    {
        if ($user === null) {
            return 0;
        }

        try {
            return $user->unreadNotifications()->count();
        } catch (\Throwable $exception) {
            report($exception);

            return 0;
        }
    }

    /**
     * @return Collection<int, Notification>
     */
    public static function recentNotifications(?User $user): Collection
    {
        if ($user === null) {
            return collect();
        }

        try {
            return $user->notifications()->take(10)->get();
        } catch (\Throwable $exception) {
            report($exception);

            return collect();
        }
    }
}
