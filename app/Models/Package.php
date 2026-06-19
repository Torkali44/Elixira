<?php

namespace App\Models;

use App\Models\Concerns\HasTags;
use App\Support\PackagePricingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasTags;

    protected $fillable = [
        'brand_id',
        'name',
        'name_en',
        'name_ar',
        'description',
        'description_en',
        'description_ar',
        'long_description',
        'price',
        'stock',
        'reward_points',
        'image',
        'is_featured',
        'is_active',
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

    public function getLocalDescriptionAttribute(): ?string
    {
        if (app()->getLocale() === 'ar') {
            return $this->description_ar ?: $this->description;
        }

        return $this->description_en ?: $this->description;
    }

    public function getDisplayPriceAttribute(): float
    {
        return app(PackagePricingService::class)->resolvePrice($this, auth()->user());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereHas('countryPrices');
    }
}
