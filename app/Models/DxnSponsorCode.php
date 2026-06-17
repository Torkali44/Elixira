<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DxnSponsorCode extends Model
{
    protected $fillable = [
        'code',
        'sponsor_name',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
