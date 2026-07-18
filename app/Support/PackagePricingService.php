<?php

namespace App\Support;

use App\Models\Package;
use App\Models\PackageCountryPrice;
use App\Models\User;
use Illuminate\Support\Collection;

class PackagePricingService
{
    /**
     * @return array{
     *     country_code: string,
     *     member_price: float,
     *     guest_price: float,
     *     active_price: float,
     *     has_country_pricing: bool,
     *     has_higher_guest_price: bool,
     *     country_price_id?: int
     * }
     */
    public function getPriceBreakdown(Package $package, ?User $user = null, ?string $countryCode = null, ?int $countryPriceId = null): array
    {
        $pricing = app(ItemPricingService::class);
        $countryCode = $pricing->resolveCountryCodeForPackage($package, $countryCode)
            ?? $pricing->resolveCountryCode($countryCode);

        $countryPrice = $countryPriceId
            ? $this->findVariant($package, $countryPriceId)
            : $this->resolveDefaultVariant($package, $countryCode);

        if ($countryPrice) {
            return $this->buildCountryPriceBreakdown(
                $countryCode,
                (float) $countryPrice->member_price,
                (float) $countryPrice->guest_price,
                true,
                $countryPrice->id
            );
        }

        $fallback = (float) $package->price;

        return $this->buildCountryPriceBreakdown($countryCode, $fallback, $fallback, false);
    }

    /**
     * @return array{
     *     country_code: string,
     *     member_price: float,
     *     guest_price: float,
     *     active_price: float,
     *     has_country_pricing: bool,
     *     has_higher_guest_price: bool,
     *     country_price_id?: int
     * }
     */
    private function buildCountryPriceBreakdown(
        string $countryCode,
        float $memberPrice,
        float $guestPrice,
        bool $hasCountryPricing = true,
        ?int $countryPriceId = null
    ): array {
        if ($guestPrice <= 0) {
            $guestPrice = $memberPrice;
        }

        $displayMember = min($memberPrice, $guestPrice);
        $displayGuest = max($memberPrice, $guestPrice);

        $breakdown = [
            'country_code' => $countryCode,
            'member_price' => $displayMember,
            'guest_price' => $displayGuest,
            'active_price' => $displayMember,
            'has_country_pricing' => $hasCountryPricing,
            'has_higher_guest_price' => $displayGuest > $displayMember,
        ];

        if ($countryPriceId !== null) {
            $breakdown['country_price_id'] = $countryPriceId;
        }

        return $breakdown;
    }

    public function resolvePrice(Package $package, ?User $user = null, ?string $countryCode = null, ?int $countryPriceId = null): float
    {
        return $this->getPriceBreakdown($package, $user, $countryCode, $countryPriceId)['active_price'];
    }

    /**
     * @return list<string>
     */
    public function availableCountryCodes(Package $package): array
    {
        if (! $package->relationLoaded('countryPrices')) {
            $package->load('countryPrices');
        }

        return $package->countryPrices
            ->pluck('country_code')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, PackageCountryPrice>
     */
    public function variantsForCountry(Package $package, ?string $countryCode = null): Collection
    {
        $pricing = app(ItemPricingService::class);
        $countryCode = $pricing->resolveCountryCodeForPackage($package, $countryCode);

        if ($countryCode === null) {
            return collect();
        }

        if (! $package->relationLoaded('countryPrices')) {
            $package->load('countryPrices');
        }

        return $package->countryPrices->where('country_code', $countryCode)->values();
    }

    public function findVariant(Package $package, int $countryPriceId): ?PackageCountryPrice
    {
        if (! $package->relationLoaded('countryPrices')) {
            $package->load('countryPrices');
        }

        $variant = $package->countryPrices->firstWhere('id', $countryPriceId);

        if ($variant) {
            return $variant;
        }

        return $package->countryPrices()->find($countryPriceId);
    }

    public function resolveDefaultVariant(Package $package, ?string $countryCode = null): ?PackageCountryPrice
    {
        $variants = $this->variantsForCountry($package, $countryCode);

        if ($variants->isEmpty()) {
            return null;
        }

        foreach ($variants as $variant) {
            if ($this->resolveStock($package, $countryCode, $variant->id) > 0) {
                return $variant;
            }
        }

        return $variants->first();
    }

    public function resolveStock(Package $package, ?string $countryCode = null, ?int $countryPriceId = null): int
    {
        $pricing = app(ItemPricingService::class);
        $countryCode = $pricing->resolveCountryCodeForPackage($package, $countryCode);

        if ($countryCode === null) {
            return 0;
        }

        if ($countryPriceId) {
            $variant = $this->findVariant($package, $countryPriceId);

            if ($variant && $variant->country_code === $countryCode) {
                if ($variant->stock !== null) {
                    return (int) $variant->stock;
                }

                return (int) $package->stock;
            }
        }

        $variants = $this->variantsForCountry($package, $countryCode);

        if ($variants->isNotEmpty()) {
            $total = 0;
            $hasVariantStock = false;

            foreach ($variants as $variant) {
                if ($variant->stock !== null) {
                    $total += (int) $variant->stock;
                    $hasVariantStock = true;
                }
            }

            if ($hasVariantStock) {
                return $total;
            }
        }

        return (int) $package->stock;
    }

    /**
     * @param  array<string, array<string, mixed>>  $countryPrices
     */
    public function syncCountryPrices(Package $package, array $countryPrices): void
    {
        $package->countryPrices()->delete();

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

                $package->countryPrices()->create([
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
        $firstPrice = $package->countryPrices()->orderBy('country_code')->first();
        if ($firstPrice) {
            $updateData['price'] = $firstPrice->member_price;
        }

        if ($hasCountryStock) {
            $updateData['stock'] = $totalStock;
        }

        if (! empty($updateData)) {
            $package->update($updateData);
        }
    }
}
