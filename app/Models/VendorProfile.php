<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'brand_name',
        'brand_logo',
        'brand_description',
        'instagram_link',
        'tiktok_link',
        'snapchat_link',
        'other_links',
        'store_link',
        'store_link_description',
        'service_countries',
        'product_types',
        'payment_method',
        'verification_document',
        'status',
    ];

    protected $casts = [
        'other_links' => 'array',
        'service_countries' => 'array',
        'product_types' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
