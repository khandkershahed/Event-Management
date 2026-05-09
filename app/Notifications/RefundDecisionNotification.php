<?php

namespace App\Notifications;

use App\Models\RefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundDecisionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public RefundRequest $refundRequest,
        public string $decision,
        protected bool $mailEnabled = true
    ) {
        $this->refundRequest->loadMissing('order', 'event', 'organizerProfile.user');
    }

    public function via(object $notifiable): array
    {
        return $this->mailEnabled ? ['database', 'mail'] : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = ucfirst(str_replace('_', ' ', $this->decision));

        return (new MailMessage)
            ->subject('Refund ' . $statusLabel . ': ' . ($this->refundRequest->order?->order_number ?? 'Order'))
            ->greeting('Hello ' . ($notifiable->name ?? 'User') . ',')
            ->line('A refund request has been reviewed.')
            ->line('Order Number: ' . ($this->refundRequest->order?->order_number ?? 'N/A'))
            ->line('Event: ' . ($this->refundRequest->event?->name ?? 'N/A'))
            ->line('Amount: ' . ($this->refundRequest->currency ?: 'BDT') . ' ' . number_format((float) $this->refundRequest->amount, 2))
            ->line('Status: ' . $statusLabel)
            ->line('Admin Note: ' . ($this->refundRequest->admin_note ?: 'N/A'))
            ->action('View Refunds', $this->targetUrl($notifiable))
            ->line('Please review your account for more details.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Refund ' . ucfirst(str_replace('_', ' ', $this->decision)),
            'message' => 'Refund request for order ' . ($this->refundRequest->order?->order_number ?? '#'.$this->refundRequest->order_id) . ' was ' . str_replace('_', ' ', $this->decision) . '.',
            'refund_request_id' => $this->refundRequest->id,
            'order_id' => $this->refundRequest->order_id,
            'order_number' => $this->refundRequest->order?->order_number,
            'event_id' => $this->refundRequest->event_id,
            'event_name' => $this->refundRequest->event?->name,
            'amount' => (float) $this->refundRequest->amount,
            'currency' => $this->refundRequest->currency ?: 'BDT',
            'status' => $this->decision,
            'admin_note' => $this->refundRequest->admin_note,
            'url' => $this->targetUrl($notifiable),
        ];
    }

    protected function targetUrl(object $notifiable): string
    {
        $organizerOwnerId = $this->refundRequest->organizerProfile?->user_id;

        if ($organizerOwnerId && (int) ($notifiable->id ?? 0) === (int) $organizerOwnerId) {
            return route('organizer.reports.sales');
        }

        return route('user.refunds.index');
    }
}

