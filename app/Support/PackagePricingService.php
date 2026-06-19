<?php

namespace App\Support;

use App\Models\Package;
use App\Models\User;

class PackagePricingService
{
    public function getPriceBreakdown(Package $package, ?User $user = null, ?string $countryCode = null): array
    {
        $pricing = app(ItemPricingService::class);
        $countryCode = $pricing->resolveCountryCode($countryCode);

        $countryPrice = $package->relationLoaded('countryPrices')
            ? $package->countryPrices->firstWhere('country_code', $countryCode)
            : $package->countryPrices()->where('country_code', $countryCode)->first();

        if ($countryPrice) {
            $memberPrice = (float) $countryPrice->member_price;
            $guestPrice = (float) $countryPrice->guest_price;

            return [
                'country_code' => $countryCode,
                'member_price' => $memberPrice,
                'guest_price' => $guestPrice,
                'active_price' => $pricing->isMember($user) ? $memberPrice : $guestPrice,
                'has_country_pricing' => true,
            ];
        }

        $fallback = (float) $package->price;

        return [
            'country_code' => $countryCode,
            'member_price' => $fallback,
            'guest_price' => $fallback,
            'active_price' => $fallback,
            'has_country_pricing' => false,
        ];
    }

    public function resolvePrice(Package $package, ?User $user = null, ?string $countryCode = null): float
    {
        return $this->getPriceBreakdown($package, $user, $countryCode)['active_price'];
    }

    /**
     * @return list<string>
     */
    public function availableCountryCodes(Package $package): array
    {
        if (! $package->relationLoaded('countryPrices')) {
            $package->load('countryPrices');
        }

        return $package->countryPrices->pluck('country_code')->all();
    }

    /**
     * @param  array<string, array{member_price: mixed, guest_price: mixed}>  $countryPrices
     */
    public function syncCountryPrices(Package $package, array $countryPrices): void
    {
        $package->countryPrices()->delete();

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

            $package->countryPrices()->create([
                'country_code' => $countryCode,
                'member_price' => $memberPrice,
                'guest_price' => $guestPrice,
            ]);
        }

        $firstPrice = $package->countryPrices()->orderBy('country_code')->first();
        if ($firstPrice) {
            $package->update(['price' => $firstPrice->member_price]);
        }
    }
}
