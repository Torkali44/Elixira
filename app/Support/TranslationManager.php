<?php

namespace App\Support;

use Illuminate\Support\Arr;

class TranslationManager
{
    /**
     * @return array<string, array{label: string, label_ar: string, file: string}>
     */
    public static function sections(): array
    {
        return [
            'app' => [
                'label' => 'Site-wide (Navigation & Common)',
                'label_ar' => 'عام (التنقل والنصوص المشتركة)',
                'file' => 'app.php',
            ],
            'admin' => [
                'label' => 'Admin Panel',
                'label_ar' => 'لوحة الإدارة',
                'file' => 'admin.php',
            ],
            'vendor' => [
                'label' => 'Vendor Panel',
                'label_ar' => 'لوحة البائع',
                'file' => 'vendor.php',
            ],
            'about' => [
                'label' => 'About Page',
                'label_ar' => 'صفحة عن الموقع',
                'file' => 'about.php',
            ],
            'contact' => [
                'label' => 'Contact Page',
                'label_ar' => 'صفحة اتصل بنا',
                'file' => 'contact.php',
            ],
            'shop' => [
                'label' => 'Shop Page',
                'label_ar' => 'صفحة المتجر',
                'file' => 'shop.php',
            ],
            'profile_page' => [
                'label' => 'Profile Page',
                'label_ar' => 'صفحة الملف الشخصي',
                'file' => 'profile_page.php',
            ],
            'home' => [
                'label' => 'Home Page',
                'label_ar' => 'الصفحة الرئيسية',
                'file' => 'home.php',
            ],
            'notifications' => [
                'label' => 'Notifications',
                'label_ar' => 'الإشعارات',
                'file' => 'notifications.php',
            ],
            'track' => [
                'label' => 'Order Tracking Page',
                'label_ar' => 'صفحة تتبع الطلب',
                'file' => 'track.php',
            ],
            'brands_page' => [
                'label' => 'Brands Page',
                'label_ar' => 'صفحة العلامات التجارية',
                'file' => 'brands_page.php',
            ],
            'testimonials_page' => [
                'label' => 'Testimonials Page',
                'label_ar' => 'صفحة التعليقات',
                'file' => 'testimonials_page.php',
            ],
            'blogs_page' => [
                'label' => 'Blogs Page',
                'label_ar' => 'صفحة المدونة',
                'file' => 'blogs_page.php',
            ],
        ];
    }

    public static function loadSection(string $section, string $locale): array
    {
        $sections = self::sections();

        if (! isset($sections[$section]) || ! in_array($locale, ['en', 'ar'], true)) {
            return [];
        }

        $path = resource_path("lang/{$locale}/{$sections[$section]['file']}");

        return file_exists($path) ? require $path : [];
    }

    /**
     * @param  array<string, mixed>  $translations
     */
    public static function saveSection(string $section, string $locale, array $translations): void
    {
        $sections = self::sections();

        if (! isset($sections[$section]) || ! in_array($locale, ['en', 'ar'], true)) {
            return;
        }

        $nested = Arr::undot($translations);
        $path = resource_path("lang/{$locale}/{$sections[$section]['file']}");
        $content = "<?php\n\nreturn ".var_export($nested, true).";\n";

        file_put_contents($path, $content);
    }

    /**
     * @param  array<string, mixed>  $array
     * @return array<string, string>
     */
    public static function flatten(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? (string) $key : "{$prefix}.{$key}";

            if (is_array($value)) {
                $result = array_merge($result, self::flatten($value, $newKey));
            } else {
                $result[$newKey] = (string) $value;
            }
        }

        return $result;
    }
}
