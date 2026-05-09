<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerTrustBadge extends Model
{
    use HasFactory;

    public const STATUS_APPROVED = 'approved';
    public const STATUS_HIDDEN = 'hidden';
    public const STATUS_REVOKED = 'revoked';

    public const BADGE_VERIFIED_ORGANIZER = 'verified_organizer';
    public const BADGE_PAYOUT_VERIFIED = 'payout_verified';
    public const BADGE_HIGHLY_RATED = 'highly_rated';
    public const BADGE_RESPONSIVE_SUPPORT = 'responsive_support';
    public const BADGE_TRUSTED_ORGANIZER = 'trusted_organizer';

    protected $fillable = [
        'organizer_profile_id',
        'badge_key',
        'label',
        'description',
        'status',
        'is_public',
        'created_by',
        'reviewed_by',
        'reviewed_at',
        'admin_note',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public static function availableBadges(): array
    {
        return [
            self::BADGE_VERIFIED_ORGANIZER => 'Verified Organizer',
            self::BADGE_PAYOUT_VERIFIED => 'Payout Verified',
            self::BADGE_HIGHLY_RATED => 'Highly Rated',
            self::BADGE_RESPONSIVE_SUPPORT => 'Responsive Support',
            self::BADGE_TRUSTED_ORGANIZER => 'Trusted Organizer',
        ];
    }

    public static function statuses(): array
    {
        return [self::STATUS_APPROVED, self::STATUS_HIDDEN, self::STATUS_REVOKED];
    }

    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED)->where('is_public', true);
    }

    public function organizerProfile()
    {
        return $this->belongsTo(OrganizerProfile::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }
}
