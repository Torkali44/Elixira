<?php

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\DxnSponsorCode;
use App\Models\DxnTeamRequest;
use App\Models\Faq;
use App\Models\HomePageSection;
use App\Models\Item;
use App\Models\Review;
use App\Models\User;
use App\Models\VendorProfile;
use App\Support\ItemPricingService;
use App\Support\SiteBroadcastService;
use App\Support\TagService;
use App\Support\UserNotifier;

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

test('dxn distributor application can be submitted', function () {
    DxnSponsorCode::query()->create([
        'code' => 'DXN200',
        'sponsor_name' => 'Elixira Admin',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $response = $this->post(route('dxn-distributor.store'), [
        'contract_accepted' => '1',
        'sponsor_code' => 'DXN200',
        'sponsor_name' => 'Elixira Admin',
        'country' => 'KSA',
        'name' => 'Ahmed Ali Mohammed',
        'gender' => 'male',
        'date_of_birth' => '1990-05-10',
        'id_number' => '1234567890',
        'nationality' => 'Saudi',
        'phone' => '+966500000000',
        'email' => 'ahmed@example.com',
        'has_heir' => '0',
        'address' => 'Riyadh, King Fahd Road',
        'address_country' => 'KSA',
        'address_city' => 'Riyadh',
        'postal_code' => '12345',
    ]);

    $response->assertRedirect(route('dxn-distributor.create'));
    expect(DxnTeamRequest::query()->where('email', 'ahmed@example.com')->exists())->toBeTrue();
});

test('dxn existing member verification can be submitted', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('dxn-distributor.existing-member'), [
        'member_code' => '45871236',
        'name' => 'Sara DXN',
        'email' => $user->email,
        'phone' => '+966511111111',
        'message' => 'I am already registered.',
    ]);

    $response->assertRedirect();
    expect(DxnTeamRequest::query()->where('application_type', 'existing_member')->where('member_code', '45871236')->exists())->toBeTrue();
});

test('expired vendor subscription hides products from public listings', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);
    $profile = VendorProfile::query()->create([
        'user_id' => $vendor->id,
        'brand_name' => 'Expired Brand',
        'status' => 'approved',
        'subscription_payment_status' => 'confirmed',
        'subscription_plan' => 'monthly',
        'subscription_starts_at' => now()->subDays(40),
        'subscription_ends_at' => now()->subDays(10),
    ]);
    $brand = Brand::query()->create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Expired Brand',
        'is_active' => true,
    ]);
    $category = Category::query()->create(['name' => 'Cat', 'name_en' => 'Cat', 'name_ar' => 'قسم']);
    $item = Item::query()->create([
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'name' => 'Hidden Product',
        'name_en' => 'Hidden Product',
        'name_ar' => 'منتج',
        'description' => 'desc',
        'price' => 50,
        'stock' => 3,
        'status' => 'approved',
    ]);

    expect(Item::query()->publiclyVisible()->where('id', $item->id)->exists())->toBeFalse();
    expect($item->isPubliclyVisible())->toBeFalse();
});

test('dxn application status notification links to status page', function () {
    $user = User::factory()->create();
    $application = DxnTeamRequest::query()->create([
        'user_id' => $user->id,
        'name' => 'Ahmed Ali Mohammed',
        'email' => $user->email,
        'phone' => '+966500000000',
        'country' => 'KSA',
        'status' => 'pending',
    ]);

    $this->actingAs($user);

    UserNotifier::send(
        $user->id,
        'dxn_team_request_updated',
        ['team_name' => $application->name, 'status' => 'approved'],
        route('dxn-distributor.status', ['application' => $application->id, 'notify' => 1])
    );

    $notification = $user->notifications()->latest()->first();

    expect($notification)->not->toBeNull()
        ->and($notification->url)->toContain('/my-dxn-application/'.$application->id);

    $this->get(route('dxn-distributor.status', ['application' => $application->id]))
        ->assertSuccessful()
        ->assertSee($application->name);
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

test('vendor onboarding draft preserves earlier step data', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('vendor.store'), [
        'action' => 'draft',
        'onboarding_step' => 1,
        'brand_name' => 'Saved Brand',
        'brand_description' => 'Brand description',
        'service_countries' => ['KSA'],
        'phone_country_code' => '+966',
        'phone' => '500000001',
    ])->assertRedirect()->assertSessionHasNoErrors();

    expect(VendorProfile::query()->where('user_id', $user->id)->count())->toBe(1);

    $second = $this->actingAs($user)->post(route('vendor.store'), [
        'action' => 'draft',
        'onboarding_step' => 2,
        'instagram_link' => 'https://instagram.com/savedbrand',
    ]);

    $second->assertRedirect(route('profile.edit'))
        ->assertSessionHasNoErrors();

    $profile = VendorProfile::query()->where('user_id', $user->id)->first();

    expect($profile)->not->toBeNull()
        ->and($profile->onboarding_step)->toBe(2)
        ->and($profile->brand_name)->toBe('Saved Brand')
        ->and($profile->brand_description)->toBe('Brand description')
        ->and($profile->service_countries)->toBe(['KSA'])
        ->and($profile->instagram_link)->toBe('https://instagram.com/savedbrand')
        ->and($profile->status)->toBe('draft');
});

