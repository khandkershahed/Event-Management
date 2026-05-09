<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OrganizerProfile extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_SUSPENDED = 'suspended';

    protected $guarded = [];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (OrganizerProfile $profile): void {
            if (blank($profile->slug) && filled($profile->organization_name)) {
                $profile->slug = static::uniqueSlug($profile->organization_name, $profile->id);
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'organizer';
        $slug = $base;
        $counter = 1;

        while (static::query()->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function venues()
    {
        return $this->hasMany(Venue::class);
    }

    public function seatingPlans()
    {
        return $this->hasMany(SeatingPlan::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function organizerLedgers()
    {
        return $this->hasMany(OrganizerLedger::class);
    }

    public function platformCommissionLedgers()
    {
        return $this->hasMany(PlatformCommissionLedger::class);
    }

    public function organizerPayouts()
    {
        return $this->hasMany(OrganizerPayout::class);
    }

    public function payoutMethod()
    {
        return $this->hasOne(OrganizerPayoutMethod::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(OrganizerTeamMember::class);
    }

    public function activeTeamMembers()
    {
        return $this->hasMany(OrganizerTeamMember::class)->where('status', OrganizerTeamMember::STATUS_ACTIVE);
    }

    public function supportTickets()
    {
        return $this->hasMany(MarketplaceSupportTicket::class);
    }

    public function marketplaceEventReviews()
    {
        return $this->hasMany(MarketplaceEventReview::class);
    }

    public function ratingSummary()
    {
        return $this->hasOne(MarketplaceOrganizerRating::class);
    }

    public function followers()
    {
        return $this->hasMany(OrganizerFollower::class);
    }

    public function followedByUsers()
    {
        return $this->belongsToMany(User::class, 'organizer_followers')->withTimestamps();
    }

    public function trustBadges()
    {
        return $this->hasMany(OrganizerTrustBadge::class);
    }

    public function publicTrustBadges()
    {
        return $this->hasMany(OrganizerTrustBadge::class)->publiclyVisible();
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
}
