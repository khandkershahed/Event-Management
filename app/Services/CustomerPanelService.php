<?php

namespace App\Services;

use App\Models\CustomerSavedEvent;
use App\Models\MarketplaceEventReview;
use App\Models\MarketplaceSupportTicket;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\OrganizerFollower;
use App\Models\RefundRequest;
use App\Models\User;
use Illuminate\Support\Collection;

class CustomerPanelService
{
    public function sidebar(User $user): array
    {
        return [
            'orders' => Order::query()->where('user_id', $user->id)->count(),
            'tickets' => OrderTicket::query()->whereHas('order', fn ($query) => $query->where('user_id', $user->id))->count(),
            'unread_notifications' => $user->unreadNotifications()->count(),
            'reviews' => MarketplaceEventReview::query()->where('user_id', $user->id)->count(),
            'followed_organizers' => OrganizerFollower::query()->where('user_id', $user->id)->count(),
            'saved_events' => CustomerSavedEvent::query()->where('user_id', $user->id)->count(),
            'support_tickets' => MarketplaceSupportTicket::query()->where('user_id', $user->id)->count(),
            'open_support_tickets' => MarketplaceSupportTicket::query()
                ->where('user_id', $user->id)
                ->whereNotIn('status', [MarketplaceSupportTicket::STATUS_RESOLVED, MarketplaceSupportTicket::STATUS_CLOSED])
                ->count(),
            'refunds' => RefundRequest::query()->where('user_id', $user->id)->count(),
            'pending_refunds' => RefundRequest::query()->where('user_id', $user->id)->where('status', RefundRequest::STATUS_PENDING)->count(),
        ];
    }

    public function marketplaceActivity(User $user): array
    {
        return [
            'summary' => $this->sidebar($user),
            'orders' => Order::query()
                ->with(['event', 'tickets'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(8)
                ->get(),
            'tickets' => OrderTicket::query()
                ->with(['order', 'event', 'eventTicket', 'seat.section'])
                ->whereHas('order', fn ($query) => $query->where('user_id', $user->id))
                ->latest()
                ->limit(8)
                ->get(),
            'savedEvents' => CustomerSavedEvent::query()
                ->with(['event.organizerProfile', 'event.venueRecord', 'event.publicTickets'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(8)
                ->get(),
            'followedOrganizers' => OrganizerFollower::query()
                ->with(['organizerProfile.ratingSummary', 'organizerProfile.publicTrustBadges'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(8)
                ->get(),
            'supportTickets' => MarketplaceSupportTicket::query()
                ->with(['event', 'order', 'latestMessage'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(8)
                ->get(),
            'refunds' => RefundRequest::query()
                ->with(['order.event'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(8)
                ->get(),
            'reviews' => MarketplaceEventReview::query()
                ->with(['event', 'order'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(8)
                ->get(),
            'notifications' => $user->notifications()->latest()->limit(8)->get(),
        ];
    }

    public function profile(User $user): array
    {
        $activity = $this->marketplaceActivity($user);

        return [
            ...$activity,
            'user' => $user,
        ];
    }

    public function emptyState(Collection $items, string $message, string $routeName, string $label): array
    {
        return [
            'is_empty' => $items->isEmpty(),
            'message' => $message,
            'route' => route($routeName),
            'label' => $label,
        ];
    }
}
