<?php

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Support\ItemPricingService;

it('saves multiple size variants for the same country', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::query()->create(['name' => 'Cat', 'name_en' => 'Cat', 'name_ar' => 'قسم']);

    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Gel',
        'name_en' => 'Gel',
        'name_ar' => 'جل',
        'description' => 'desc',
        'description_en' => 'desc',
        'description_ar' => 'وصف',
        'price' => 30,
        'stock' => 10,
        'status' => 'approved',
    ]);

    app(ItemPricingService::class)->syncCountryPrices($item, [
        'KSA' => [
            'enabled' => '1',
            'variants' => [
                [
                    'size_en' => '50ml',
                    'size_ar' => '50 مل',
                    'member_price' => 31,
                    'guest_price' => 40,
                    'reward_points' => 30,
                    'stock' => 33,
                ],
                [
                    'size_en' => '100ml',
                    'size_ar' => '100 مل',
                    'member_price' => 55,
                    'guest_price' => 70,
                    'reward_points' => 50,
                    'stock' => 20,
                ],
            ],
        ],
    ]);

    expect($item->countryPrices()->where('country_code', 'KSA')->count())->toBe(2);

    $this->actingAs($admin)
        ->get(route('admin.items.edit', $item))
        ->assertSuccessful()
        ->assertSee('50ml')
        ->assertSee('100ml');
});
