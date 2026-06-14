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
        $titleKey = "notifications.{$key}.title";
        $messageKey = "notifications.{$key}.message";

        Notification::create([
            'user_id' => $userId,
            'title' => __($titleKey, $data),
            'message' => __($messageKey, $data),
            'title_key' => $titleKey,
            'message_key' => $messageKey,
            'data' => $data,
            'url' => $url,
            'is_read' => false,
        ]);
    }
}
