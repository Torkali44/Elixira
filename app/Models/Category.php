<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'name_en', 'name_ar', 'description', 'description_en', 'description_ar', 'image'];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Get localised category name — falls back to 'name' for legacy rows.
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
}

