<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class SiteBroadcastService
{
    public function broadcastIfAllowed(string $key, array $data = [], ?string $url = null): void
    {
        $maxPerDay = (int) config('site.broadcasts.max_per_day', 2);
        $cacheKey = 'site_broadcast_count_'.now()->toDateString();

        if ((int) Cache::get($cacheKey, 0) >= $maxPerDay) {
            return;
        }

        User::query()
            ->whereIn('role', ['user', 'vendor', 'admin'])
            ->pluck('id')
            ->each(fn (int $userId) => UserNotifier::send($userId, $key, $data, $url));

        Cache::put($cacheKey, (int) Cache::get($cacheKey, 0) + 1, now()->endOfDay());
    }
}
