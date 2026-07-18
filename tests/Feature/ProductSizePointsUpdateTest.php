<?php

use App\Models\Category;
use App\Models\Item;
use App\Support\ItemPricingService;

it('embeds variant reward points on the product page for size switching', function () {
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
        'is_active' => true,
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

    $this->get(route('menu.show', $item).'?country=KSA')
        ->assertSuccessful()
        ->assertSee('id="product-points-badge"', false)
        ->assertSee('id="product-points-text"', false)
        ->assertSee('"points":30', false)
        ->assertSee('"points":50', false)
        ->assertSee('product-points-text', false);
});
