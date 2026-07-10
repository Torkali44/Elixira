<?php

use App\Models\Category;
use App\Models\Item;

function createCartItemWithVariants(): array
{
    $category = Category::query()->create(['name' => 'Cat', 'name_en' => 'Cat', 'name_ar' => 'قسم']);

    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Soap',
        'name_en' => 'Soap',
        'name_ar' => 'صابون',
        'description' => 'desc',
        'price' => 50,
        'stock' => 10,
        'status' => 'approved',
    ]);

    $small = $item->countryPrices()->create([
        'country_code' => 'UAE',
        'size_en' => '1kg',
        'size_ar' => '1ك',
        'member_price' => 30,
        'guest_price' => 40,
        'stock' => 5,
    ]);

    $large = $item->countryPrices()->create([
        'country_code' => 'UAE',
        'size_en' => '2kg',
        'size_ar' => '2ك',
        'member_price' => 40,
        'guest_price' => 50,
        'stock' => 10,
    ]);

    return compact('item', 'small', 'large');
}

test('cart page displays synced subtotal for variant items', function () {
    ['item' => $item, 'large' => $large] = createCartItemWithVariants();

    $cartKey = $item->id.'_v_'.$large->id;

    session()->put('cart', [
        $cartKey => [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => 2,
            'price' => 0,
            'country_code' => 'UAE',
            'country_price_id' => $large->id,
            'points' => 0,
            'image' => null,
        ],
    ]);

    session()->put('shopping_country', 'UAE');

    $response = $this->get(route('cart.index'));

    $response->assertSuccessful()
        ->assertSee('80', false)
        ->assertSee('40', false);

    expect(session('cart')[$cartKey]['price'])->toBe(40.0);
});

test('cart quantity can be increased via patch request', function () {
    ['item' => $item, 'large' => $large] = createCartItemWithVariants();

    $cartKey = $item->id.'_v_'.$large->id;

    session()->put('cart', [
        $cartKey => [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => 1,
            'price' => 40,
            'country_code' => 'UAE',
            'country_price_id' => $large->id,
            'points' => 40,
            'image' => null,
        ],
    ]);

    $this->patchJson(route('cart.update'), [
        'id' => $cartKey,
        'quantity' => 3,
    ])->assertSuccessful();

    expect(session('cart')[$cartKey]['quantity'])->toBe(3);
});

test('cart quantity cannot exceed available stock', function () {
    ['item' => $item, 'large' => $large] = createCartItemWithVariants();

    $cartKey = $item->id.'_v_'.$large->id;

    session()->put('cart', [
        $cartKey => [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => 1,
            'price' => 40,
            'country_code' => 'UAE',
            'country_price_id' => $large->id,
            'points' => 40,
            'image' => null,
        ],
    ]);

    $this->patchJson(route('cart.update'), [
        'id' => $cartKey,
        'quantity' => 99,
    ])->assertUnprocessable();
});

test('cart variant can be changed to another in stock size', function () {
    ['item' => $item, 'small' => $small, 'large' => $large] = createCartItemWithVariants();

    $cartKey = $item->id.'_v_'.$large->id;
    $newKey = $item->id.'_v_'.$small->id;

    session()->put('cart', [
        $cartKey => [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => 1,
            'price' => 40,
            'country_code' => 'UAE',
            'country_price_id' => $large->id,
            'points' => 40,
            'image' => null,
        ],
    ]);

    $this->patchJson(route('cart.update-variant'), [
        'id' => $cartKey,
        'country_price_id' => $small->id,
    ])->assertSuccessful();

    expect(session('cart'))->toHaveKey($newKey)
        ->not->toHaveKey($cartKey)
        ->and(session('cart')[$newKey]['country_price_id'])->toBe($small->id)
        ->and(session('cart')[$newKey]['price'])->toBe(30.0);
});

test('cart variant change is rejected when target size is out of stock', function () {
    ['item' => $item, 'small' => $small, 'large' => $large] = createCartItemWithVariants();

    $small->update(['stock' => 0]);

    $cartKey = $item->id.'_v_'.$large->id;

    session()->put('cart', [
        $cartKey => [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => 1,
            'price' => 40,
            'country_code' => 'UAE',
            'country_price_id' => $large->id,
            'points' => 40,
            'image' => null,
        ],
    ]);

    $this->patchJson(route('cart.update-variant'), [
        'id' => $cartKey,
        'country_price_id' => $small->id,
    ])->assertUnprocessable();

    expect(session('cart'))->toHaveKey($cartKey);
});

test('cart quantity form post redirects and updates session', function () {
    ['item' => $item, 'large' => $large] = createCartItemWithVariants();

    $cartKey = $item->id.'_v_'.$large->id;

    session()->put('cart', [
        $cartKey => [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => 1,
            'price' => 40,
            'country_code' => 'UAE',
            'country_price_id' => $large->id,
            'points' => 40,
            'image' => null,
        ],
    ]);

    $this->from(route('cart.index'))
        ->patch(route('cart.update'), [
            'id' => $cartKey,
            'quantity' => 2,
        ])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('success');

    expect(session('cart')[$cartKey]['quantity'])->toBe(2);
});

test('cart variant form post redirects and updates session', function () {
    ['item' => $item, 'small' => $small, 'large' => $large] = createCartItemWithVariants();

    $cartKey = $item->id.'_v_'.$large->id;
    $newKey = $item->id.'_v_'.$small->id;

    session()->put('cart', [
        $cartKey => [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => 1,
            'price' => 40,
            'country_code' => 'UAE',
            'country_price_id' => $large->id,
            'points' => 40,
            'image' => null,
        ],
    ]);

    $this->from(route('cart.index'))
        ->patch(route('cart.update-variant'), [
            'id' => $cartKey,
            'country_price_id' => $small->id,
        ])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('success');

    expect(session('cart'))->toHaveKey($newKey)->not->toHaveKey($cartKey);
});

test('cart page warns when line is unavailable and disables checkout', function () {
    ['item' => $item, 'small' => $small] = createCartItemWithVariants();

    $small->update(['stock' => 0]);

    $cartKey = $item->id.'_v_'.$small->id;

    session()->put('cart', [
        $cartKey => [
            'type' => 'item',
            'item_id' => $item->id,
            'name' => $item->local_name,
            'quantity' => 1,
            'price' => 30,
            'country_code' => 'UAE',
            'country_price_id' => $small->id,
            'points' => 30,
            'image' => null,
        ],
    ]);

    $this->get(route('cart.index'))
        ->assertSuccessful()
        ->assertSee(__('cart_page.cart_has_unavailable_items'), false)
        ->assertSee('disabled', false);
});
