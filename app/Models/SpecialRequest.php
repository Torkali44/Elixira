<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialRequest extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'name',
        'phone',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
