<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedDetailSections;
use App\Models\Concerns\HasTags;
use App\Support\ItemPricingService;
use App\Support\PackagePricingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasLocalizedDetailSections, HasTags;

    protected $fillable = [
        'brand_id',
        'name',
        'name_en',
        'name_ar',
        'size_en',
        'size_ar',
        'description',
        'description_en',
        'description_ar',
        'long_description_en',
        'long_description_ar',
        'benefits_en',
        'benefits_ar',
        'ingredients_en',
        'ingredients_ar',
        'usage_instructions_en',
        'usage_instructions_ar',
        'warnings_en',
        'warnings_ar',
        'price',
        'stock',
        'reward_points',
        'image',
        'is_featured',
        'is_active',
        'status',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'reward_points' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'package_item')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function countryPrices(): HasMany
    {
        return $this->hasMany(PackageCountryPrice::class);
    }

    public function getLocalNameAttribute(): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->name_ar ?: $this->name;
        }

        return $this->name_en ?: $this->name;
    }

    public function getLocalSizeAttribute(): ?string
    {
        $countryCode = app(ItemPricingService::class)->resolveCountryCode();
        $countryPrice = $this->relationLoaded('countryPrices')
            ? $this->countryPrices->firstWhere('country_code', $countryCode)
            : $this->countryPrices()->where('country_code', $countryCode)->first();

        if ($countryPrice) {
            if (app()->getLocale() === 'ar') {
                $val = $countryPrice->size_ar ?: $countryPrice->size_en;
                if ($val) {
                    return $val;
                }
            } else {
                $val = $countryPrice->size_en ?: $countryPrice->size_ar;
                if ($val) {
                    return $val;
                }
            }
        }

        if (app()->getLocale() === 'ar') {
            return $this->size_ar ?: $this->size_en;
        }

        return $this->size_en ?: $this->size_ar;
    }

    public function getLocalDescriptionAttribute(): ?string
    {
        if (app()->getLocale() === 'ar') {
            return $this->description_ar ?: $this->description;
        }

        return $this->description_en ?: $this->description;
    }

    public function getLocalLongDescriptionAttribute(): ?string
    {
        if (app()->getLocale() === 'ar') {
            return $this->long_description_ar ?: $this->long_description_en;
        }

        return $this->long_description_en ?: $this->long_description_ar;
    }

    public function getDisplayPriceAttribute(): float
    {
        return app(PackagePricingService::class)->resolvePrice($this, auth()->user());
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'approved')
            ->where('is_active', true)
            ->whereHas('countryPrices');
    }

    public function isPubliclyVisible(): bool
    {
        return $this->status === 'approved'
            && $this->is_active
            && $this->countryPrices()->exists();
    }
}
