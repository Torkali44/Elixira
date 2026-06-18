<?php

namespace App\Models;

use App\Models\Concerns\HasTags;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasTags;

    protected $fillable = [
        'vendor_profile_id',
        'slug',
        'name',
        'logo',
        'description',
        'instagram_link',
        'tiktok_link',
        'snapchat_link',
        'twitter_link',
        'store_link',
        'store_link_description',
        'service_countries',
        'is_active',
    ];

    protected $casts = [
        'service_countries' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Brand $brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
                $original = $brand->slug;
                $count = 1;
                while (static::where('slug', $brand->slug)->exists()) {
                    $brand->slug = $original.'-'.$count++;
                }
            }
        });
    }

    public function vendorProfile()
    {
        return $this->belongsTo(VendorProfile::class);
    }

    public function vendor()
    {
        return $this->hasOneThrough(
            User::class,
            VendorProfile::class,
            'id',          // vendor_profiles.id
            'id',          // users.id
            'vendor_profile_id', // brands.vendor_profile_id
            'user_id'      // vendor_profiles.user_id
        );
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/'.$this->logo) : null;
    }

    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function getAverageRatingAttribute(): float
    {
        $brandRatings = $this->ratings()->pluck('rating');
        $itemRatings = Rating::where('rateable_type', Item::class)
            ->whereIn('rateable_id', $this->items()->pluck('id'))
            ->pluck('rating');

        $allRatings = $brandRatings->concat($itemRatings);

        if ($allRatings->isEmpty()) {
            return 0;
        }

        $avg = $allRatings->average();

        return round($avg * 2) / 2;
    }

    public function getProductCountAttribute(): int
    {
        return $this->items()->count();
    }
}
