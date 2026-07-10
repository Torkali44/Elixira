<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryCity extends Model
{
    protected $fillable = [
        'delivery_country_id',
        'name_en',
        'name_ar',
        'delivery_fee',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'delivery_fee' => 'decimal:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(DeliveryCountry::class, 'delivery_country_id');
    }

    public function getLocalNameAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? ($this->name_ar ?: $this->name_en)
            : ($this->name_en ?: $this->name_ar);
    }
}
