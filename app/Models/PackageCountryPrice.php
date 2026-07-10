<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageCountryPrice extends Model
{
    protected $fillable = [
        'package_id',
        'country_code',
        'size_en',
        'size_ar',
        'member_price',
        'guest_price',
        'reward_points',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'member_price' => 'decimal:2',
            'guest_price' => 'decimal:2',
            'reward_points' => 'integer',
            'stock' => 'integer',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function getLocalSizeAttribute(): ?string
    {
        if (app()->getLocale() === 'ar') {
            return $this->size_ar ?: $this->size_en;
        }

        return $this->size_en ?: $this->size_ar;
    }
}
