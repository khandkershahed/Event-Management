<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizerTeamMember extends Model
{
    use HasFactory, SoftDeletes;

    public const ROLE_OWNER = 'owner';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_CHECK_IN_STAFF = 'check_in_staff';
    public const ROLE_FINANCE_VIEWER = 'finance_viewer';

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $guarded = [];

    protected $casts = [
        'invited_at' => 'datetime',
        'accepted_at' => 'datetime',
        'deactivated_at' => 'datetime',
    ];

    public static function roles(): array
    {
        return [
            self::ROLE_OWNER,
            self::ROLE_MANAGER,
            self::ROLE_CHECK_IN_STAFF,
            self::ROLE_FINANCE_VIEWER,
        ];
    }

    public static function staffRoles(): array
    {
        return [
            self::ROLE_MANAGER,
            self::ROLE_CHECK_IN_STAFF,
            self::ROLE_FINANCE_VIEWER,
        ];
    }

    public static function statuses(): array
    {
        return [self::STATUS_PENDING, self::STATUS_ACTIVE, self::STATUS_INACTIVE];
    }

    public function organizerProfile()
    {
        return $this->belongsTo(OrganizerProfile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function can(string $ability): bool
    {
        if (! $this->isActive()) {
            return false;
        }

        if ($this->role === self::ROLE_OWNER) {
            return true;
        }

        return match ($ability) {
            'team.manage' => $this->role === self::ROLE_MANAGER,
            'operations.manage' => $this->role === self::ROLE_MANAGER,
            'checkin.manage' => in_array($this->role, [self::ROLE_MANAGER, self::ROLE_CHECK_IN_STAFF], true),
            'finance.view' => $this->role === self::ROLE_FINANCE_VIEWER,
            'finance.manage' => false,
            default => false,
        };
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_OWNER => 'Owner',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_CHECK_IN_STAFF => 'Check-In Staff',
            self::ROLE_FINANCE_VIEWER => 'Finance Viewer',
            default => ucfirst(str_replace('_', ' ', $this->role)),
        };
    }
}
