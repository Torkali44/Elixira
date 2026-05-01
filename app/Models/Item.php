<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = [
        'category_id',
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

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function specialRequests()
    {
        return $this->hasMany(SpecialRequest::class);
    }
}
