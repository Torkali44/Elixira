<?php

use App\Models\Item;
use App\Models\Package;
use App\Models\User;
use App\Support\ItemPricingService;

test('item pricing currency follows selected country', function () {
    $pricing = app(ItemPricingService::class);

    app()->setLocale('en');
    expect($pricing->currencySymbol('KSA'))->toBe('SAR')
        ->and($pricing->currencySymbol('UAE'))->toBe('AED');

    app()->setLocale('ar');
    expect($pricing->currencySymbol('KSA'))->toBe('ريال')
        ->and($pricing->currencySymbol('UAE'))->toBe('درهم');
});

test('item localized detail sections include filled fields only', function () {
    $item = new Item([
        'description_en' => 'Short description',
        'long_description_en' => 'Long description',
        'benefits_en' => 'Great benefits',
        'ingredients_en' => null,
        'usage_instructions_en' => 'Use daily',
        'warnings_en' => 'Keep away from children',
    ]);

    app()->setLocale('en');

    $sections = $item->localizedDetailSections();

    expect($sections)->toHaveCount(4)
        ->and(collect($sections)->pluck('id')->all())->toBe([
            'description',
            'benefits',
            'usage_instructions',
            'warnings',
        ]);
});

test('package localized detail sections follow locale', function () {
    $package = new Package([
        'description_en' => 'English short',
        'description_ar' => 'وصف عربي',
        'benefits_ar' => 'فوائد',
    ]);

    app()->setLocale('ar');

    $sections = $package->localizedDetailSections();

    expect($sections[0]['title'])->toBe('الوصف')
        ->and($sections[0]['content'])->toBe('وصف عربي')
        ->and($sections[1]['content'])->toBe('فوائد');
});

test('detect user country defaults from saudi phone number', function () {
    $user = User::factory()->make(['phone' => '+966501234567']);

    expect(app(ItemPricingService::class)->detectUserCountry($user))->toBe('KSA');
});

test('item reward points resolve per country when configured', function () {
    $item = new Item([
        'reward_points' => 10,
    ]);

    $item->setRelation('countryPrices', collect([
        new \App\Models\ItemCountryPrice(['country_code' => 'UAE', 'reward_points' => 25]),
        new \App\Models\ItemCountryPrice(['country_code' => 'KSA', 'reward_points' => null]),
    ]));

    $pricing = app(ItemPricingService::class);

    expect($pricing->resolveRewardPoints($item, 'UAE'))->toBe(25)
        ->and($pricing->resolveRewardPoints($item, 'KSA'))->toBe(0);
});
