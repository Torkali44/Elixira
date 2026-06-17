<?php

namespace Database\Seeders;

use App\Models\DxnSponsorCode;
use Illuminate\Database\Seeder;

class DxnSponsorCodeSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [
            ['code' => 'DXN200', 'sponsor_name' => 'Elixira Admin', 'sort_order' => 1],
            ['code' => 'DXN100', 'sponsor_name' => 'Primary Sponsor', 'sort_order' => 2],
        ];

        foreach ($codes as $entry) {
            DxnSponsorCode::query()->updateOrCreate(
                ['code' => $entry['code']],
                [
                    'sponsor_name' => $entry['sponsor_name'],
                    'sort_order' => $entry['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
