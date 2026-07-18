<?php

namespace App\Support;

use App\Models\Item;
use App\Models\Package;
use App\Models\User;

class ItemPricingService
{
    public const DEFAULT_COUNTRY = 'KSA';

    /**
     * @return array<string, string>
     */
    public function supportedCountries(): array
    {
        return [
            'KSA' => __('shop.country_ksa'),
            'UAE' => __('shop.country_uae'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function countryFlags(): array
    {
        return [
            'KSA' => asset('images/sa.png'),
            'UAE' => asset('images/AE.png'),
        ];
    }

    public function countryFlag(string $countryCode): ?string
    {
        return $this->countryFlags()[$countryCode] ?? null;
    }

    public function detectUserCountry(?User $user = null): string
    {
        $sessionCountry = session('shopping_country');
        if (is_string($sessionCountry) && in_array($sessionCountry, ['KSA', 'UAE'], true)) {
            return $sessionCountry;
        }

        $user ??= auth()->user();
        if ($user && filled($user->phone)) {
            $phone = (string) $user->phone;
            if (str_contains($phone, '+971') || str_starts_with(ltrim($phone, '+'), '971')) {
                return 'UAE';
            }
            if (str_contains($phone, '+966') || str_starts_with(ltrim($phone, '+'), '966') || str_starts_with(ltrim($phone, '0'), '05')) {
                return 'KSA';
            }
        }

        return self::DEFAULT_COUNTRY;
    }

    public function resolveCountryCode(?string $countryCode = null): string
    {
        if ($countryCode && in_array($countryCode, ['KSA', 'UAE'], true)) {
            return $countryCode;
        }

        return $this->detectUserCountry();
    }

    public function mapPhoneCountryCode(?string $phoneCountryCode): string
    {
        return match ($phoneCountryCode) {
            '+971' => 'UAE',
            '+966' => 'KSA',
            default => self::DEFAULT_COUNTRY,
        };
    }

    public function currencySymbol(?string $countryCode = null): string
    {
        $countryCode = $this->resolveCountryCode($countryCode);

        return match ($countryCode) {
            'UAE' => __('shop.currency_aed'),
            'KSA' => __('shop.currency_sar'),
            default => __('shop.currency_sar'),
        };
    }

    public function formatPrice(float $amount, ?string $countryCode = null): string
    {
        return $this->formatCompactPrice($amount, $this->currencySymbol($countryCode));
    }

    public function formatCompactPrice(float $amount, string $currency): string
    {
        $formatted = abs($amount - round($amount)) < 0.001
            ? (string) (int) round($amount)
            : rtrim(rtrim(number_format($amount, 2, '.', ''), '0'), '.');

        if (app()->getLocale() === 'ar') {
            return $formatted.' '.$currency;
        }

        return $currency.' '.$formatted;
    }

    public function resolveRewardPoints(Item $item, ?string $countryCode = null, ?int $countryPriceId = null): int
    {
        if ($countryPriceId) {
            $countryPrice = $this->findVariant($item, $countryPriceId);

            if ($countryPrice && $countryPrice->reward_points !== null) {
                return (int) $countryPrice->reward_points;
            }

            return 0;
        }

        $countryCode = $this->resolveCountryCodeForItem($item, $countryCode);

        if ($countryCode === null) {
            return 0;
        }

        $countryPrice = $item->relationLoaded('countryPrices')
            ? $item->countryPrices->firstWhere('country_code', $countryCode)
            : $item->countryPrices()->where('country_code', $countryCode)->first();

        if ($countryPrice && $countryPrice->reward_points !== null) {
            return (int) $countryPrice->reward_points;
        }

        return 0;
    }

    public function resolvePackageRewardPoints(Package $package, ?string $countryCode = null, ?int $countryPriceId = null): int
    {
        $countryCode = $this->resolveCountryCodeForPackage($package, $countryCode);

        if ($countryCode === null) {
            return 0;
        }

        $packagePricing = app(PackagePricingService::class);
        $countryPrice = $countryPriceId
            ? $packagePricing->findVariant($package, $countryPriceId)
            : $packagePricing->resolveDefaultVariant($package, $countryCode);

        if ($countryPrice && $countryPrice->reward_points !== null) {
            return (int) $countryPrice->reward_points;
        }

        return 0;
    }

    public function isMember(?User $user): bool
    {
        return $user !== null && filled($user->user_code);
    }

    /**
     * @return array{
     *     country_code: string,
     *     member_price: float,
     *     guest_price: float,
     *     active_price: float,
     *     has_country_pricing: bool,
     *     has_higher_guest_price?: bool
     * }
     */
    public function getPriceBreakdown(Item $item, ?User $user = null, ?string $countryCode = null, ?int $countryPriceId = null): array
    {
        $countryCode = $this->resolveCountryCode($countryCode);

        $countryPrice = $countryPriceId
            ? $this->findVariant($item, $countryPriceId)
            : ($item->relationLoaded('countryPrices')
                ? $item->countryPrices->firstWhere('country_code', $countryCode)
                : $item->countryPrices()->where('country_code', $countryCode)->first());

        if ($countryPrice) {
            $memberPrice = (float) $countryPrice->member_price;
            $guestPrice = (float) $countryPrice->guest_price;
            $hasHigherGuestPrice = $guestPrice > $memberPrice;

            $breakdown = [
                'country_code' => $countryCode,
                'country_price_id' => $countryPrice->id,
                'member_price' => $memberPrice,
                'guest_price' => $guestPrice,
                'active_price' => $memberPrice,
                'has_country_pricing' => true,
                'has_higher_guest_price' => $hasHigherGuestPrice,
            ];
        } else {
            $fallback = (float) $item->price;

            $breakdown = [
                'country_code' => $countryCode,
                'member_price' => $fallback,
                'guest_price' => $fallback,
                'active_price' => $fallback,
                'has_country_pricing' => false,
            ];
        }

        return $breakdown;
    }

    public function resolvePrice(Item $item, ?User $user = null, ?string $countryCode = null, ?int $countryPriceId = null): float
    {
        return $this->getPriceBreakdown($item, $user, $countryCode, $countryPriceId)['active_price'];
    }

    public function resolveCountryCodeForItem(Item $item, ?string $countryCode = null): ?string
    {
        $available = $this->availableCountryCodes($item);

        if ($available === []) {
            return null;
        }

        $countryCode = $this->resolveCountryCode($countryCode);

        if (in_array($countryCode, $available, true)) {
            return $countryCode;
        }

        return $available[0];
    }

    public function resolveCountryCodeForPackage(Package $package, ?string $countryCode = null): ?string
    {
        $available = app(PackagePricingService::class)->availableCountryCodes($package);

        if ($available === []) {
            return null;
        }

        $countryCode = $this->resolveCountryCode($countryCode);

        if (in_array($countryCode, $available, true)) {
            return $countryCode;
        }

        return $available[0];
    }

    public function isAvailableInCountry(Item $item, ?string $countryCode = null): bool
    {
        $countryCode = $this->resolveCountryCode($countryCode);

        if (! $item->relationLoaded('countryPrices')) {
            return $item->countryPrices()->where('country_code', $countryCode)->exists();
        }

        return $item->countryPrices->contains('country_code', $countryCode);
    }

    /**
     * @return list<string>
     */
    public function availableCountryCodes(Item $item): array
    {
        if (! $item->relationLoaded('countryPrices')) {
            $item->load('countryPrices');
        }

        return $item->countryPrices->pluck('country_code')->unique()->values()->all();
    }

    /**
     * @return \Illuminate\Support\Collection<int, ItemCountryPrice>
     */
    public function variantsForCountry(Item $item, ?string $countryCode = null): \Illuminate\Support\Collection
    {
        $countryCode = $this->resolveCountryCodeForItem($item, $countryCode);

        if ($countryCode === null) {
            return collect();
        }

        if (! $item->relationLoaded('countryPrices')) {
            $item->load('countryPrices');
        }

        return $item->countryPrices->where('country_code', $countryCode)->values();
    }

    public function findVariant(Item $item, int $countryPriceId): ?\App\Models\ItemCountryPrice
    {
        if (! $item->relationLoaded('countryPrices')) {
            $item->load('countryPrices');
        }

        $variant = $item->countryPrices->firstWhere('id', $countryPriceId);

        if ($variant) {
            return $variant;
        }

        return $item->countryPrices()->find($countryPriceId);
    }

    public function resolveDefaultVariant(Item $item, ?string $countryCode = null): ?\App\Models\ItemCountryPrice
    {
        $variants = $this->variantsForCountry($item, $countryCode);

        if ($variants->isEmpty()) {
            return null;
        }

        foreach ($variants as $variant) {
            if ($this->resolveStock($item, $countryCode, $variant->id) > 0) {
                return $variant;
            }
        }

        return $variants->first();
    }

    public function hasStockInCountry(Item $item, ?string $countryCode = null): bool
    {
        $countryCode = $this->resolveCountryCodeForItem($item, $countryCode);

        if ($countryCode === null) {
            return false;
        }

        $variants = $this->variantsForCountry($item, $countryCode);

        if ($variants->isEmpty()) {
            return (int) $item->stock > 0;
        }

        foreach ($variants as $variant) {
            if ($this->resolveStock($item, $countryCode, $variant->id) > 0) {
                return true;
            }
        }

        return false;
    }

    public function resolveStock(Item $item, ?string $countryCode = null, ?int $countryPriceId = null): int
    {
        if ($countryPriceId) {
            $variant = $this->findVariant($item, $countryPriceId);

            if (! $variant) {
                return 0;
            }

            if ($variant->stock !== null) {
                return max(0, (int) $variant->stock);
            }

            $variants = $this->variantsForCountry($item, $variant->country_code);

            if ($variants->count() === 1) {
                return max(0, (int) $item->stock);
            }

            return 0;
        }

        $countryCode = $this->resolveCountryCodeForItem($item, $countryCode);

        if ($countryCode === null) {
            return 0;
        }

        $variants = $this->variantsForCountry($item, $countryCode);

        if ($variants->isEmpty()) {
            return (int) $item->stock;
        }

        $totalStock = 0;
        $hasCountryStock = false;

        foreach ($variants as $variant) {
            if ($variant->stock !== null) {
                $totalStock += (int) $variant->stock;
                $hasCountryStock = true;
            }
        }

        if ($hasCountryStock) {
            return $totalStock;
        }

        return (int) $item->stock;
    }

    /**
     * @param  array<string, array<string, mixed>>  $countryPrices
     */
    public function syncCountryPrices(Item $item, array $countryPrices): void
    {
        $item->countryPrices()->delete();

        $totalStock = 0;
        $hasCountryStock = false;

        foreach ($countryPrices as $countryCode => $prices) {
            if (! in_array($countryCode, ['KSA', 'UAE'], true)) {
                continue;
            }

            if (empty($prices['enabled'])) {
                continue;
            }

            $variants = $prices['variants'] ?? null;
            if (! is_array($variants)) {
                $variants = [$prices];
            }

            foreach ($variants as $variant) {
                if (! is_array($variant)) {
                    continue;
                }

                if (! isset($variant['member_price']) || $variant['member_price'] === '' || $variant['member_price'] === null) {
                    continue;
                }

                $memberPrice = $variant['member_price'];
                $guestPrice = (isset($variant['guest_price']) && $variant['guest_price'] !== '' && $variant['guest_price'] !== null)
                    ? $variant['guest_price']
                    : $memberPrice;

                $stockVal = (isset($variant['stock']) && $variant['stock'] !== '' && is_numeric($variant['stock']))
                    ? (int) $variant['stock']
                    : null;

                if ($stockVal !== null) {
                    $totalStock += $stockVal;
                    $hasCountryStock = true;
                }

                $item->countryPrices()->create([
                    'country_code' => $countryCode,
                    'size_en' => $variant['size_en'] ?? null,
                    'size_ar' => $variant['size_ar'] ?? null,
                    'member_price' => $memberPrice,
                    'guest_price' => $guestPrice,
                    'reward_points' => (isset($variant['reward_points']) && $variant['reward_points'] !== '' && is_numeric($variant['reward_points']))
                        ? (int) $variant['reward_points']
                        : null,
                    'stock' => $stockVal,
                ]);
            }
        }

        $updateData = [];
        $firstPrice = $item->countryPrices()->orderBy('country_code')->first();
        if ($firstPrice) {
            $updateData['price'] = $firstPrice->member_price;
        }

        if ($hasCountryStock) {
            $updateData['stock'] = $totalStock;
        }

        if (! empty($updateData)) {
            $item->update($updateData);
        }
    }
}
