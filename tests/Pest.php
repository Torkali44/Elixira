<?php

use App\Models\Category;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

pest()->extend(TestCase::class)
    ->in('Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

function createTestItem(array $attributes = [], array $countryPrice = []): Item
{
    $categoryId = $attributes['category_id'] ?? Category::query()->create([
        'name' => 'Test Category',
        'name_en' => 'Test Category',
        'name_ar' => 'قسم تجريبي',
    ])->id;

    $item = Item::query()->create(array_merge([
        'category_id' => $categoryId,
        'name' => 'Test Item',
        'name_en' => 'Test Item',
        'name_ar' => 'منتج تجريبي',
        'description' => 'desc',
        'description_en' => 'desc',
        'description_ar' => 'وصف',
        'price' => 50,
        'stock' => 5,
        'status' => 'approved',
    ], $attributes));

    $item->countryPrices()->create(array_merge([
        'country_code' => 'KSA',
        'member_price' => 50,
        'guest_price' => 60,
    ], $countryPrice));

    return $item->fresh(['countryPrices']);
}
