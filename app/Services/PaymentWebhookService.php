<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PaymentWebhookService
{
    public function __construct(protected StripeCheckoutService $stripeCheckoutService)
    {
    }

    public function handleStripeRequest(Request $request): array
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = (string) config('services.stripe.webhook_secret');

        if ($secret !== '' && ! app()->environment('testing')) {
            try {
                $event = Webhook::constructEvent($payload, $signature, $secret);
                $data = $event->toArray();
            } catch (\Throwable $exception) {
                throw new BadRequestHttpException('Invalid Stripe webhook signature.');
            }
        } else {
            $data = json_decode($payload, true);
            if (! is_array($data)) {
                throw new BadRequestHttpException('Invalid Stripe webhook payload.');
            }
        }

        return $this->handleStripeEvent($data);
    }

    public function handleStripeEvent(array $event): array
    {
        $type = (string) ($event['type'] ?? '');
        $object = $event['data']['object'] ?? [];

        if (! is_array($object)) {
            return ['handled' => false, 'message' => 'Missing Stripe object payload.'];
        }

        if (in_array($type, ['checkout.session.completed', 'payment_intent.succeeded'], true)) {
            $order = $this->resolveOrder($object);
            if (! $order) {
                return ['handled' => false, 'message' => 'Order not found.'];
            }

            $this->stripeCheckoutService->markCheckoutSuccess($order, $object['id'] ?? null, $object);

            return ['handled' => true, 'message' => 'Order marked paid.'];
        }

        if (in_array($type, ['checkout.session.expired', 'payment_intent.payment_failed', 'checkout.session.async_payment_failed'], true)) {
            $order = $this->resolveOrder($object);
            if (! $order) {
                return ['handled' => false, 'message' => 'Order not found.'];
            }

            $this->stripeCheckoutService->markCheckoutFailed($order, $object['id'] ?? null, $object);

            return ['handled' => true, 'message' => 'Order payment failed.'];
        }

        return ['handled' => false, 'message' => 'Stripe event ignored.'];
    }

    protected function resolveOrder(array $object): ?Order
    {
        $orderId = $object['metadata']['order_id'] ?? $object['client_reference_id'] ?? null;

        if ($orderId) {
            return Order::find((int) $orderId);
        }

        $sessionId = $object['id'] ?? null;
        if ($sessionId) {
            $transaction = PaymentTransaction::where('provider', PaymentTransaction::PROVIDER_STRIPE)
                ->where('provider_session_id', $sessionId)
                ->first();

            return $transaction?->order;
        }

        return null;
    }
}
