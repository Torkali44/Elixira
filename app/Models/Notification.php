<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'title_key',
        'message_key',
        'data',
        'url',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'data' => 'array',
        ];
    }

    public function getDisplayTitleAttribute(): string
    {
        if ($this->title_key) {
            return __($this->title_key, $this->resolvedData());
        }

        return (string) $this->title;
    }

    public function getDisplayMessageAttribute(): string
    {
        if ($this->message_key) {
            return __($this->message_key, $this->resolvedData());
        }

        return (string) $this->message;
    }

    /**
     * @return array<string, string>
     */
    protected function resolvedData(): array
    {
        $data = $this->data ?? [];

        if (isset($data['status']) && is_string($data['status'])) {
            $statusKey = 'notifications.status.'.$data['status'];
            $translated = __($statusKey);
            if ($translated !== $statusKey) {
                $data['status'] = $translated;
            }
        }

        return $data;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