test('vendor subscription starts when admin approves not on payment confirm alone', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $vendor = User::factory()->create();
    $profile = VendorProfile::query()->create([
        'user_id' => $vendor->id,
        'brand_name' => 'Paid Vendor',
        'status' => 'pending',
        'subscription_payment_status' => 'pending',
        'subscription_plan' => 'monthly',
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.vendors.requests.confirm-subscription', $profile))
        ->assertRedirect();

    $profile->refresh();

    expect($profile->subscription_payment_status)->toBe('confirmed')
        ->and($profile->subscription_starts_at)->toBeNull()
        ->and($profile->subscription_ends_at)->toBeNull();

    $this->actingAs($admin)
        ->patch(route('admin.vendors.requests.update', $profile), ['status' => 'approved'])
        ->assertRedirect();

    $profile->refresh();

    expect($profile->subscription_starts_at)->not->toBeNull()
        ->and($profile->subscription_ends_at)->not->toBeNull()
        ->and((int) $profile->subscription_starts_at->diffInDays($profile->subscription_ends_at))->toBe(30);
});

test('vendor products stay visible during subscription grace period', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);
    $profile = VendorProfile::query()->create([
        'user_id' => $vendor->id,
        'brand_name' => 'Grace Brand',
        'status' => 'approved',
        'subscription_payment_status' => 'confirmed',
        'subscription_plan' => 'monthly',
        'subscription_starts_at' => now()->subDays(35),
        'subscription_ends_at' => now()->subDays(2),
    ]);
    $brand = Brand::query()->create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Grace Brand',
        'is_active' => true,
    ]);
    $category = Category::query()->create(['name' => 'Grace', 'name_en' => 'Grace', 'name_ar' => 'سماح']);
    $item = Item::query()->create([
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'name' => 'Grace Product',
        'name_en' => 'Grace Product',
        'name_ar' => 'منتج',
        'description' => 'desc',
        'price' => 50,
        'stock' => 3,
        'status' => 'approved',
    ]);

    expect(Item::query()->publiclyVisible()->where('id', $item->id)->exists())->toBeTrue();
});

test('admin users list can filter verified dxn members by tag color', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $verified = User::factory()->create([
        'is_dxn_verified' => true,
        'dxn_member_code' => '12345678',
        'dxn_tag_color' => '#00ff88',
    ]);
    $unverified = User::factory()->create([
        'is_dxn_verified' => false,
        'email' => 'unverified-dxn@example.com',
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.users.index', ['dxn_verified' => 'yes', 'dxn_tag_color' => '#00ff88']));

    $response->assertSuccessful()
        ->assertSee('DXN.Mem: 12345678')
        ->assertDontSee($unverified->email);
});

test('vendor subscription expiring soon command sends renewal warning', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);
    VendorProfile::query()->create([
        'user_id' => $vendor->id,
        'brand_name' => 'Soon Expiring',
        'status' => 'approved',
        'subscription_payment_status' => 'confirmed',
        'subscription_plan' => 'monthly',
        'subscription_starts_at' => now()->subDays(26),
        'subscription_ends_at' => now()->addDays(4),
    ]);

    $this->artisan('vendors:process-subscriptions')->assertSuccessful();

    $notification = $vendor->fresh()->notifications()->latest()->first();

    expect($notification)->not->toBeNull()
        ->and($notification->message_key)->toBe('notifications.vendor_subscription_expiring.message');
});

test('cart page loads without controller resolution error', function () {
    $this->get(route('cart.index'))->assertSuccessful();
});

