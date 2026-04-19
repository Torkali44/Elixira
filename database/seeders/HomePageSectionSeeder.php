<?php

namespace Database\Seeders;

use App\Models\HomePageSection;
use Illuminate\Database\Seeder;

class HomePageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'slug' => 'hero',
                'admin_label' => 'Hero (top banner)',
                'template' => 'hero',
                'title' => 'Welcome to Elixira',
                'subtitle' => 'Clean, potent skincare rooted in nature — curated for your daily ritual.',
                'body' => null,
                'image' => null,
                'button_label' => 'Shop the collection',
                'button_url' => '/menu',
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'slug' => 'loved_by',
                'admin_label' => 'Featured products heading',
                'template' => 'heading',
                'title' => 'Loved by many',
                'subtitle' => 'Hand-picked formulas your admin marks as featured appear here.',
                'body' => null,
                'image' => null,
                'button_label' => null,
                'button_url' => null,
                'sort_order' => 20,
                'is_active' => true,
            ],
            [
                'slug' => 'featured_grid',
                'admin_label' => 'Featured product cards',
                'template' => 'featured_products',
                'title' => null,
                'subtitle' => null,
                'body' => null,
                'image' => null,
                'button_label' => 'View all products',
                'button_url' => '/menu',
                'sort_order' => 30,
                'is_active' => true,
            ],
            [
                'slug' => 'story',
                'admin_label' => 'Brand story (text + image)',
                'template' => 'split',
                'title' => 'Our philosophy',
                'subtitle' => null,
                'body' => "At Elixira, we believe that true beauty and wellbeing stem from nature. Every formula is developed with clear ingredient lists and honest claims — so you always know what touches your skin.\n\nBuild a morning and night ritual from one trusted catalogue, and track every order from checkout to delivery.",
                'image' => null,
                'button_label' => 'Explore the shop',
                'button_url' => '/explore',
                'sort_order' => 40,
                'is_active' => true,
            ],
            [
                'slug' => 'newsletter',
                'admin_label' => 'Newsletter strip',
                'template' => 'newsletter',
                'title' => 'Unlock exclusive launches',
                'subtitle' => 'Curated tips and members-only offers. No spam — good stuff only.',
                'body' => null,
                'image' => null,
                'button_label' => 'Subscribe',
                'button_url' => '#',
                'sort_order' => 50,
                'is_active' => true,
            ],
            [
                'slug' => 'values',
                'admin_label' => 'Three value cards (JSON in body)',
                'template' => 'icon_cards',
                'title' => 'Why Elixira',
                'subtitle' => null,
                'body' => json_encode([
                    ['icon' => 'fa-leaf', 'title' => 'Natural ingredients', 'text' => 'Crafted from pure ingredients that respect your skin.'],
                    ['icon' => 'fa-flask', 'title' => 'Potent formulas', 'text' => 'Science-backed actives for visible results.'],
                    ['icon' => 'fa-hand-sparkles', 'title' => 'Daily ritual', 'text' => 'Turn your routine into a moment of care.'],
                ], JSON_THROW_ON_ERROR),
                'image' => null,
                'button_label' => null,
                'button_url' => null,
                'sort_order' => 45,
                'is_active' => true,
            ],
        ];

        foreach ($rows as $row) {
            HomePageSection::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
