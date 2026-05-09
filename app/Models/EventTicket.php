<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    use HasFactory;

    public const TYPE_FREE = 'free';
    public const TYPE_PAID = 'paid';
    public const TYPE_DONATION = 'donation';
    public const TYPE_INVITE_ONLY = 'invite_only';

    public const VISIBILITY_PUBLIC = 'public';
    public const VISIBILITY_HIDDEN = 'hidden';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_SOLD_OUT = 'sold_out';
    public const STATUS_ARCHIVED = 'archived';

    public const FEE_NONE = 'none';
    public const FEE_FIXED = 'fixed';
    public const FEE_PERCENT = 'percent';

    protected $guarded = [];

    protected $casts = [
        'valid_section_ids' => 'array',
        'price' => 'decimal:2',
        'platform_fee_value' => 'decimal:2',
        'organizer_absorbs_fee' => 'boolean',
        'sales_start_at' => 'datetime',
        'sales_end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public static function ticketTypes(): array
    {
        return [self::TYPE_FREE, self::TYPE_PAID, self::TYPE_DONATION, self::TYPE_INVITE_ONLY];
    }

    public static function visibilities(): array
    {
        return [self::VISIBILITY_PUBLIC, self::VISIBILITY_HIDDEN];
    }

    public static function statuses(): array
    {
        return [self::STATUS_DRAFT, self::STATUS_ACTIVE, self::STATUS_PAUSED, self::STATUS_SOLD_OUT, self::STATUS_ARCHIVED];
    }

    public static function platformFeeTypes(): array
    {
        return [self::FEE_NONE, self::FEE_FIXED, self::FEE_PERCENT];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'ticket_type_id');
    }

    public function seatLocks()
    {
        return $this->hasMany(SeatLock::class, 'ticket_type_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $statusQuery): void {
            $statusQuery->where('status', self::STATUS_ACTIVE)
                ->orWhere(function (Builder $legacyQuery): void {
                    $legacyQuery->whereNull('status')->where('is_active', true);
                });
        });
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where(function (Builder $visibilityQuery): void {
            $visibilityQuery->where('visibility', self::VISIBILITY_PUBLIC)
                ->orWhereNull('visibility');
        });
    }

    public function scopeOnSale(Builder $query): Builder
    {
        return $query->where(function (Builder $dateQuery): void {
            $dateQuery->whereNull('sales_start_at')->orWhere('sales_start_at', '<=', now());
        })->where(function (Builder $dateQuery): void {
            $dateQuery->whereNull('sales_end_at')->orWhere('sales_end_at', '>=', now());
        });
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->active()->public()->onSale()->where(function (Builder $qtyQuery): void {
            $qtyQuery->whereNull('quantity')->orWhereColumn('sold_quantity', '<', 'quantity');
        });
    }

    public function scopePubliclyAvailable(Builder $query): Builder
    {
        return $query->available();
    }

    public function isFree(): bool
    {
        return $this->ticket_type === self::TYPE_FREE || (float) $this->price <= 0;
    }

    public function isPaid(): bool
    {
        return in_array($this->ticket_type, [self::TYPE_PAID, self::TYPE_DONATION], true) && (float) $this->price > 0;
    }

    public function remainingQuantity(): int
    {
        if ($this->quantity === null) {
            return PHP_INT_MAX;
        }

        return max(0, (int) $this->quantity - (int) ($this->sold_quantity ?? 0));
    }

    public function isOnSale(): bool
    {
        $startsOk = ! $this->sales_start_at || $this->sales_start_at->lte(now());
        $endsOk = ! $this->sales_end_at || $this->sales_end_at->gte(now());
        $statusOk = $this->status === self::STATUS_ACTIVE || ($this->status === null && (bool) $this->is_active);
        $visibilityOk = $this->visibility === self::VISIBILITY_PUBLIC || $this->visibility === null;

        return $startsOk && $endsOk && $statusOk && $visibilityOk;
    }

    public function isSoldOut(): bool
    {
        return $this->remainingQuantity() <= 0 || $this->status === self::STATUS_SOLD_OUT;
    }

    public function canBeEditedSafely(): bool
    {
        return (int) ($this->sold_quantity ?? 0) === 0;
    }

    public function requiresSeatSelection(): bool
    {
        return count($this->validSectionIdsArray()) > 0;
    }

    public function validSectionIdsArray(): array
    {
        $ids = $this->valid_section_ids;

        if (is_string($ids)) {
            $decoded = json_decode($ids, true);
            $ids = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($ids)) {
            return [];
        }

        return collect($ids)->filter()->map(fn($id) => (int) $id)->unique()->values()->all();
    }

    public function formattedPrice(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }

        return ($this->currency ?: 'BDT') . ' ' . number_format((float) $this->price, 2);
    }

    public function totalFees()
    {
        $base = (float) $this->price;

        $platform = (float) ($this->platform_fee_fixed ?? 0) + ($base * (float) ($this->platform_fee_percent ?? 0) / 100);
        $processing = (float) ($this->processing_fee_fixed ?? 0) + ($base * (float) ($this->processing_fee_percent ?? 0) / 100);
        $gateway = (float) ($this->payment_gateway_fee_fixed ?? 0) + ($base * (float) ($this->payment_gateway_fee_percent ?? 0) / 100);

        if (($this->platform_fee_type ?? self::FEE_NONE) === self::FEE_FIXED) {
            $platform += (float) ($this->platform_fee_value ?? 0);
        }

        if (($this->platform_fee_type ?? self::FEE_NONE) === self::FEE_PERCENT) {
            $platform += $base * (float) ($this->platform_fee_value ?? 0) / 100;
        }

        return round($platform + $processing + $gateway, 2);
    }

    public function customerFee()
    {
        if ((bool) ($this->organizer_absorbs_fee ?? false)) {
            return 0.00;
        }

        return round($this->totalFees() * ((float) ($this->fee_customer_percent ?? 0) / 100), 2);
    }

    public function organizerFee()
    {
        if ((bool) ($this->organizer_absorbs_fee ?? false)) {
            return round($this->totalFees(), 2);
        }

        return round($this->totalFees() * ((float) ($this->fee_organizer_percent ?? 0) / 100), 2);
    }

    public function customerFinalPrice()
    {
        return round((float) $this->price + $this->customerFee(), 2);
    }

    public function organizerPayout()
    {
        return round((float) $this->price - $this->organizerFee(), 2);
    }
}
