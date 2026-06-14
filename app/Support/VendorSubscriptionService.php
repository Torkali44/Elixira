<?php

namespace App\Support;

use App\Models\VendorProfile;

class VendorSubscriptionService
{
    public function approvedVendorCount(): int
    {
        return VendorProfile::query()->where('status', 'approved')->count();
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
     * @return array<string, mixed>
     */
    public function subscriptionInfo(): array
    {
        return [
            'free_slots' => (int) config('vendor.free_vendor_slots', 10),
            'remaining_free_slots' => $this->freeSlotsRemaining(),
            'requires_payment' => $this->requiresPayment(),
            'amount' => (int) config('vendor.annual_subscription_amount', 180),
            'currency' => (string) config('vendor.subscription_currency', 'SAR'),
            'bank_account_number' => (string) config('vendor.bank_account_number'),
            'bank_account_holder' => (string) config('vendor.bank_account_holder'),
            'bank_name' => (string) config('vendor.bank_name'),
        ];
    }
}
