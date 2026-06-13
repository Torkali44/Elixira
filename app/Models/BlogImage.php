<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model
{
    protected $fillable = ['blog_id', 'image', 'caption', 'sort_order'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
