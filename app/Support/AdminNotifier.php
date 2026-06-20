<?php

namespace App\Support;

use App\Models\User;

class AdminNotifier
{
    /**
     * @param  array<string, string|int|float>  $data
     */
    public static function notifyAll(string $key, array $data = [], ?string $url = null): void
    {
        User::query()
            ->where('role', 'admin')
            ->pluck('id')
            ->each(fn (int $adminId) => UserNotifier::send($adminId, $key, $data, $url));
    }
}
