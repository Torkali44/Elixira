<?php

return [
    'free_vendor_slots' => 10,
    'subscription_currency' => 'SAR',
    'grace_period_days' => 7,
    'expiry_warning_days' => 5,
    'bank_account_number' => env('VENDOR_BANK_ACCOUNT', '009520809741001'),
    'bank_account_holder' => env('VENDOR_BANK_HOLDER', 'SALEM SUROOR MUBARAK SHAHEEN ALSHAMSI'),
    'bank_name' => env('VENDOR_BANK_NAME', 'Dubai Islamic Bank (DIB)'),
    'bank_iban' => env('VENDOR_BANK_IBAN', 'AE920240009520809741001'),

    'plans' => [
        'monthly' => [
            'days' => 30,
            'price' => 20,
            'original_price' => 30,
            'color' => '#5B9BD5',
            'features' => ['support_standard'],
        ],
        'semi_annual' => [
            'days' => 180,
            'price' => 100,
            'original_price' => 120,
            'color' => '#2563EB',
            'features' => ['support_fast', 'analytics'],
        ],
        'yearly' => [
            'days' => 356,
            'price' => 180,
            'original_price' => 240,
            'color' => '#1E3A5F',
            'features' => ['support_faster', 'analytics', 'featured'],
        ],
    ],

    'plan_common_features' => ['store', 'products'],
];
