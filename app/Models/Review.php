<?php

namespace App\Models;

use App\Models\Concerns\HasTags;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasTags;

    protected $fillable = [
        'type', 'avatar', 'name', 'age', 'skin_type', 'rating', 'content', 'status',
    ];
}
