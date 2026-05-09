<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAID = 'paid';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED = 'refunded';

    public const PAYMENT_UNPAID = 'unpaid';
    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_FAILED = 'failed';
    public const PAYMENT_REFUNDED = 'refunded';

    protected $fillable = [
        'user_id',
        'session_id',
        'event_id',
        'order_number',
        'subtotal',
        'discount_total',
        'fee_total',
        'total',
        'currency',
        'status',
        'payment_status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'expires_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'fee_total' => 'decimal:2',
        'total' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [self::STATUS_DRAFT, self::STATUS_PENDING_PAYMENT, self::STATUS_PAID, self::STATUS_COMPLETED, self::STATUS_CANCELLED, self::STATUS_REFUNDED];
    }

    public static function paymentStatuses(): array
    {
        return [self::PAYMENT_UNPAID, self::PAYMENT_PENDING, self::PAYMENT_PAID, self::PAYMENT_FAILED, self::PAYMENT_REFUNDED];
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function tickets()
    {
        return $this->hasMany(OrderTicket::class);
    }
    public function organizerLedger()
    {
        return $this->hasOne(OrganizerLedger::class);
    }
    public function platformCommissionLedger()
    {
        return $this->hasOne(PlatformCommissionLedger::class);
    }
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }
    public function latestPaymentTransaction()
    {
        return $this->hasOne(PaymentTransaction::class)->latestOfMany();
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class);
    }
    public function refundTransactions()
    {
        return $this->hasMany(RefundTransaction::class);
    }
    public function supportTickets()
    {
        return $this->hasMany(MarketplaceSupportTicket::class);
    }

    public function marketplaceEventReviews()
    {
        return $this->hasMany(MarketplaceEventReview::class);
    }

    public function isFreeOrder(): bool
    {
        return (float) $this->total <= 0;
    }
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
    public function isPaidForRefund(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID && in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_PAID], true);
    }
    public function isRefundedOrCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_REFUNDED, self::STATUS_CANCELLED], true) || $this->payment_status === self::PAYMENT_REFUNDED;
    }

    public function requiresPayment(): bool
    {
        return ! $this->isFreeOrder() && $this->status === self::STATUS_PENDING_PAYMENT && in_array($this->payment_status, [self::PAYMENT_UNPAID, self::PAYMENT_PENDING, self::PAYMENT_FAILED], true);
    }

    public function markPaymentPending(): void
    {
        $this->forceFill(['status' => self::STATUS_PENDING_PAYMENT, 'payment_status' => self::PAYMENT_PENDING])->save();
    }
    public function markPaid(): void
    {
        $this->forceFill(['status' => self::STATUS_COMPLETED, 'payment_status' => self::PAYMENT_PAID, 'expires_at' => null])->save();
    }
    public function markPaymentFailed(): void
    {
        $this->forceFill(['status' => self::STATUS_PENDING_PAYMENT, 'payment_status' => self::PAYMENT_FAILED])->save();
    }
    public function markRefunded(): void
    {
        $this->forceFill(['status' => self::STATUS_REFUNDED, 'payment_status' => self::PAYMENT_REFUNDED, 'expires_at' => null])->save();
    }
    public function markCancelled(): void
    {
        $this->forceFill(['status' => self::STATUS_CANCELLED, 'expires_at' => null])->save();
    }
    // orderItem

}
