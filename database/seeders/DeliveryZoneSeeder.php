<?php

namespace Database\Seeders;

use App\Models\DeliveryCountry;
use Illuminate\Database\Seeder;

class DeliveryZoneSeeder extends Seeder
{
    public function run(): void
    {
        $ksa = DeliveryCountry::updateOrCreate(
            ['code' => 'KSA'],
            [
                'name_en' => 'Saudi Arabia',
                'name_ar' => 'السعودية',
                'currency_code' => 'SAR',
                'currency_label_en' => 'SAR',
                'currency_label_ar' => 'ريال',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $uae = DeliveryCountry::updateOrCreate(
            ['code' => 'UAE'],
            [
                'name_en' => 'United Arab Emirates',
                'name_ar' => 'الإمارات',
                'currency_code' => 'AED',
                'currency_label_en' => 'AED',
                'currency_label_ar' => 'درهم',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $ksaCities = [
            ['name_en' => 'Riyadh', 'name_ar' => 'الرياض', 'delivery_fee' => 25, 'sort_order' => 1],
            ['name_en' => 'Jeddah', 'name_ar' => 'جدة', 'delivery_fee' => 30, 'sort_order' => 2],
            ['name_en' => 'Dammam', 'name_ar' => 'الدمام', 'delivery_fee' => 35, 'sort_order' => 3],
        ];

        foreach ($ksaCities as $city) {
            $ksa->cities()->updateOrCreate(
                ['name_en' => $city['name_en']],
                array_merge($city, ['is_active' => true])
            );
        }

        $uaeCities = [
            ['name_en' => 'Dubai', 'name_ar' => 'دبي', 'delivery_fee' => 20, 'sort_order' => 1],
            ['name_en' => 'Ajman', 'name_ar' => 'عجمان', 'delivery_fee' => 15, 'sort_order' => 2],
            ['name_en' => 'Sharjah', 'name_ar' => 'الشارقة', 'delivery_fee' => 20, 'sort_order' => 3],
            ['name_en' => 'Abu Dhabi', 'name_ar' => 'أبوظبي', 'delivery_fee' => 25, 'sort_order' => 4],
        ];

        foreach ($uaeCities as $city) {
            $uae->cities()->updateOrCreate(
                ['name_en' => $city['name_en']],
                array_merge($city, ['is_active' => true])
            );
        }
    }
}