test('global search finds products blogs and faqs', function () {
    $category = Category::query()->create(['name' => 'Hair', 'name_en' => 'Hair', 'name_ar' => 'شعر']);
    Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Hair Cream',
        'name_en' => 'Hair Cream',
        'name_ar' => 'كريم شعر',
        'description' => 'desc',
        'price' => 50,
        'stock' => 3,
        'status' => 'approved',
    ]);

    Blog::query()->create([
        'title_en' => 'Hair Care Tips',
        'title_ar' => 'نصائح للعناية بالشعر',
        'slug' => 'hair-care-tips',
        'summary_en' => 'About hair cream',
        'summary_ar' => 'عن كريم الشعر',
        'content_en' => 'Full article about hair cream',
        'content_ar' => 'مقال كامل',
        'is_published' => true,
        'published_at' => now(),
    ]);

    Faq::query()->create([
        'question_en' => 'What is hair cream?',
        'question_ar' => 'ما هو كريم الشعر؟',
        'answer_en' => 'A nourishing cream for hair.',
        'answer_ar' => 'كريم مغذي للشعر.',
        'is_published' => true,
        'sort_order' => 1,
    ]);

    $response = $this->get(route('search.index', ['q' => 'hair cream']));

    $response->assertSuccessful()
        ->assertSee('Hair Cream')
        ->assertSee('Hair Care Tips')
        ->assertSee('What is hair cream?');
});

test('admin can manage homepage hero section', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    HomePageSection::query()->updateOrCreate(
        ['slug' => 'hero'],
        [
            'admin_label' => 'Hero',
            'template' => 'hero',
            'title' => 'Seasonal Hero',
            'subtitle' => 'Limited offer',
            'body' => json_encode(['secondary_button_label' => 'Cart', 'secondary_button_url' => '/cart']),
            'button_label' => 'Shop',
            'button_url' => '/menu',
            'sort_order' => 10,
            'is_active' => true,
        ]
    );

    $this->actingAs($admin)
        ->get(route('admin.home-sections.index'))
        ->assertSuccessful();

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('Seasonal Hero')
        ->assertSee('Limited offer');
});

test('product page shows country-specific pricing selector', function () {
    $category = Category::query()->create(['name' => 'Cat', 'name_en' => 'Cat', 'name_ar' => 'قسم']);
    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Dual Country Item',
        'name_en' => 'Dual Country Item',
        'name_ar' => 'منتج',
        'description' => 'desc',
        'price' => 100,
        'stock' => 5,
        'status' => 'approved',
    ]);
    $item->countryPrices()->create(['country_code' => 'KSA', 'member_price' => 80, 'guest_price' => 100]);
    $item->countryPrices()->create(['country_code' => 'UAE', 'member_price' => 90, 'guest_price' => 110]);

    $this->get(route('menu.show', ['item' => $item, 'country' => 'UAE']))
        ->assertSuccessful()
        ->assertSee(__('shop.country_uae'));
});

test('related testimonials appear on product page by shared tags', function () {
    $category = Category::query()->create(['name' => 'Cat', 'name_en' => 'Cat', 'name_ar' => 'قسم']);
    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Tagged Product',
        'name_en' => 'Tagged Product',
        'name_ar' => 'منتج',
        'description' => 'desc',
        'price' => 50,
        'stock' => 3,
        'status' => 'approved',
    ]);

    app(TagService::class)->syncFromInput($item, 'hair, glow');

    $review = Review::query()->create([
        'type' => 'whatsapp',
        'avatar' => 'reviews/sample.jpg',
        'content' => 'Amazing hair results',
        'status' => 'approved',
    ]);
    app(TagService::class)->syncFromInput($review, 'hair');

    $this->get(route('menu.show', $item))
        ->assertSuccessful()
        ->assertSee(__('shop.related_testimonials'))
        ->assertSee('Amazing hair results');
});

test('site broadcast notifications respect daily cap', function () {
    $user = User::factory()->create(['role' => 'user']);
    $service = app(SiteBroadcastService::class);

    $service->broadcastIfAllowed('new_product', ['product' => 'One']);
    $service->broadcastIfAllowed('new_product', ['product' => 'Two']);
    $service->broadcastIfAllowed('new_product', ['product' => 'Three']);

    expect($user->fresh()->notifications()->count())->toBe(2);
});
