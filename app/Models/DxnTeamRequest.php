<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DxnTeamRequest extends Model
{
    protected $fillable = [
        'user_id',
        'application_type',
        'name',
        'gender',
        'date_of_birth',
        'id_number',
        'passport_number',
        'nationality',
        'email',
        'phone',
        'member_code',
        'sponsor_code',
        'sponsor_name',
        'team_name',
        'team_size',
        'team_members',
        'country',
        'has_heir',
        'heir_name',
        'heir_relationship',
        'heir_id_number',
        'heir_passport_number',
        'team_goal',
        'message',
        'address',
        'address_country',
        'address_city',
        'postal_code',
        'status',
        'assigned_dxn_member_code',
        'admin_notes',
        'contract_accepted_at',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'contract_accepted_at' => 'datetime',
            'date_of_birth' => 'date',
            'team_members' => 'array',
            'has_heir' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): void
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
        }
    }

    public function isLegacyTeamRequest(): bool
    {
        return filled($this->team_name) || filled($this->team_size);
    }

    public function isExistingMemberRequest(): bool
    {
        return $this->application_type === 'existing_member';
    }
}
