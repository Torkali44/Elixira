<?php

namespace App\Support;

use App\Models\VendorProfile;

class VendorSubscriptionService
{
    public function activeVendorCount(): int
    {
        return VendorProfile::query()
            ->where('status', 'approved')
            ->whereHas('user', function ($query) {
                $query->where('is_suspended', false);
            })
            ->where(function ($query) {
                $query->whereDoesntHave('brand')
                    ->orWhereHas('brand', function ($brandQuery) {
                        $brandQuery->where('is_active', true);
                    });
            })
            ->count();
    }

    public function approvedVendorCount(): int
    {
        return $this->activeVendorCount();
    }

    public function freeSlotsRemaining(): int
    {
        return max(0, (int) config('vendor.free_vendor_slots', 10) - $this->approvedVendorCount());
    }

    public function requiresPayment(): bool
    {
        return $this->freeSlotsRemaining() <= 0;
    }

    /**
     * @return array<string, array<string, int|string>>
     */
    public function plans(): array
    {
        return config('vendor.plans', []);
    }

    public function plan(string $key): ?array
    {
        return $this->plans()[$key] ?? null;
    }

    public function gracePeriodDays(): int
    {
        return (int) config('vendor.grace_period_days', 7);
    }

    public function expiryWarningDays(): int
    {
        return (int) config('vendor.expiry_warning_days', 5);
    }

    public function isExpiringSoon(?VendorProfile $profile): bool
    {
        if ($profile === null || ! $this->hasActiveSubscription($profile)) {
            return false;
        }

        $daysRemaining = $this->daysRemaining($profile);

        if ($daysRemaining === null) {
            return false;
        }

        return $daysRemaining > 0 && $daysRemaining <= $this->expiryWarningDays();
    }

    public function hasActiveSubscription(?VendorProfile $profile): bool
    {
        if ($profile === null || $profile->status !== 'approved') {
            return false;
        }

        if ($profile->subscription_payment_status === 'not_required') {
            return true;
        }

        if ($profile->subscription_payment_status !== 'confirmed') {
            return false;
        }

        if ($profile->subscription_ends_at === null) {
            return true;
        }

        return $profile->subscription_ends_at->isFuture();
    }

    public function isInGracePeriod(?VendorProfile $profile): bool
    {
        if ($profile === null || $profile->subscription_ends_at === null) {
            return false;
        }

        if ($profile->subscription_ends_at->isFuture()) {
            return false;
        }

        return $profile->subscription_ends_at->copy()->addDays($this->gracePeriodDays())->isFuture();
    }

    public function productsPubliclyVisible(?VendorProfile $profile): bool
    {
        if ($profile === null) {
            return true;
        }

        if ($profile->status !== 'approved') {
            return false;
        }

        if ($profile->subscription_payment_status === 'not_required') {
            return true;
        }

        if ($this->hasActiveSubscription($profile)) {
            return true;
        }

        if ($this->isInGracePeriod($profile)) {
            return true;
        }

        return false;
    }

    public function activateSubscription(VendorProfile $profile, ?string $planKey = null): void
    {
        if ($profile->status !== 'approved') {
            return;
        }

        if ($profile->subscription_payment_status === 'not_required') {
            return;
        }

        if ($profile->subscription_payment_status !== 'confirmed') {
            return;
        }

        if ($profile->subscription_ends_at?->isFuture()) {
            return;
        }

        $planKey = $planKey ?: $profile->subscription_plan ?: 'yearly';
        $plan = $this->plan($planKey);

        if ($plan === null) {
            return;
        }

        $startsAt = now();
        $endsAt = $startsAt->copy()->addDays((int) $plan['days']);

        $profile->update([
            'subscription_plan' => $planKey,
            'subscription_starts_at' => $startsAt,
            'subscription_ends_at' => $endsAt,
        ]);
    }

    public function confirmPayment(VendorProfile $profile): void
    {
        $profile->update([
            'subscription_payment_status' => 'confirmed',
        ]);

        if ($profile->status === 'approved') {
            $this->activateSubscription($profile->fresh());
        }
    }

    public function isSubscriptionExpired(?VendorProfile $profile): bool
    {
        if ($profile === null || $profile->subscription_payment_status === 'not_required') {
            return false;
        }

        if ($profile->subscription_ends_at === null) {
            return false;
        }

        return $profile->subscription_ends_at->isPast() && ! $this->isInGracePeriod($profile);
    }

    public function daysRemaining(?VendorProfile $profile): ?int
    {
        if ($profile?->subscription_ends_at === null) {
            return null;
        }

        return max(0, (int) now()->diffInDays($profile->subscription_ends_at, false));
    }

    public function graceDaysRemaining(?VendorProfile $profile): ?int
    {
        if (! $this->isInGracePeriod($profile)) {
            return null;
        }

        $graceEnds = $profile->subscription_ends_at->copy()->addDays($this->gracePeriodDays());

        return max(0, (int) now()->diffInDays($graceEnds, false));
    }

    /**
     * @return array<string, mixed>
     */
    public function subscriptionInfo(): array
    {
        return [
            'free_slots' => (int) config('vendor.free_vendor_slots', 10),
            'remaining_free_slots' => $this->freeSlotsRemaining(),
            'requires_payment' => $this->requiresPayment(),
            'currency' => (string) config('vendor.subscription_currency', 'SAR'),
            'bank_account_number' => (string) config('vendor.bank_account_number'),
            'bank_account_holder' => (string) config('vendor.bank_account_holder'),
            'bank_name' => (string) config('vendor.bank_name'),
            'bank_iban' => (string) config('vendor.bank_iban'),
            'plans' => $this->plansWithLabels(),
            'grace_period_days' => $this->gracePeriodDays(),
            'expiry_warning_days' => $this->expiryWarningDays(),
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function plansWithLabels(): array
    {
        $plans = [];

        foreach ($this->plans() as $key => $plan) {
            $commonKeys = config('vendor.plan_common_features', ['store', 'products']);
            $featureKeys = array_values(array_unique(array_merge($commonKeys, $plan['features'] ?? [])));
            $features = array_map(
                fn (string $featureKey) => __('vendor.onboarding.plan_feature_'.$featureKey),
                $featureKeys
            );

            $plans[$key] = array_merge($plan, [
                'key' => $key,
                'label' => __('vendor.onboarding.plan_'.$key),
                'period' => __('vendor.onboarding.plan_period_'.$key),
                'features' => $features,
            ]);
        }

        return $plans;
    }

    /**
     * @return array<string, mixed>
     */
    public function statusForVendor(?VendorProfile $profile): array
    {
        return [
            'active' => $this->hasActiveSubscription($profile),
            'in_grace' => $this->isInGracePeriod($profile),
            'products_visible' => $this->productsPubliclyVisible($profile),
            'days_remaining' => $this->daysRemaining($profile),
            'grace_days_remaining' => $this->graceDaysRemaining($profile),
            'ends_at' => $profile?->subscription_ends_at,
            'plan' => $profile?->subscription_plan,
        ];
    }
}
