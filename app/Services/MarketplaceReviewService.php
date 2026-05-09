<?php

namespace App\Services;

use App\Models\Event;
use App\Models\MarketplaceEventReview;
use App\Models\MarketplaceOrganizerRating;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MarketplaceReviewService
{
    public function eligibleOrdersFor(User $user, Event $event)
    {
        return Order::query()->where('user_id', $user->id)->where('event_id', $event->id)
            ->where(function ($query) {
                $query->whereIn('status', [Order::STATUS_COMPLETED, Order::STATUS_PAID])
                    ->orWhere('payment_status', Order::PAYMENT_PAID)
                    ->orWhereHas('tickets', function ($ticketQuery) {
                        $ticketQuery->where('status', OrderTicket::STATUS_USED)->orWhereNotNull('checked_in_at');
                    });
            })->latest()->get();
    }
    public function assertCanReview(User $user, Event $event, ?Order $order = null): void
    {
        if (! $event->organizer_profile_id) { throw ValidationException::withMessages(['event_id' => 'This event cannot be reviewed yet.']); }
        if ($order && ((int) $order->user_id !== (int) $user->id || (int) $order->event_id !== (int) $event->id)) { throw ValidationException::withMessages(['order_id' => 'Selected order is not valid for this event.']); }
        $eligibleOrders = $this->eligibleOrdersFor($user, $event);
        if ($eligibleOrders->isEmpty()) { throw ValidationException::withMessages(['event_id' => 'You can review this event only after a completed order or valid attendance.']); }
        if ($order && ! $eligibleOrders->contains('id', $order->id)) { throw ValidationException::withMessages(['order_id' => 'Selected order is not eligible for review yet.']); }
        $duplicateQuery = MarketplaceEventReview::query()->where('user_id', $user->id)->where('event_id', $event->id);
        $order ? $duplicateQuery->where('order_id', $order->id) : $duplicateQuery->whereNull('order_id');
        if ($duplicateQuery->exists()) { throw ValidationException::withMessages(['event_id' => 'You have already reviewed this event for this order.']); }
    }
    public function createReview(User $user, Event $event, array $data): MarketplaceEventReview
    {
        $order = ! empty($data['order_id']) ? Order::findOrFail($data['order_id']) : null;
        $this->assertCanReview($user, $event, $order);
        return DB::transaction(fn () => MarketplaceEventReview::create(['user_id' => $user->id, 'event_id' => $event->id, 'organizer_profile_id' => $event->organizer_profile_id, 'order_id' => $order?->id, 'rating' => (int) $data['rating'], 'title' => $data['title'] ?? null, 'body' => $data['body'] ?? null, 'status' => MarketplaceEventReview::STATUS_PENDING]));
    }
    public function refreshOrganizerRating(int $organizerProfileId): MarketplaceOrganizerRating
    {
        $query = MarketplaceEventReview::query()->where('organizer_profile_id', $organizerProfileId)->where('status', MarketplaceEventReview::STATUS_APPROVED);
        $count = (clone $query)->count();
        $average = $count > 0 ? round((float) (clone $query)->avg('rating'), 2) : 0;
        $latest = (clone $query)->latest('reviewed_at')->value('reviewed_at');
        return MarketplaceOrganizerRating::updateOrCreate(['organizer_profile_id' => $organizerProfileId], ['approved_reviews_count' => $count, 'average_rating' => $average, 'last_reviewed_at' => $latest]);
    }
}
