<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageCountryPrice extends Model
{
    protected $fillable = [
        'package_id',
        'country_code',
        'member_price',
        'guest_price',
    ];

    protected function casts(): array
    {
        return [
            'member_price' => 'decimal:2',
            'guest_price' => 'decimal:2',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
