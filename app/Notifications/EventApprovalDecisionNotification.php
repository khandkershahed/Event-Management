<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventApprovalDecisionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Event $event,
        public string $decision,
        public ?string $reason = null,
        protected bool $mailEnabled = true
    ) {
        $this->event->loadMissing('organizerProfile.user');
    }

    public function via(object $notifiable): array
    {
        return $this->mailEnabled ? ['database', 'mail'] : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = ucfirst(str_replace('_', ' ', $this->decision));
        $message = (new MailMessage)
            ->subject('Event ' . $statusLabel . ': ' . $this->event->name)
            ->greeting('Hello ' . ($notifiable->name ?? 'Organizer') . ',')
            ->line('Your event review status has been updated.')
            ->line('Event: ' . $this->event->name)
            ->line('Status: ' . $statusLabel);

        if ($this->reason) {
            $message->line('Reason: ' . $this->reason);
        }

        return $message
            ->action('View Event', route('organizer.events.show', $this->event))
            ->line('Please review the event from your organizer panel.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Event ' . ucfirst(str_replace('_', ' ', $this->decision)),
            'message' => 'Your event "' . $this->event->name . '" was ' . str_replace('_', ' ', $this->decision) . '.',
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'status' => $this->decision,
            'reason' => $this->reason,
            'url' => route('organizer.events.show', $this->event),
        ];
    }
}
