<?php

namespace App\Models;

use App\Support\EmailVerificationOtpService;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'gender',
        'phone',
        'user_code',
        'is_dxn_verified',
        'dxn_member_code',
        'dxn_tag_color',
        'dxn_badge_image',
        'total_points',
        'avatar',
        'avatar_option_id',
        'password',
        'role',
        'theme',
        'locale',
        'cart_data',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verification_code_expires_at' => 'datetime',
            'is_suspended' => 'boolean',
            'is_dxn_verified' => 'boolean',
            'dxn_verified_at' => 'datetime',
            'password' => 'hashed',
            'cart_data' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function dxnTeamRequests()
    {
        return $this->hasMany(DxnTeamRequest::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function avatarOption(): BelongsTo
    {
        return $this->belongsTo(AvatarOption::class);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatarOption && $this->avatarOption->is_active) {
            return $this->avatarOption->image_url;
        }

        return $this->avatar ? asset('storage/'.$this->avatar) : null;
    }

    public function getAvatarInitialsAttribute(): string
    {
        $parts = Str::of((string) $this->name)
            ->trim()
            ->explode(' ')
            ->filter()
            ->take(2);

        $initials = $parts
            ->map(fn (string $part) => Str::upper(Str::substr($part, 0, 1)))
            ->implode('');

        return $initials !== '' ? $initials : 'U';
    }

    public function vendorProfile()
    {
        return $this->hasOne(VendorProfile::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false)->latest();
    }

    public function pointsTransactions()
    {
        return $this->hasMany(UserPointsTransaction::class)->latest();
    }

    public function getDxnBadgeUrlAttribute(): ?string
    {
        return $this->dxn_badge_image ? asset('storage/'.$this->dxn_badge_image) : null;
    }

    public function resolvedDxnTagColor(): string
    {
        return $this->attributes['dxn_tag_color'] ?? (string) config('dxn.default_tag_colors.primary', '#00ff88');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function sendEmailVerificationNotification(): void
    {
        app(EmailVerificationOtpService::class)->send($this);
    }
}
