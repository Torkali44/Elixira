<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryCountry extends Model
{
    protected $fillable = [
        'code',
        'name_en',
        'name_ar',
        'currency_code',
        'currency_label_en',
        'currency_label_ar',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function cities(): HasMany
    {
        return $this->hasMany(DeliveryCity::class)->orderBy('sort_order')->orderBy('name_en');
    }

    public function activeCities(): HasMany
    {
        return $this->cities()->where('is_active', true);
    }

    public function getLocalNameAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? ($this->name_ar ?: $this->name_en)
            : ($this->name_en ?: $this->name_ar);
    }

    public function getLocalCurrencyLabelAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar' && filled($this->currency_label_ar)) {
            return $this->currency_label_ar;
        }

        if ($locale !== 'ar' && filled($this->currency_label_en)) {
            return $this->currency_label_en;
        }

        return $this->currency_label_en
            ?: $this->currency_label_ar
            ?: $this->currency_code;
    }

    public function flagUrl(): ?string
    {
        return match ($this->code) {
            'KSA' => asset('images/sa.png'),
            'UAE' => asset('images/AE.png'),
            default => null,
        };
    }
}
