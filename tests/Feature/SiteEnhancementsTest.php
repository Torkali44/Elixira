<?php

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\DxnTeamRequest;
use App\Models\Item;
use App\Models\User;
use App\Support\ItemPricingService;

test('contact form stores a message for admin review', function () {
    $response = $this->post(route('contact.store'), [
        'name' => 'Sara Ali',
        'email' => 'sara@example.com',
        'reason' => 'inquiry',
        'subject' => 'Product question',
        'message' => 'I need help choosing a product.',
    ]);

    $response->assertRedirect(route('contact'));
    expect(ContactMessage::query()->where('email', 'sara@example.com')->exists())->toBeTrue();
});

test('dxn team request can be submitted', function () {
    $response = $this->post(route('dxn-team.store'), [
        'name' => 'Ahmed DXN',
        'email' => 'ahmed@example.com',
        'phone' => '+966500000000',
        'member_code' => 'DXN123',
        'country' => 'KSA',
        'team_goal' => 'Build a 10-member team',
        'message' => 'Ready to start.',
    ]);

    $response->assertRedirect(route('dxn-team.create'));
    expect(DxnTeamRequest::query()->where('email', 'ahmed@example.com')->exists())->toBeTrue();
});

test('item pricing resolves member and guest prices by country', function () {
    $category = Category::query()->create([
        'name' => 'Pricing',
        'name_en' => 'Pricing',
        'name_ar' => 'تسعير',
    ]);

    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Priced Item',
        'name_en' => 'Priced Item',
        'name_ar' => 'منتج',
        'description' => 'desc',
        'price' => 100,
        'stock' => 5,
    ]);

    $item->countryPrices()->create([
        'country_code' => 'KSA',
        'member_price' => 80,
        'guest_price' => 100,
    ]);

    $member = User::factory()->create(['user_code' => 'MEMBER1']);
    $guest = User::factory()->create(['user_code' => null]);
    $pricing = app(ItemPricingService::class);

    expect($pricing->resolvePrice($item, $member, 'KSA'))->toBe(80.0)
        ->and($pricing->resolvePrice($item, $guest, 'KSA'))->toBe(100.0);
});

test('monthly reward points reset command clears user totals', function () {
    $user = User::factory()->create(['total_points' => 250]);

    $this->artisan('points:reset-monthly')->assertSuccessful();

    expect($user->fresh()->total_points)->toBe(0);
});
