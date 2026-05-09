<?php

namespace App\Notifications;

use App\Models\OrganizerPayout;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutDecisionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public OrganizerPayout $payout,
        public string $decision,
        protected bool $mailEnabled = true
    ) {
        $this->payout->loadMissing('organizerProfile.user');
    }

    public function via(object $notifiable): array
    {
        return $this->mailEnabled ? ['database', 'mail'] : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = ucfirst(str_replace('_', ' ', $this->decision));
        $message = (new MailMessage)
            ->subject('Payout ' . $statusLabel . ': ' . $this->payout->payout_number)
            ->greeting('Hello ' . ($notifiable->name ?? 'Organizer') . ',')
            ->line('Your payout request status has been updated.')
            ->line('Payout Number: ' . $this->payout->payout_number)
            ->line('Amount: ' . ($this->payout->currency ?: 'BDT') . ' ' . number_format((float) $this->payout->amount, 2))
            ->line('Status: ' . $statusLabel);

        if ($this->payout->rejection_reason) {
            $message->line('Rejection Reason: ' . $this->payout->rejection_reason);
        }

        if ($this->payout->paid_at) {
            $message->line('Paid At: ' . $this->payout->paid_at->format('d M Y, h:i A'));
        }

        return $message
            ->action('View Payouts', route('organizer.payouts.index'))
            ->line('Please review your organizer finance area for more details.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payout ' . ucfirst(str_replace('_', ' ', $this->decision)),
            'message' => 'Payout ' . $this->payout->payout_number . ' was ' . str_replace('_', ' ', $this->decision) . '.',
            'payout_id' => $this->payout->id,
            'payout_number' => $this->payout->payout_number,
            'amount' => (float) $this->payout->amount,
            'currency' => $this->payout->currency ?: 'BDT',
            'status' => $this->decision,
            'rejection_reason' => $this->payout->rejection_reason,
            'paid_at' => $this->payout->paid_at?->toDateTimeString(),
            'url' => route('organizer.payouts.index'),
        ];
    }
}
