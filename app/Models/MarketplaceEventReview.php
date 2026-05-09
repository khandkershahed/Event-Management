<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceEventReview extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_HIDDEN = 'hidden';

    protected $fillable = ['user_id','event_id','organizer_profile_id','order_id','rating','title','body','status','reviewed_by','reviewed_at','admin_note'];
    protected $casts = ['reviewed_at' => 'datetime'];

    public static function statuses(): array { return [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_HIDDEN]; }
    public function user() { return $this->belongsTo(User::class); }
    public function event() { return $this->belongsTo(Event::class); }
    public function organizerProfile() { return $this->belongsTo(OrganizerProfile::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function reviewedBy() { return $this->belongsTo(Admin::class, 'reviewed_by'); }
    public function scopeApproved($query) { return $query->where('status', self::STATUS_APPROVED); }
    public function approve(Admin $admin, ?string $note = null): void { $this->forceFill(['status' => self::STATUS_APPROVED, 'reviewed_by' => $admin->id, 'reviewed_at' => now(), 'admin_note' => $note])->save(); }
    public function reject(Admin $admin, ?string $note = null): void { $this->forceFill(['status' => self::STATUS_REJECTED, 'reviewed_by' => $admin->id, 'reviewed_at' => now(), 'admin_note' => $note])->save(); }
    public function hide(Admin $admin, ?string $note = null): void { $this->forceFill(['status' => self::STATUS_HIDDEN, 'reviewed_by' => $admin->id, 'reviewed_at' => now(), 'admin_note' => $note])->save(); }
}
