<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialItemOffer extends Model
{
    protected $fillable = [
        'item_id',
        'special_request_id',
        'user_id',
        'target_phone',
        'target_email',
        'quantity',
        'used_quantity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(SpecialRequest::class, 'special_request_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function remainingQuantity(): int
    {
        return max(0, (int) $this->quantity - (int) $this->used_quantity);
    }
}
