<?php

use App\Models\Item;
use App\Models\Package;

test('item local long description follows application locale', function () {
    $item = new Item([
        'long_description_en' => 'English details',
        'long_description_ar' => 'تفاصيل عربية',
    ]);

    app()->setLocale('en');
    expect($item->local_long_description)->toBe('English details');

    app()->setLocale('ar');
    expect($item->local_long_description)->toBe('تفاصيل عربية');
});

test('package local long description falls back to english when arabic is empty', function () {
    $package = new Package([
        'long_description_en' => 'Package details',
        'long_description_ar' => null,
    ]);

    app()->setLocale('ar');
    expect($package->local_long_description)->toBe('Package details');
});
