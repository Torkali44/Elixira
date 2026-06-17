<?php

namespace App\Models;

use App\Support\ItemPricingService;
use App\Support\VendorSubscriptionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'name_en',
        'name_ar',
        'brand',
        'description',
        'description_en',
        'description_ar',
        'price',
        'stock',
        'points',
        'reward_points',
        'image',
        'is_featured',
        'long_description',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brandModel(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function vendor()
    {
        return $this->hasOneThrough(User::class, Brand::class, 'id', 'id', 'brand_id', 'vendor_profile_id')
            ->join('vendor_profiles', 'vendor_profiles.id', '=', 'brands.vendor_profile_id')
            ->select('users.*'); // Actually, since we want User, it's a bit complex. Let's just create an accessor for the Vendor user.
    }

    public function getVendorAttribute()
    {
        return $this->brandModel?->vendorProfile?->user;
    }

    public function countryPrices()
    {
        return $this->hasMany(ItemCountryPrice::class);
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function getDisplayPriceAttribute(): float
    {
        return app(ItemPricingService::class)->resolvePrice($this, auth()->user());
    }

    public function specialRequests()
    {
        return $this->hasMany(SpecialRequest::class);
    }

    public function specialOffers()
    {
        return $this->hasMany(SpecialItemOffer::class);
    }

    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function getAverageRatingAttribute(): float
    {
        $avg = $this->ratings()->avg('rating') ?: 0;

        return round($avg * 2) / 2;
    }

    /**
     * Get localised name — falls back to 'name' for legacy rows.
     */
    public function getLocalNameAttribute(): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->name_ar ?: $this->name;
        }

        return $this->name_en ?: $this->name;
    }

    /**
     * Get localised description.
     */
    public function getLocalDescriptionAttribute(): ?string
    {
        if (app()->getLocale() === 'ar') {
            return $this->description_ar ?: $this->description;
        }

        return $this->description_en ?: $this->description;
    }

    public function scopePubliclyVisible(Builder $query): Builder
    {
        $graceDays = (int) config('vendor.grace_period_days', 7);
        $graceCutoff = now()->subDays($graceDays);

        return $query->where('status', 'approved')
            ->where(function (Builder $q) use ($graceCutoff) {
                $q->whereNull('brand_id')
                    ->orWhereHas('brandModel', function (Builder $brandQuery) use ($graceCutoff) {
                        $brandQuery->where('is_active', true)
                            ->whereHas('vendorProfile', function (Builder $vpQuery) use ($graceCutoff) {
                                $vpQuery->where('status', 'approved')
                                    ->where(function (Builder $subQuery) use ($graceCutoff) {
                                        $subQuery->where('subscription_payment_status', 'not_required')
                                            ->orWhere(function (Builder $paidQuery) use ($graceCutoff) {
                                                $paidQuery->where('subscription_payment_status', 'confirmed')
                                                    ->where(function (Builder $dateQuery) use ($graceCutoff) {
                                                        $dateQuery->whereNull('subscription_ends_at')
                                                            ->orWhere('subscription_ends_at', '>', $graceCutoff);
                                                    });
                                            });
                                    });
                            });
                    });
            });
    }

    public function isPubliclyVisible(): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }

        if ($this->brand_id === null) {
            return true;
        }

        $profile = $this->brandModel?->vendorProfile;

        return app(VendorSubscriptionService::class)->productsPubliclyVisible($profile);
    }
}
