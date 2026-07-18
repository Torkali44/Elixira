<?php

use App\Models\Package;
use App\Models\User;
use App\Support\PackagePricingService;

it('saves multiple size variants for the same package country', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $package = Package::query()->create([
        'name' => 'Aloe Pack',
        'name_en' => 'Aloe Pack',
        'name_ar' => 'باقة الألوة',
        'description' => 'desc',
        'description_en' => 'desc',
        'description_ar' => 'وصف',
        'price' => 100,
        'stock' => 10,
        'status' => 'approved',
        'is_active' => true,
    ]);

    app(PackagePricingService::class)->syncCountryPrices($package, [
        'KSA' => [
            'enabled' => '1',
            'variants' => [
                [
                    'size_en' => '500M',
                    'size_ar' => '500 مل',
                    'member_price' => 100,
                    'guest_price' => 150,
                    'reward_points' => 40,
                    'stock' => 15,
                ],
                [
                    'size_en' => '1K',
                    'size_ar' => '1 ك',
                    'member_price' => 200,
                    'guest_price' => 350,
                    'reward_points' => 100,
                    'stock' => 10,
                ],
            ],
        ],
    ]);

    expect($package->countryPrices()->where('country_code', 'KSA')->count())->toBe(2);

    $this->actingAs($admin)
        ->get(route('admin.packages.edit', $package))
        ->assertSuccessful()
        ->assertSee('500M')
        ->assertSee('1K');
});
