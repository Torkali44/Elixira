<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\VendorProfile;

test('admin report printable routes return 200', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $routes = [
        'admin.reports.orders',
        'admin.reports.products',
        'admin.reports.vendors',
        'admin.reports.brands',
        'admin.reports.financials',
    ];

    foreach ($routes as $route) {
        $this->actingAs($admin)
            ->get(route($route))
            ->assertOk();
    }
});

test('admin can approve a pending product from details page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $vendor = User::factory()->create(['role' => 'vendor']);

    $profile = VendorProfile::create([
        'user_id' => $vendor->id,
        'brand_name' => 'Test Brand',
        'status' => 'approved',
    ]);

    $brand = Brand::create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Test Brand',
        'service_countries' => ['Saudi Arabia'],
    ]);

    $category = Category::create([
        'name' => 'Skincare',
    ]);

    $item = Item::create([
        'user_id' => $vendor->id,
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'name' => 'Pending Product',
        'price' => 50,
        'stock' => 10,
        'status' => 'pending',
    ]);

    // View product detail page
    $this->actingAs($admin)
        ->get(route('admin.items.show', $item->id))
        ->assertOk()
        ->assertSee('Pending Product')
        ->assertSee('Approve Product')
        ->assertSee('Reject Product');

    // Approve the product
    $this->actingAs($admin)
        ->patch(route('admin.items.approve', $item->id))
        ->assertRedirect(route('admin.items.index'));

    $this->assertEquals('approved', $item->fresh()->status);
});

test('admin can reject a pending product with notes', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $vendor = User::factory()->create(['role' => 'vendor']);

    $profile = VendorProfile::create([
        'user_id' => $vendor->id,
        'brand_name' => 'Test Brand',
        'status' => 'approved',
    ]);

    $brand = Brand::create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Test Brand',
        'service_countries' => ['Saudi Arabia'],
    ]);

    $category = Category::create([
        'name' => 'Skincare',
    ]);

    $item = Item::create([
        'user_id' => $vendor->id,
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'name' => 'Pending Product 2',
        'price' => 50,
        'stock' => 10,
        'status' => 'pending',
    ]);

    // Reject the product
    $this->actingAs($admin)
        ->patch(route('admin.items.reject', $item->id), [
            'reject_type' => 'notes',
            'rejection_reason' => 'Fix spelling errors',
        ])
        ->assertRedirect(route('admin.items.index'));

    $this->assertEquals('rejected_with_notes', $item->fresh()->status);
    $this->assertEquals('Fix spelling errors', $item->fresh()->rejection_reason);
});

test('vendor sees rejected_with_notes as needs revision not pending', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);

    $profile = VendorProfile::create([
        'user_id' => $vendor->id,
        'brand_name' => 'Revision Brand',
        'status' => 'approved',
    ]);

    $brand = Brand::create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Revision Brand',
        'service_countries' => ['Saudi Arabia'],
    ]);

    $category = Category::create([
        'name' => 'Skincare',
    ]);

    Item::create([
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'name' => 'Revision Product',
        'price' => 50,
        'stock' => 10,
        'status' => 'rejected_with_notes',
        'rejection_reason' => 'Fix image quality',
    ]);

    $this->actingAs($vendor)
        ->get(route('vendor.items.index'))
        ->assertOk()
        ->assertSee('Needs Revision')
        ->assertDontSee('Pending Approval');
});

test('admin can access vendor brand edit from brands management', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $vendor = User::factory()->create(['role' => 'vendor']);

    $profile = VendorProfile::create([
        'user_id' => $vendor->id,
        'brand_name' => 'Editable Brand',
        'status' => 'approved',
    ]);

    $brand = Brand::create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Editable Brand',
        'service_countries' => ['Saudi Arabia'],
    ]);

    $this->actingAs($admin)
        ->get(route('admin.brands.edit', $brand))
        ->assertOk()
        ->assertSee('Editable Brand');
});

test('vendor sees inactive brand message instead of 404', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);

    $profile = VendorProfile::create([
        'user_id' => $vendor->id,
        'brand_name' => 'Inactive Brand',
        'status' => 'approved',
    ]);

    $brand = Brand::create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Inactive Brand',
        'slug' => 'inactive-brand',
        'service_countries' => ['Saudi Arabia'],
        'is_active' => false,
    ]);

    $this->actingAs($vendor)
        ->get(route('brands.show', $brand->slug))
        ->assertOk()
        ->assertSee('is currently inactive')
        ->assertSee('Manage My Brand');

    auth()->logout();

    $this->get(route('brands.show', $brand->slug))
        ->assertNotFound();
});

test('vendor brand routes are registered and accessible', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);

    $profile = VendorProfile::create([
        'user_id' => $vendor->id,
        'brand_name' => 'Test Brand',
        'status' => 'approved',
    ]);

    $brand = Brand::create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Test Brand',
        'service_countries' => ['Saudi Arabia'],
    ]);

    $this->actingAs($vendor)
        ->get(route('vendor.brand.edit'))
        ->assertOk()
        ->assertSee('Test Brand');
});

test('vendor dashboard page loads successfully without SQL errors', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);

    $profile = VendorProfile::create([
        'user_id' => $vendor->id,
        'brand_name' => 'Test Brand',
        'status' => 'approved',
    ]);

    $brand = Brand::create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Test Brand',
        'service_countries' => ['Saudi Arabia'],
    ]);

    $category = Category::create([
        'name' => 'Skincare',
    ]);

    $item = Item::create([
        'user_id' => $vendor->id,
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'name' => 'Vendor Product',
        'price' => 50,
        'stock' => 10,
        'status' => 'approved',
    ]);

    $order = Order::create([
        'customer_name' => 'Test Buyer',
        'customer_phone' => '0501234567',
        'address' => 'Riyadh',
        'total_amount' => 50,
        'status' => 'pending',
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'item_id' => $item->id,
        'quantity' => 1,
        'price' => 50,
    ]);

    $this->actingAs($vendor)
        ->get(route('vendor.dashboard'))
        ->assertOk()
        ->assertSee('Vendor Product')
        ->assertSee('Test Buyer')
        ->assertSee('Print Dashboard');
});
