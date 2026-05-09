<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventCancellationRequest;
use App\Models\Order;
use App\Models\RefundRequest;
use Illuminate\Database\Seeder;

class RefundCancellationSeeder extends Seeder
{
    public function run(): void
    {
        $paidOrder = Order::query()
            ->where('payment_status', Order::PAYMENT_PAID)
            ->whereIn('status', [Order::STATUS_COMPLETED, Order::STATUS_PAID])
            ->with('event')
            ->first();

        if ($paidOrder) {
            RefundRequest::query()->firstOrCreate(
                [
                    'order_id' => $paidOrder->id,
                    'status' => RefundRequest::STATUS_PENDING,
                ],
                [
                    'user_id' => $paidOrder->user_id,
                    'organizer_profile_id' => $paidOrder->event?->organizer_profile_id,
                    'event_id' => $paidOrder->event_id,
                    'requested_by_type' => 'seeder',
                    'requested_by_id' => $paidOrder->user_id,
                    'reason' => 'Demo customer refund request.',
                    'amount' => $paidOrder->total,
                    'currency' => $paidOrder->currency ?: 'BDT',
                ]
            );
        }

        $event = Event::query()
            ->whereNotNull('organizer_profile_id')
            ->whereIn('status', [Event::STATUS_PUBLISHED, Event::STATUS_APPROVED])
            ->first();

        if ($event) {
            EventCancellationRequest::query()->firstOrCreate(
                [
                    'event_id' => $event->id,
                    'status' => EventCancellationRequest::STATUS_PENDING,
                ],
                [
                    'organizer_profile_id' => $event->organizer_profile_id,
                    'requested_by' => $event->organizerProfile?->user_id,
                    'reason' => 'Demo organizer cancellation request.',
                ]
            );
        }
    }
}
