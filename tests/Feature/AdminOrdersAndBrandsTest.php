<?php

use App\Models\Brand;
use App\Models\Order;
use App\Models\User;
use App\Models\VendorProfile;

test('admin can update order status from order detail page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $order = Order::create([
        'customer_name' => 'Buyer Name',
        'customer_phone' => '0501234567',
        'address' => 'Riyadh',
        'total_amount' => 120,
        'status' => 'pending',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.orders.show', $order))
        ->assertOk()
        ->assertSee(__('admin.orders.update_status'));

    $this->actingAs($admin)
        ->patch(route('admin.orders.update', $order), [
            'status' => 'confirmed',
        ])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe('confirmed');
});

test('admin can create and delete a standalone brand', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.brands.create'))
        ->assertOk()
        ->assertSee(__('admin.brands_page.create_title'));

    $this->actingAs($admin)
        ->post(route('admin.brands.store'), [
            'name' => 'Admin Created Brand',
            'description' => 'Created by admin',
            'service_countries' => ['Saudi Arabia'],
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.brands.index'));

    $brand = Brand::query()->where('name', 'Admin Created Brand')->first();

    expect($brand)->not->toBeNull()
        ->and($brand->slug)->toBe('admin-created-brand');

    $this->actingAs($admin)
        ->delete(route('admin.brands.destroy', $brand))
        ->assertRedirect(route('admin.brands.index'));

    expect(Brand::query()->whereKey($brand->id)->exists())->toBeFalse();
});

test('approved product assigned to standalone brand appears on brand page', function () {
    $brand = Brand::query()->create([
        'vendor_profile_id' => null,
        'name' => 'Standalone Store',
        'slug' => 'standalone-store',
        'is_active' => true,
        'service_countries' => ['Saudi Arabia'],
    ]);

    $category = \App\Models\Category::query()->create([
        'name' => 'Skincare',
        'name_en' => 'Skincare',
        'name_ar' => 'عناية',
    ]);

    $item = \App\Models\Item::query()->create([
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'name' => 'Standalone Product',
        'name_en' => 'Standalone Product',
        'name_ar' => 'منتج مستقل',
        'description' => 'desc',
        'description_en' => 'desc',
        'description_ar' => 'وصف',
        'price' => 50,
        'stock' => 5,
        'status' => 'approved',
    ]);

    $item->countryPrices()->create([
        'country_code' => 'KSA',
        'member_price' => 45,
        'guest_price' => 50,
    ]);

    expect(\App\Models\Item::query()->publiclyVisible()->whereKey($item->id)->exists())->toBeTrue();

    $this->get(route('brands.show', $brand->slug))
        ->assertSuccessful()
        ->assertSee('Standalone Product');
});

test('admin cannot delete brand linked to products', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $vendor = User::factory()->create(['role' => 'vendor']);

    $profile = VendorProfile::create([
        'user_id' => $vendor->id,
        'brand_name' => 'Locked Brand',
        'status' => 'approved',
    ]);

    $brand = Brand::create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Locked Brand',
        'service_countries' => ['Saudi Arabia'],
    ]);

    \App\Models\Item::factory()->create([
        'brand_id' => $brand->id,
        'status' => 'approved',
    ]);

    $this->actingAs($admin)
        ->from(route('admin.brands.edit', $brand))
        ->delete(route('admin.brands.destroy', $brand))
        ->assertRedirect(route('admin.brands.edit', $brand))
        ->assertSessionHas('error');

    expect(Brand::query()->whereKey($brand->id)->exists())->toBeTrue();
});

test('admin editing user email keeps user visible in users list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create([
        'role' => 'user',
        'email' => 'original-user@example.com',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => 'updated-user@example.com',
            'role' => 'user',
        ])
        ->assertRedirect(route('admin.users.index'));

    expect($user->fresh())
        ->email->toBe('updated-user@example.com')
        ->email_verified_at->not->toBeNull();

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertSuccessful()
        ->assertSee('updated-user@example.com');
});

test('admin cannot edit another admin account', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $otherAdmin = User::factory()->create(['role' => 'admin', 'email' => 'other-admin@example.com']);

    $this->actingAs($admin)
        ->get(route('admin.users.edit', $otherAdmin))
        ->assertForbidden();

    $this->actingAs($admin)
        ->put(route('admin.users.update', $otherAdmin), [
            'name' => $otherAdmin->name,
            'email' => 'blocked@example.com',
            'role' => 'user',
        ])
        ->assertForbidden();
});
