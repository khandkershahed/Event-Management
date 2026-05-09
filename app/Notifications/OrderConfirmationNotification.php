<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order,
        protected bool $mailEnabled = true
    ) {
        $this->order->loadMissing('event');
    }

    public function via(object $notifiable): array
    {
        return $this->mailEnabled ? ['database', 'mail'] : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order;
        $eventName = $order->event?->name ?? 'your event';

        return (new MailMessage)
            ->subject('Order confirmation: ' . $order->order_number)
            ->greeting('Hello ' . ($notifiable->name ?? $order->customer_name ?? 'Customer') . ',')
            ->line('Your order has been received successfully.')
            ->line('Order Number: ' . $order->order_number)
            ->line('Event: ' . $eventName)
            ->line('Total: ' . ($order->currency ?: 'BDT') . ' ' . number_format((float) $order->total, 2))
            ->line('Payment Status: ' . ucfirst(str_replace('_', ' ', $order->payment_status)))
            ->action('View Order', route('user.orders.show', $order))
            ->line('Thank you for using our marketplace.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Order confirmation',
            'message' => 'Your order ' . $this->order->order_number . ' has been received.',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'event_id' => $this->order->event_id,
            'event_name' => $this->order->event?->name,
            'total' => (float) $this->order->total,
            'currency' => $this->order->currency ?: 'BDT',
            'payment_status' => $this->order->payment_status,
            'url' => route('user.orders.show', $this->order),
        ];
    }
}
