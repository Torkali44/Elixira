<?php

namespace App\Support;

use App\Models\Item;
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

    public function resolveCountryCode(?string $countryCode = null): string
    {
        if ($countryCode && in_array($countryCode, ['KSA', 'UAE'], true)) {
            return $countryCode;
        }

        $sessionCountry = session('shopping_country');

        if (is_string($sessionCountry) && in_array($sessionCountry, ['KSA', 'UAE'], true)) {
            return $sessionCountry;
        }

        return self::DEFAULT_COUNTRY;
    }

    public function mapPhoneCountryCode(?string $phoneCountryCode): string
    {
        return match ($phoneCountryCode) {
            '+971' => 'UAE',
            '+966' => 'KSA',
            default => self::DEFAULT_COUNTRY,
        };
    }

    public function isMember(?User $user): bool
    {
        return $user !== null && filled($user->user_code);
    }

    public function resolvePrice(Item $item, ?User $user = null, ?string $countryCode = null): float
    {
        $countryCode = $this->resolveCountryCode($countryCode);
        $countryPrice = $item->relationLoaded('countryPrices')
            ? $item->countryPrices->firstWhere('country_code', $countryCode)
            : $item->countryPrices()->where('country_code', $countryCode)->first();

        if ($countryPrice) {
            return (float) ($this->isMember($user) ? $countryPrice->member_price : $countryPrice->guest_price);
        }

        return (float) $item->price;
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

            if (! isset($prices['member_price'], $prices['guest_price'])) {
                continue;
            }

            $item->countryPrices()->create([
                'country_code' => $countryCode,
                'member_price' => $prices['member_price'],
                'guest_price' => $prices['guest_price'],
            ]);
        }

        $firstPrice = $item->countryPrices()->orderBy('country_code')->first();
        if ($firstPrice) {
            $item->update(['price' => $firstPrice->guest_price]);
        }
    }
}
