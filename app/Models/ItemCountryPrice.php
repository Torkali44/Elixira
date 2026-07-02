<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemCountryPrice extends Model
{
    protected $fillable = [
        'item_id',
        'country_code',
        'member_price',
        'guest_price',
        'reward_points',
    ];

    protected function casts(): array
    {
        return [
            'member_price' => 'decimal:2',
            'guest_price' => 'decimal:2',
            'reward_points' => 'integer',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
