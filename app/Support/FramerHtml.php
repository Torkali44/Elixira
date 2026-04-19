<?php

namespace App\Support;

class FramerHtml
{
    public static function applyLaravelRoutes(string $html): string
    {
        $replacements = [
            'href="./reviewform"' => 'href="'.route('contact').'"',
            'href="./testmonials"' => 'href="'.route('home').'#testimonials"',
            'href="./"' => 'href="'.route('home').'"',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $html);
    }

    /**
     * Framer export uses ./store for Shop, Explore, and About mega-menu items (pairs of hrefs), then CTAs.
     * First 6 occurrences: Shop + Explore → shop; About → about page. Remaining ./store → shop.
     */
    public static function rewriteFramerStoreLinks(string $html): string
    {
        $shop = route('menu.index');
        $about = route('about');
        $i = 0;

        return preg_replace_callback('/href="\.\/store"/', function () use (&$i, $shop, $about) {
            $i++;
            if ($i >= 1 && $i <= 4) {
                return 'href="'.$shop.'"';
            }
            if ($i >= 5 && $i <= 6) {
                return 'href="'.$about.'"';
            }

            return 'href="'.$shop.'"';
        }, $html);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, \App\Models\Item>  $featured
     */
    public static function rewriteStaticProductLinks(string $html, \Illuminate\Support\Collection $featured): string
    {
        $paths = [
            './productdetails/ganozhi-soap',
            './productdetails/ganozhi-tooth-paste',
            './productdetails/tea-tree-cream',
            './productdetails/spirulina-powder',
        ];
        $fallback = route('menu.index');
        $ordered = $featured->values();
        foreach ($paths as $i => $path) {
            $item = $ordered->get($i);
            $url = $item ? route('menu.show', $item) : $fallback;
            $html = str_replace('href="'.$path.'"', 'href="'.$url.'"', $html);
        }

        return $html;
    }

    public static function featuredPayload(\Illuminate\Support\Collection $items): array
    {
        return $items->values()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description ? \Illuminate\Support\Str::limit(strip_tags($item->description), 120) : '',
                'price' => (float) $item->price,
                'image' => $item->image ? asset('storage/'.$item->image) : null,
                'category' => optional($item->category)->name ?? 'Skincare',
                'url' => route('menu.show', $item, absolute: true),
            ];
        })->all();
    }

    public static function injectBeforeBodyEnd(string $html, string $injection): string
    {
        if (str_contains($html, '</body>')) {
            return str_replace('</body>', $injection."\n</body>", $html);
        }

        return $html.$injection;
    }
}
