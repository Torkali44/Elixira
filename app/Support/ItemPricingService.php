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
     *     discount_percent?: int
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
                'active_price' => $this->isMember($user) ? $memberPrice : ($hasHigherGuestPrice ? $guestPrice : $memberPrice),
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

        return $this->applyDiscount($item, $breakdown, $user);
    }

    /**
     * @param  array<string, mixed>  $breakdown
     * @return array<string, mixed>
     */
    private function applyDiscount(Item $item, array $breakdown, ?User $user): array
    {
        $discountPercent = (int) ($item->discount_percent ?? 0);

        if ($discountPercent <= 0 || $discountPercent > 100) {
            return $breakdown;
        }

        $factor = 1 - ($discountPercent / 100);
        $breakdown['original_member_price'] = $breakdown['member_price'];
        $breakdown['original_guest_price'] = $breakdown['guest_price'];
        $breakdown['member_price'] = round((float) $breakdown['member_price'] * $factor, 2);
        $breakdown['guest_price'] = round((float) $breakdown['guest_price'] * $factor, 2);
        $breakdown['active_price'] = $this->isMember($user) ? $breakdown['member_price'] : $breakdown['guest_price'];
        $breakdown['discount_percent'] = $discountPercent;

        return $breakdown;
    }

    public function resolvePrice(Item $item, ?User $user = null, ?string $countryCode = null): float
    {
        return $this->getPriceBreakdown($item, $user, $countryCode)['active_price'];
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
            ]);
        }

        $firstPrice = $item->countryPrices()->orderBy('country_code')->first();
        if ($firstPrice) {
            $item->update(['price' => $firstPrice->member_price]);
        }
    }
}
