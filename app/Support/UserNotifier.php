<?php

namespace App\Support;

use App\Models\Notification;

class UserNotifier
{
    /**
     * @param  array<string, string|int|float>  $data
     */
    public static function send(int $userId, string $key, array $data = [], ?string $url = null): void
    {
        Notification::create([
            'user_id' => $userId,
            'title_key' => "notifications.{$key}.title",
            'message_key' => "notifications.{$key}.message",
            'data' => $data,
            'url' => $url,
            'is_read' => false,
        ]);
    }
}
