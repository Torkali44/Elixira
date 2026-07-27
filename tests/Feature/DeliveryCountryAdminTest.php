<?php

use App\Models\DeliveryCity;
use App\Models\DeliveryCountry;
use App\Models\User;

it('updates a delivery city name and fee via post without method spoofing', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $country = DeliveryCountry::query()->create([
        'code' => 'TST',
        'name_en' => 'Testland',
        'name_ar' => 'تست',
        'currency_code' => 'SAR',
        'currency_label_en' => 'SAR',
        'currency_label_ar' => 'ر.س',
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $city = $country->cities()->create([
        'name_en' => 'Old City',
        'name_ar' => 'مدينة قديمة',
        'delivery_fee' => 10,
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.delivery-countries.cities.update', [$country, $city]), [
            'name_en' => 'New City',
            'name_ar' => 'مدينة جديدة',
            'delivery_fee' => 25.5,
            'sort_order' => 2,
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.delivery-countries.edit', $country))
        ->assertSessionHas('success');

    $city->refresh();

    expect($city->name_en)->toBe('New City')
        ->and((float) $city->delivery_fee)->toBe(25.5)
        ->and($city->sort_order)->toBe(2);
});

it('updates a city when mysql-style string foreign keys are compared safely', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $country = DeliveryCountry::query()->create([
        'code' => 'STR',
        'name_en' => 'Stringland',
        'name_ar' => 'سترنج',
        'currency_code' => 'AED',
        'currency_label_en' => 'AED',
        'currency_label_ar' => 'د.إ',
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $city = DeliveryCity::query()->create([
        'delivery_country_id' => (string) $country->id,
        'name_en' => 'Port',
        'name_ar' => 'ميناء',
        'delivery_fee' => 5,
        'is_active' => true,
        'sort_order' => 0,
    ]);

    // Simulate MySQL returning string ids on the ownership check path
    expect((int) $city->delivery_country_id === (int) $country->id)->toBeTrue();

    $this->actingAs($admin)
        ->post(route('admin.delivery-countries.cities.update', [$country, $city]), [
            'name_en' => 'Port Updated',
            'name_ar' => 'ميناء محدث',
            'delivery_fee' => 8,
            'sort_order' => 0,
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.delivery-countries.edit', $country));

    expect($city->fresh()->name_en)->toBe('Port Updated');
});

it('rejects updating a city that belongs to another country', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $countryA = DeliveryCountry::query()->create([
        'code' => 'AAA',
        'name_en' => 'A',
        'name_ar' => 'أ',
        'currency_code' => 'SAR',
        'currency_label_en' => 'SAR',
        'currency_label_ar' => 'ر.س',
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $countryB = DeliveryCountry::query()->create([
        'code' => 'BBB',
        'name_en' => 'B',
        'name_ar' => 'ب',
        'currency_code' => 'AED',
        'currency_label_en' => 'AED',
        'currency_label_ar' => 'د.إ',
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $cityOnB = $countryB->cities()->create([
        'name_en' => 'Other',
        'name_ar' => 'أخرى',
        'delivery_fee' => 1,
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.delivery-countries.cities.update', [$countryA, $cityOnB]), [
            'name_en' => 'Hacked',
            'name_ar' => 'اختراق',
            'delivery_fee' => 99,
            'sort_order' => 0,
            'is_active' => '1',
        ])
        ->assertNotFound();
});
