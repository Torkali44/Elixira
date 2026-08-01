<?php

namespace App\Models;

use App\Support\ItemPricingService;
use App\Support\OrderReferenceGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'reference',
        'user_id',
        'user_code',
        'customer_name',
        'customer_phone',
        'address',
        'delivery_city_id',
        'country_code',
        'delivery_fee',
        'shared_shipping_order_id',
        'subtotal_amount',
        'total_amount',
        'status',
        'notes',
        'admin_read_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'subtotal_amount' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'admin_read_at' => 'datetime',
        ];
    }

    public function markAdminRead(): void
    {
        if ($this->admin_read_at === null) {
            $this->update(['admin_read_at' => now()]);
        }
    }

    public static function markAllAdminRead(): void
    {
        static::query()->whereNull('admin_read_at')->update(['admin_read_at' => now()]);
    }


    public function deliveryCity(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DeliveryCity::class);
    }

    public function sharedShippingOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'shared_shipping_order_id');
    }

    public function sharedShippingChildren(): HasMany
    {
        return $this->hasMany(self::class, 'shared_shipping_order_id');
    }

    public function usesSharedShipping(): bool
    {
        return $this->shared_shipping_order_id !== null;
    }

    public function canShareShipping(): bool
    {
        return $this->status === 'pending';
    }

    public function deliveryCountryCode(): ?string
    {
        if (! empty($this->country_code)) {
            return $this->country_code;
        }

        $this->loadMissing('deliveryCity.country');

        if ($this->deliveryCity?->country) {
            return $this->deliveryCity->country->code;
        }

        $phone = (string) $this->customer_phone;
        $digits = preg_replace('/[^\d]/', '', $phone);

        if (str_starts_with($digits, '971')) {
            return 'UAE';
        }

        if (str_starts_with($digits, '966')) {
            return 'KSA';
        }

        return app(ItemPricingService::class)->mapPhoneCountryCode(null);
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            if (empty($order->reference)) {
                $order->reference = OrderReferenceGenerator::generate();
            }
        });
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
