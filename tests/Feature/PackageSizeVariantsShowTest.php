<?php

use App\Models\Package;
use App\Models\User;
use App\Support\PackagePricingService;

it('shows a size dropdown for package country variants on the package page', function () {
    $package = Package::query()->create([
        'name' => 'Aloe Bundle',
        'name_en' => 'Aloe Bundle',
        'name_ar' => 'باقة الألوة',
        'description' => 'desc',
        'description_en' => 'desc',
        'description_ar' => 'وصف',
        'price' => 100,
        'stock' => 25,
        'status' => 'approved',
        'is_active' => true,
    ]);

    app(PackagePricingService::class)->syncCountryPrices($package, [
        'UAE' => [
            'enabled' => '1',
            'variants' => [
                [
                    'size_en' => '100 ml',
                    'size_ar' => '100 مل',
                    'member_price' => 27,
                    'guest_price' => 35,
                    'reward_points' => 25,
                    'stock' => 10,
                ],
                [
                    'size_en' => '200 ml',
                    'size_ar' => '200 مل',
                    'member_price' => 45,
                    'guest_price' => 55,
                    'reward_points' => 40,
                    'stock' => 5,
                ],
            ],
        ],
    ]);

    $this->get(route('packages.show', $package).'?country=UAE')
        ->assertSuccessful()
        ->assertSee('package-size-select', false)
        ->assertSee('100 ml')
        ->assertSee('200 ml');
});

it('adds the selected package size variant to the cart', function () {
    $user = User::factory()->create();

    $package = Package::query()->create([
        'name' => 'Aloe Bundle',
        'name_en' => 'Aloe Bundle',
        'name_ar' => 'باقة الألوة',
        'description' => 'desc',
        'description_en' => 'desc',
        'description_ar' => 'وصف',
        'price' => 100,
        'stock' => 25,
        'status' => 'approved',
        'is_active' => true,
    ]);

    app(PackagePricingService::class)->syncCountryPrices($package, [
        'UAE' => [
            'enabled' => '1',
            'variants' => [
                [
                    'size_en' => '100 ml',
                    'size_ar' => '100 مل',
                    'member_price' => 27,
                    'guest_price' => 35,
                    'reward_points' => 25,
                    'stock' => 10,
                ],
                [
                    'size_en' => '200 ml',
                    'size_ar' => '200 مل',
                    'member_price' => 45,
                    'guest_price' => 55,
                    'reward_points' => 40,
                    'stock' => 5,
                ],
            ],
        ],
    ]);

    $large = $package->countryPrices()->where('size_en', '200 ml')->firstOrFail();

    $this->actingAs($user)
        ->post(route('cart.add-package'), [
            'package_id' => $package->id,
            'country_code' => 'UAE',
            'country_price_id' => $large->id,
            'quantity' => 1,
        ])
        ->assertRedirect();

    $cart = session('cart');
    $key = 'p_'.$package->id.'_v_'.$large->id;

    expect($cart)->toHaveKey($key)
        ->and($cart[$key]['country_price_id'])->toBe($large->id)
        ->and((float) $cart[$key]['price'])->toBe(45.0);
});
