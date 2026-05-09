<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\OrganizerPayout;
use App\Models\OrganizerTrustBadge;
use App\Models\OrganizerProfile;
use App\Models\MarketplaceEventReview;
use App\Models\MarketplaceSupportTicket;
use App\Models\RefundRequest;
use App\Models\User;
use App\Notifications\EventApprovalDecisionNotification;
use App\Notifications\MarketplaceReviewNotification;
use App\Notifications\OrderConfirmationNotification;
use App\Notifications\PayoutDecisionNotification;
use App\Notifications\RefundDecisionNotification;
use App\Notifications\SupportTicketUpdateNotification;
use App\Notifications\OrganizerTrustBadgeNotification;
use App\Notifications\TicketDeliveryNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Throwable;

class MarketplaceNotificationService
{
    public function notifyOrderPlaced(Order $order): void
    {
        $order->loadMissing('user', 'event', 'tickets.event', 'tickets.ticketType');

        if ($order->user) {
            $this->safeNotify($order->user, new OrderConfirmationNotification($order, $this->mailEnabled()));
        }
    }

    public function notifyTicketsIssuedForOrder(Order $order): void
    {
        $order->loadMissing('user', 'tickets.order', 'tickets.event', 'tickets.ticketType', 'tickets.seat.section');

        if (! $order->user) {
            return;
        }

        foreach ($order->tickets as $ticket) {
            $this->notifyTicketIssued($ticket, $order->user);
        }
    }

    public function notifyTicketIssued(OrderTicket $ticket, ?User $user = null): void
    {
        $ticket->loadMissing('order.user', 'event', 'ticketType', 'seat.section');
        $recipient = $user ?: $ticket->order?->user;

        if ($recipient) {
            $this->safeNotify($recipient, new TicketDeliveryNotification($ticket, $this->mailEnabled()));
        }
    }

    public function notifyEventApproved(Event $event): void
    {
        $this->notifyEventDecision($event, Event::STATUS_APPROVED, null);
    }

    public function notifyEventRejected(Event $event, ?string $reason = null): void
    {
        $this->notifyEventDecision($event, Event::STATUS_REJECTED, $reason);
    }

    public function notifyEventDecision(Event $event, string $decision, ?string $reason = null): void
    {
        $event->loadMissing('organizerProfile.user');
        $recipient = $event->organizerProfile?->user;

        if ($recipient) {
            $this->safeNotify($recipient, new EventApprovalDecisionNotification($event, $decision, $reason, $this->mailEnabled()));
        }
    }

    public function notifyRefundDecision(RefundRequest $refundRequest, string $decision): void
    {
        $refundRequest->loadMissing('user', 'order', 'event', 'organizerProfile.user');

        if ($refundRequest->user) {
            $this->safeNotify($refundRequest->user, new RefundDecisionNotification($refundRequest, $decision, $this->mailEnabled()));
        }

        $organizerOwner = $refundRequest->organizerProfile?->user;
        if ($organizerOwner && (! $refundRequest->user || (int) $organizerOwner->id !== (int) $refundRequest->user->id)) {
            $this->safeNotify($organizerOwner, new RefundDecisionNotification($refundRequest, $decision, $this->mailEnabled()));
        }
    }

    public function notifyPayoutDecision(OrganizerPayout $payout, string $decision): void
    {
        $payout->loadMissing('organizerProfile.user');
        $recipient = $payout->organizerProfile?->user;

        if ($recipient) {
            $this->safeNotify($recipient, new PayoutDecisionNotification($payout, $decision, $this->mailEnabled()));
        }
    }


    public function notifyReviewSubmitted(MarketplaceEventReview $review): void
    {
        $review->loadMissing('organizerProfile.user', 'event', 'user');
        if ($review->organizerProfile?->user) {
            $this->safeNotify($review->organizerProfile->user, new MarketplaceReviewNotification($review, 'submitted', $this->mailEnabled()));
        }
    }

    public function notifyReviewApproved(MarketplaceEventReview $review): void
    {
        $review->loadMissing('organizerProfile.user', 'event', 'user');
        if ($review->organizerProfile?->user) {
            $this->safeNotify($review->organizerProfile->user, new MarketplaceReviewNotification($review, 'approved', $this->mailEnabled()));
        }
    }


    public function notifyTrustBadgeUpdated(OrganizerTrustBadge $badge, string $action = 'updated'): void
    {
        $badge->loadMissing('organizerProfile.user');
        if ($badge->organizerProfile?->user) {
            $this->safeNotify($badge->organizerProfile->user, new OrganizerTrustBadgeNotification($badge, $action, $this->mailEnabled()));
        }
    }

    public function notifySupportTicketUpdate(object $recipient, MarketplaceSupportTicket $ticket, string $title, string $message, ?string $url = null): void
    {
        $ticket->loadMissing('user', 'organizerProfile.user');
        $this->safeNotify($recipient, new SupportTicketUpdateNotification($ticket, $title, $message, $url, $this->mailEnabled()));
    }

    public function notifyOrganizerOwner(?OrganizerProfile $organizerProfile, Notification $notification): void
    {
        $organizerProfile?->loadMissing('user');
        if ($organizerProfile?->user) {
            $this->safeNotify($organizerProfile->user, $notification);
        }
    }

    protected function safeNotify(object $notifiable, Notification $notification): void
    {
        try {
            $notifiable->notify($notification);
        } catch (Throwable $throwable) {
            Log::warning('Marketplace notification failed.', [
                'notifiable_type' => $notifiable::class,
                'notifiable_id' => $notifiable->id ?? null,
                'notification' => $notification::class,
                'message' => $throwable->getMessage(),
            ]);
        }
    }

    protected function mailEnabled(): bool
    {
        $mailer = (string) config('mail.default', '');
        $host = (string) config('mail.mailers.smtp.host', '');

        if (app()->runningUnitTests()) {
            return true;
        }

        if (in_array($mailer, ['log', 'array'], true)) {
            return true;
        }

        return filled($mailer) && ($mailer !== 'smtp' || filled($host));
    }
}
