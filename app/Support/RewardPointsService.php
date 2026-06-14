<?php

namespace App\Support;

use App\Models\Order;
use App\Models\UserPointsTransaction;

class RewardPointsService
{
    public static function awardForOrder(Order $order): void
    {
        if (! $order->user_id) {
            return;
        }

        if (UserPointsTransaction::where('order_id', $order->id)->exists()) {
            return;
        }

        $order->loadMissing(['orderItems.item', 'user']);
        $user = $order->user;

        if (! $user) {
            return;
        }

        foreach ($order->orderItems as $orderItem) {
            $item = $orderItem->item;

            if (! $item) {
                continue;
            }

            $points = (int) ($item->reward_points ?? 0) * (int) $orderItem->quantity;

            if ($points <= 0) {
                continue;
            }

            $user->increment('total_points', $points);

            $nameEn = $item->name_en ?: $item->name;
            $nameAr = $item->name_ar ?: $nameEn;

            UserPointsTransaction::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'item_id' => $item->id,
                'points' => $points,
                'description_en' => "Earned {$points} points from {$nameEn} (Order #{$order->id})",
                'description_ar' => "حصلت على {$points} نقطة من {$nameAr} (الطلب رقم {$order->id})",
            ]);
        }
    }
}
