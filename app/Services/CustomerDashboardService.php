<?php

namespace App\Services;

use App\Models\CustomerSavedEvent;
use App\Models\MarketplaceSupportTicket;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\OrganizerFollower;
use App\Models\RefundRequest;
use App\Models\User;
use Illuminate\Support\Collection;

class CustomerDashboardService
{
    public function build(User $user): array
    {
        $ordersQuery = Order::query()->where('user_id', $user->id);

        $issuedTicketsQuery = OrderTicket::query()
            ->whereHas('order', fn ($query) => $query->where('user_id', $user->id))
            ->where('status', OrderTicket::STATUS_ISSUED);

        $upcomingTicketsQuery = (clone $issuedTicketsQuery)
            ->whereHas('event', function ($query): void {
                $query->whereDate('start_date', '>=', now()->toDateString());
            });

        $stats = [
            'total_orders' => (clone $ordersQuery)->count(),
            'paid_orders' => (clone $ordersQuery)
                ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED])
                ->where('payment_status', Order::PAYMENT_PAID)
                ->count(),
            'completed_orders' => (clone $ordersQuery)->where('status', Order::STATUS_COMPLETED)->count(),
            'pending_payment_orders' => (clone $ordersQuery)
                ->where(function ($query): void {
                    $query->where('status', Order::STATUS_PENDING_PAYMENT)
                        ->orWhereIn('payment_status', [Order::PAYMENT_UNPAID, Order::PAYMENT_PENDING, Order::PAYMENT_FAILED]);
                })
                ->count(),
            'issued_tickets' => (clone $issuedTicketsQuery)->count(),
            'upcoming_tickets' => (clone $upcomingTicketsQuery)->count(),
            'upcoming_events' => (clone $upcomingTicketsQuery)->distinct('event_id')->count('event_id'),
            'saved_events' => CustomerSavedEvent::query()->where('user_id', $user->id)->count(),
            'followed_organizers' => OrganizerFollower::query()->where('user_id', $user->id)->count(),
            'support_tickets' => MarketplaceSupportTicket::query()->where('user_id', $user->id)->count(),
            'open_support_tickets' => MarketplaceSupportTicket::query()
                ->where('user_id', $user->id)
                ->whereNotIn('status', [MarketplaceSupportTicket::STATUS_RESOLVED, MarketplaceSupportTicket::STATUS_CLOSED])
                ->count(),
            'refund_requests' => RefundRequest::query()->where('user_id', $user->id)->count(),
            'pending_refund_requests' => RefundRequest::query()
                ->where('user_id', $user->id)
                ->where('status', RefundRequest::STATUS_PENDING)
                ->count(),
            'unread_notifications' => $user->unreadNotifications()->count(),
        ];

        return [
            'stats' => $stats,
            'recentOrders' => $this->recentOrders($user),
            'recentTickets' => $this->recentTickets($user),
            'upcomingTickets' => $this->upcomingTickets($user),
            'recentSavedEvents' => $this->recentSavedEvents($user),
            'recentFollowedOrganizers' => $this->recentFollowedOrganizers($user),
            'recentSupportTickets' => $this->recentSupportTickets($user),
            'recentRefunds' => $this->recentRefunds($user),
            'recentNotifications' => $this->recentNotifications($user),
            'hasMarketplaceActivity' => collect($stats)->except(['unread_notifications'])->sum() > 0,
        ];
    }

    private function recentOrders(User $user): Collection
    {
        return Order::query()
            ->with(['event', 'tickets'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(6)
            ->get();
    }

    private function recentTickets(User $user): Collection
    {
        return OrderTicket::query()
            ->with(['event', 'eventTicket', 'seat', 'order'])
            ->whereHas('order', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->limit(6)
            ->get();
    }

    private function upcomingTickets(User $user): Collection
    {
        return OrderTicket::query()
            ->with(['event', 'eventTicket', 'seat', 'order'])
            ->where('status', OrderTicket::STATUS_ISSUED)
            ->whereHas('order', fn ($query) => $query->where('user_id', $user->id))
            ->whereHas('event', fn ($query) => $query->whereDate('start_date', '>=', now()->toDateString()))
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();
    }

    private function recentSavedEvents(User $user): Collection
    {
        return CustomerSavedEvent::query()
            ->with(['event.eventType', 'event.organizerProfile', 'event.venueRecord'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(6)
            ->get();
    }

    private function recentFollowedOrganizers(User $user): Collection
    {
        return OrganizerFollower::query()
            ->with(['organizerProfile.publicTrustBadges', 'organizerProfile.ratingSummary'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(6)
            ->get();
    }

    private function recentSupportTickets(User $user): Collection
    {
        return MarketplaceSupportTicket::query()
            ->with(['event', 'order'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
    }

    private function recentRefunds(User $user): Collection
    {
        return RefundRequest::query()
            ->with(['order.event'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
    }

    private function recentNotifications(User $user): Collection
    {
        return $user->notifications()
            ->latest()
            ->limit(6)
            ->get();
    }
}
