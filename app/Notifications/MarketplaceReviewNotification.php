<?php

namespace App\Notifications;

use App\Models\MarketplaceEventReview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MarketplaceReviewNotification extends Notification
{
    use Queueable;
    public function __construct(public MarketplaceEventReview $review, public string $eventType, public bool $sendMail = false) {}
    public function via(object $notifiable): array { return $this->sendMail ? ['database', 'mail'] : ['database']; }
    public function toMail(object $notifiable): MailMessage
    {
        $this->review->loadMissing('event', 'user');
        $subject = $this->eventType === 'approved' ? 'A marketplace review was approved' : 'A new marketplace review was submitted';
        return (new MailMessage)->subject($subject)->line($subject . '.')->line('Event: ' . ($this->review->event?->name ?? 'Event'))->line('Rating: ' . $this->review->rating . '/5')->action('View Organizer Reviews', route('organizer.reviews.index'));
    }
    public function toArray(object $notifiable): array
    {
        $this->review->loadMissing('event', 'user');
        return ['type' => 'marketplace_review_' . $this->eventType, 'title' => $this->eventType === 'approved' ? 'Review approved' : 'New review submitted', 'message' => ($this->review->event?->name ?? 'Event') . ' received a ' . $this->review->rating . '/5 review.', 'review_id' => $this->review->id, 'event_id' => $this->review->event_id, 'url' => route('organizer.reviews.index')];
    }
}
