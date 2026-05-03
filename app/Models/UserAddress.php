<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = ['user_id', 'address', 'is_main'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
