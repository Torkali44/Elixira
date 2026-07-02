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
        $formattedAmount = number_format($amount, 2);
        $currency = $this->currencySymbol($countryCode);

        if (app()->getLocale() === 'ar') {
            return $formattedAmount.' '.$currency;
        }

        return $currency.' '.$formattedAmount;
    }

    public function resolveRewardPoints(Item $item, ?string $countryCode = null): int
    {
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

    public function resolvePackageRewardPoints(Package $package, ?string $countryCode = null): int
    {
        $countryCode = $this->resolveCountryCodeForPackage($package, $countryCode);

        if ($countryCode === null) {
            return 0;
        }

        $countryPrice = $package->relationLoaded('countryPrices')
            ? $package->countryPrices->firstWhere('country_code', $countryCode)
            : $package->countryPrices()->where('country_code', $countryCode)->first();

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
    public function getPriceBreakdown(Item $item, ?User $user = null, ?string $countryCode = null): array
    {
        $countryCode = $this->resolveCountryCode($countryCode);
        $countryPrice = $item->relationLoaded('countryPrices')
            ? $item->countryPrices->firstWhere('country_code', $countryCode)
            : $item->countryPrices()->where('country_code', $countryCode)->first();

        if ($countryPrice) {
            $memberPrice = (float) $countryPrice->member_price;
            $guestPrice = (float) $countryPrice->guest_price;
            $hasHigherGuestPrice = $guestPrice > $memberPrice;

            $breakdown = [
                'country_code' => $countryCode,
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

    public function resolvePrice(Item $item, ?User $user = null, ?string $countryCode = null): float
    {
        return $this->getPriceBreakdown($item, $user, $countryCode)['active_price'];
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

        return $item->countryPrices->pluck('country_code')->all();
    }

    /**
     * @param  array<string, array{member_price: mixed, guest_price: mixed}>  $countryPrices
     */
    public function syncCountryPrices(Item $item, array $countryPrices): void
    {
        $item->countryPrices()->delete();

        foreach ($countryPrices as $countryCode => $prices) {
            if (! in_array($countryCode, ['KSA', 'UAE'], true)) {
                continue;
            }

            if (empty($prices['enabled'])) {
                continue;
            }

            if (! isset($prices['member_price']) || $prices['member_price'] === '' || $prices['member_price'] === null) {
                continue;
            }

            $memberPrice = $prices['member_price'];
            $guestPrice = (isset($prices['guest_price']) && $prices['guest_price'] !== '' && $prices['guest_price'] !== null)
                ? $prices['guest_price']
                : $memberPrice;

            $item->countryPrices()->create([
                'country_code' => $countryCode,
                'member_price' => $memberPrice,
                'guest_price' => $guestPrice,
                'reward_points' => (isset($prices['reward_points']) && $prices['reward_points'] !== '' && is_numeric($prices['reward_points']))
                    ? (int) $prices['reward_points']
                    : null,
            ]);
        }

        $firstPrice = $item->countryPrices()->orderBy('country_code')->first();
        if ($firstPrice) {
            $item->update(['price' => $firstPrice->member_price]);
        }
    }
}
