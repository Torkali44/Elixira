<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'brand',
        'description',
        'price',
        'stock',
        'points',
        'image',
        'is_featured',
        'long_description'
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

    public function images()
    {
        return $this->hasMany(ItemImage::class);
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
}
