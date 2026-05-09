<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundTransaction extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_MANUAL = 'manual';

    protected $fillable = [
        'refund_request_id','order_id','payment_transaction_id','provider','amount','currency','status','raw_payload','processed_at',
    ];

    protected $casts = ['amount' => 'decimal:2', 'raw_payload' => 'array', 'processed_at' => 'datetime'];

    public function refundRequest() { return $this->belongsTo(RefundRequest::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function paymentTransaction() { return $this->belongsTo(PaymentTransaction::class); }
}
