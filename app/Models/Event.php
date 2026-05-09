<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, HasSlug;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    protected $slugSourceColumn = 'name';
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date','end_date' => 'date','start_time' => 'datetime:H:i','end_time' => 'datetime:H:i',
        'purchase_deadline' => 'datetime','is_featured' => 'boolean','submitted_at' => 'datetime','approved_at' => 'datetime',
    ];

    public static function statuses(): array { return [self::STATUS_DRAFT,self::STATUS_SUBMITTED,self::STATUS_UNDER_REVIEW,self::STATUS_APPROVED,self::STATUS_REJECTED,self::STATUS_PUBLISHED,self::STATUS_CANCELLED,self::STATUS_COMPLETED]; }
    public static function organizerEditableStatuses(): array { return [self::STATUS_DRAFT, self::STATUS_REJECTED]; }
    public function scopePubliclyVisible(Builder $query): Builder { return $query->where('status', self::STATUS_PUBLISHED); }
    public function scopePendingReview(Builder $query): Builder { return $query->whereIn('status', [self::STATUS_SUBMITTED, self::STATUS_UNDER_REVIEW]); }

    public function organizerProfile() { return $this->belongsTo(OrganizerProfile::class); }
    public function approvedBy() { return $this->belongsTo(Admin::class, 'approved_by'); }
    public function eventType() { return $this->belongsTo(EventType::class); }
    public function venueModel() { return $this->belongsTo(Venue::class, 'venue_id'); }
    public function venueRecord() { return $this->belongsTo(Venue::class, 'venue_id'); }
    public function venueRelation() { return $this->belongsTo(Venue::class, 'venue_id'); }
    public function images() { return $this->hasMany(EventImage::class); }
    public function seatingPlan() { return $this->belongsTo(SeatingPlan::class); }
    public function tickets() { return $this->hasMany(EventTicket::class); }
    public function publicTickets() { return $this->hasMany(EventTicket::class)->publiclyAvailable()->orderBy('price'); }
    public function cartItems() { return $this->hasMany(CartItem::class); }
    public function seatLocks() { return $this->hasMany(SeatLock::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function orderTickets() { return $this->hasMany(OrderTicket::class); }
    public function cancellationRequests() { return $this->hasMany(EventCancellationRequest::class); }
    public function refundRequests() { return $this->hasMany(RefundRequest::class); }
    public function supportTickets() { return $this->hasMany(MarketplaceSupportTicket::class); }
    public function moderationFlags() { return $this->morphMany(MarketplaceModerationFlag::class, 'flaggable'); }
    public function reviews() { return $this->hasMany(MarketplaceEventReview::class); }
    public function approvedReviews() { return $this->hasMany(MarketplaceEventReview::class)->where('status', MarketplaceEventReview::STATUS_APPROVED); }
    public function savedByCustomers() { return $this->hasMany(CustomerSavedEvent::class); }
    public function interestedCustomers() { return $this->belongsToMany(User::class, 'customer_saved_events')->withTimestamps(); }

    public function canBeEditedByOrganizer(): bool { return in_array($this->status, self::organizerEditableStatuses(), true); }
    public function canBeSubmittedByOrganizer(): bool { return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED], true); }
    public function canBePublishedByOrganizer(): bool { return $this->status === self::STATUS_APPROVED; }
    public function isCancelled(): bool { return $this->status === self::STATUS_CANCELLED; }

    public function getDisplayPriceAttribute()
    {
        $lowestTicket = $this->relationLoaded('tickets')
            ? $this->tickets->where('visibility', EventTicket::VISIBILITY_PUBLIC)->where('status', EventTicket::STATUS_ACTIVE)->sortBy('price')->first()
            : $this->tickets()->public()->active()->orderBy('price')->first();
        if ($lowestTicket) { return $lowestTicket->formattedPrice(); }
        if (($this->price ?? 0) > 0) { return 'AUD $' . $this->price; }
        return 'Free';
    }

    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) { return $this->start_time->diffInHours($this->end_time) . 'h'; }
        return '1h';
    }

    public function getLocationLabelAttribute(): string
    {
        if ($this->venueRecord?->city) {
            return $this->venueRecord->city;
        }

        if ($this->venueModel?->city) {
            return $this->venueModel->city;
        }

        return $this->venue ?: 'Location TBA';
    }

    public function getEventFormatLabelAttribute(): string
    {
        $format = $this->event_type ?: ($this->venueRecord?->type ?: $this->venueModel?->type);

        return $format ? ucfirst(str_replace('_', ' ', (string) $format)) : 'Event';
    }

}
