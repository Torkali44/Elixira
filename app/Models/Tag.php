<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function items(): MorphToMany
    {
        return $this->morphedByMany(Item::class, 'taggable');
    }

    public function blogs(): MorphToMany
    {
        return $this->morphedByMany(Blog::class, 'taggable');
    }

    public function brands(): MorphToMany
    {
        return $this->morphedByMany(Brand::class, 'taggable');
    }

    public static function findOrCreateFromName(string $name): self
    {
        $normalizedName = trim($name);

        $slug = Str::slug($normalizedName);

        if ($slug === '') {
            $slug = 'tag-'.Str::lower(Str::random(8));
        }

        return static::query()->firstOrCreate(
            ['slug' => $slug],
            ['name' => $normalizedName]
        );
    }
}
