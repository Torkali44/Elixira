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
        'email',
        'status',
        'admin_read_at',
    ];

    protected function casts(): array
    {
        return [
            'admin_read_at' => 'datetime',
        ];
    }

    public function markAdminRead(): void
    {
        if ($this->admin_read_at === null) {
            $this->update(['admin_read_at' => now()]);
        }
    }

    public static function markAllAdminRead(): void
    {
        static::query()->whereNull('admin_read_at')->update(['admin_read_at' => now()]);
    }


    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offers()
    {
        return $this->hasMany(SpecialItemOffer::class);
    }
}
