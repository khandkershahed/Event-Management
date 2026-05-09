<?php

namespace App\Notifications;

use App\Models\OrganizerTrustBadge;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrganizerTrustBadgeNotification extends Notification
{
    use Queueable;

    public function __construct(public OrganizerTrustBadge $badge, public string $action = 'updated', public bool $mailEnabled = false)
    {
        $this->badge->loadMissing('organizerProfile');
    }

    public function via(object $notifiable): array
    {
        return $this->mailEnabled ? ['database', 'mail'] : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Organizer trust badge ' . str_replace('_', ' ', $this->action))
            ->greeting('Hello ' . ($notifiable->name ?? 'Organizer') . ',')
            ->line('A trust badge for your organizer profile has been ' . str_replace('_', ' ', $this->action) . '.')
            ->line('Badge: ' . $this->badge->label)
            ->line('Status: ' . ucfirst($this->badge->status))
            ->action('View Organizer Profile', route('public.organizers.show', $this->badge->organizerProfile?->slug));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Trust badge ' . str_replace('_', ' ', $this->action),
            'message' => $this->badge->label . ' badge is now ' . $this->badge->status . '.',
            'badge_id' => $this->badge->id,
            'badge_key' => $this->badge->badge_key,
            'organizer_profile_id' => $this->badge->organizer_profile_id,
            'url' => route('public.organizers.show', $this->badge->organizerProfile?->slug),
        ];
    }
}
