<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'type', 'avatar', 'name', 'age', 'skin_type', 'rating', 'content', 'status'
    ];
}
