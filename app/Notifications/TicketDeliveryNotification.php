<?php

namespace App\Notifications;

use App\Models\OrderTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketDeliveryNotification extends Notification
{
    use Queueable;

    public function __construct(
        public OrderTicket $ticket,
        protected bool $mailEnabled = true
    ) {
        $this->ticket->loadMissing('order', 'event', 'ticketType', 'seat.section');
    }

    public function via(object $notifiable): array
    {
        return $this->mailEnabled ? ['database', 'mail'] : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $eventName = $this->ticket->event?->name ?? 'your event';

        return (new MailMessage)
            ->subject('Your ticket: ' . $eventName)
            ->greeting('Hello ' . ($notifiable->name ?? $this->ticket->attendee_name ?? 'Customer') . ',')
            ->line('Your ticket has been issued successfully.')
            ->line('Event: ' . $eventName)
            ->line('Ticket Code: ' . $this->ticket->ticket_code)
            ->line('Ticket Type: ' . ($this->ticket->ticketType?->name ?? 'Ticket'))
            ->line('QR Payload: ' . $this->ticket->qr_payload)
            ->action('View Ticket', route('user.tickets.show', $this->ticket))
            ->line('Please keep this ticket safe for check-in.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Ticket delivered',
            'message' => 'Your ticket ' . $this->ticket->ticket_code . ' has been issued.',
            'order_id' => $this->ticket->order_id,
            'order_number' => $this->ticket->order?->order_number,
            'ticket_id' => $this->ticket->id,
            'ticket_code' => $this->ticket->ticket_code,
            'event_id' => $this->ticket->event_id,
            'event_name' => $this->ticket->event?->name,
            'ticket_type' => $this->ticket->ticketType?->name,
            'qr_payload' => $this->ticket->qr_payload,
            'url' => route('user.tickets.show', $this->ticket),
        ];
    }
}
