<?php

namespace App\Notifications;

use App\Models\MarketplaceSupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketUpdateNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected MarketplaceSupportTicket $ticket,
        protected string $title,
        protected string $message,
        protected ?string $url = null,
        protected bool $sendMail = false
    ) {
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($this->sendMail) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->title)
            ->greeting('Hello!')
            ->line($this->message)
            ->line('Support Ticket: ' . $this->ticket->ticket_number)
            ->line('Subject: ' . $this->ticket->subject)
            ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->ticket->status)))
            ->line('Priority: ' . ucfirst($this->ticket->priority));

        if ($this->url) {
            $mail->action('Open Support Ticket', $this->url);
        }

        return $mail->line('Thank you for using our marketplace.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'support_ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'subject' => $this->ticket->subject,
            'status' => $this->ticket->status,
            'priority' => $this->ticket->priority,
            'url' => $this->url,
        ];
    }
}
