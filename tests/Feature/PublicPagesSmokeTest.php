<?php

use App\Models\Category;
use App\Models\User;

test('public storefront pages load without server errors', function (string $routeName, array $params) {
    $this->get(route($routeName, $params))->assertSuccessful();
})->with([
    'home' => ['home', []],
    'about' => ['about', []],
    'contact' => ['contact', []],
    'explore' => ['explore', []],
    'menu' => ['menu.index', []],
    'packages' => ['packages.index', []],
    'search' => ['search.index', ['q' => 'test']],
    'cart' => ['cart.index', []],
    'faqs' => ['faqs.index', []],
    'blogs' => ['blogs.index', []],
    'testimonials' => ['testimonials.index', []],
    'brands' => ['brands.index', []],
    'login' => ['login', []],
    'register' => ['register', []],
    'vendor terms' => ['vendor.terms', []],
    'dxn distributor' => ['dxn-distributor.create', []],
    'track order' => ['orders.track', []],
]);

test('public pages include responsive viewport meta tag', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('width=device-width', false);
});

test('public pages include site favicon', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('images/tab_icon.jpg', false);
});

test('admin dashboard and key pages load for administrator', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $routes = [
        'admin.dashboard',
        'admin.items.index',
        'admin.packages.index',
        'admin.orders.index',
        'admin.users.index',
        'admin.categories.index',
        'admin.home-sections.index',
        'admin.settings.translations',
    ];

    foreach ($routes as $route) {
        $this->actingAs($admin)->get(route($route))->assertSuccessful();
    }
});

test('checkout rejects duplicate member code for authenticated user', function () {
    User::factory()->create(['user_code' => 'TAKEN1']);
    $user = User::factory()->create(['user_code' => null]);

    $category = Category::query()->create([
        'name' => 'Cat',
        'name_en' => 'Cat',
        'name_ar' => 'قسم',
    ]);

    $item = createTestItem([
        'category_id' => $category->id,
        'name' => 'Checkout Item',
        'name_en' => 'Checkout Item',
        'price' => 50,
        'stock' => 5,
    ]);

    session()->put('cart', [
        $item->id => [
            'type' => 'item',
            'name' => $item->local_name,
            'quantity' => 1,
            'price' => 60,
            'country_code' => 'KSA',
            'points' => 0,
            'image' => null,
        ],
    ]);

    $this->actingAs($user)
        ->post(route('checkout'), [
            'customer_name' => 'Duplicate Code User',
            'phone_number' => '501234567',
            'country_code' => '+966',
            'address' => '123 Test Street, Riyadh, Saudi Arabia',
            'user_code' => 'TAKEN1',
        ])
        ->assertSessionHasErrors('user_code');

    expect($user->fresh()->user_code)->toBeNull();
});
