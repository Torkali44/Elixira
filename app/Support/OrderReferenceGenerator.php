<?php

namespace App\Support;

use App\Models\Order;

class OrderReferenceGenerator
{
    private const ALPHABET = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';

    public static function generate(): string
    {
        do {
            $reference = self::randomReference();
        } while (Order::query()->where('reference', $reference)->exists());

        return $reference;
    }

    private static function randomReference(): string
    {
        $chars = self::ALPHABET;
        $length = strlen($chars);
        $result = '';

        for ($i = 0; $i < 6; $i++) {
            $result .= $chars[random_int(0, $length - 1)];
        }

        return $result;
    }
}
