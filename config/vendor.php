<?php

return [
    'free_vendor_slots' => 10,
    'annual_subscription_amount' => 180,
    'subscription_currency' => 'SAR',
    'bank_account_number' => env('VENDOR_BANK_ACCOUNT', 'SA00 0000 0000 0000 0000 0000'),
    'bank_account_holder' => env('VENDOR_BANK_HOLDER', 'Elixira Trading'),
    'bank_name' => env('VENDOR_BANK_NAME', 'Al Rajhi Bank'),
];
