<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@elixira.com'],
            [
                'name' => 'Elixira Admin',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
            ]
        );

        $categories = [
            ['name' => 'Cleansers', 'description' => 'Face washes, balms, and micellar waters for a fresh canvas.'],
            ['name' => 'Moisturizers', 'description' => 'Creams, gels, and lotions to lock in hydration.'],
            ['name' => 'Serums & Treatments', 'description' => 'Targeted actives for brightening, renewal, and balance.'],
            ['name' => 'Sun care', 'description' => 'SPF and daily protection for healthy-looking skin.'],
            ['name' => 'Masks & exfoliants', 'description' => 'Weekly resets—clay, enzyme, and gentle scrubs.'],
            ['name' => 'Body care', 'description' => 'Lotions, oils, and essentials from neck to toe.'],
        ];

        foreach ($categories as $row) {
            Category::firstOrCreate(
                ['name' => $row['name']],
                ['description' => $row['description'], 'image' => null]
            );
        }

        $this->call(HomePageSectionSeeder::class);
    }
}
