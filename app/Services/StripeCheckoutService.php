<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentTransaction;
use RuntimeException;
use Illuminate\Support\Str;
use Stripe\StripeClient;

class StripeCheckoutService
{
    public function __construct(protected OrganizerLedgerService $organizerLedgerService)
    {
    }

    public function createCheckoutSession(Order $order): array
    {
        $order->loadMissing(['event', 'items']);

        if (! $order->requiresPayment()) {
            throw new RuntimeException('Only unpaid paid orders can be sent to Stripe checkout.');
        }

        $amount = (float) $order->total;
        if ($amount <= 0) {
            throw new RuntimeException('Free orders do not require Stripe payment.');
        }

        $successUrl = route('frontend.payment.success', $order, absolute: true) . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('frontend.payment.cancel', $order, absolute: true) . '?session_id={CHECKOUT_SESSION_ID}';

        if ($this->shouldUseFakeStripeSession()) {
            $fakeSessionId = 'cs_test_' . strtolower((string) $order->order_number) . '_' . Str::random(8);
            $transaction = $this->storePendingTransaction($order, $fakeSessionId, null, ['mode' => 'testing_fake_session']);
            $order->markPaymentPending();

            return [
                'id' => $fakeSessionId,
                'url' => route('frontend.payment.success', ['order' => $order, 'session_id' => $fakeSessionId], true),
                'transaction' => $transaction,
            ];
        }

        $secret = (string) config('services.stripe.secret');
        if ($secret === '') {
            throw new RuntimeException('Stripe secret key is not configured. Add STRIPE_SECRET to your .env file.');
        }

        $stripe = new StripeClient($secret);
        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => (string) $order->id,
            'customer_email' => $order->customer_email,
            'metadata' => [
                'order_id' => (string) $order->id,
                'order_number' => (string) $order->order_number,
            ],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => strtolower($order->currency ?: 'BDT'),
                    'unit_amount' => (int) round($amount * 100),
                    'product_data' => [
                        'name' => 'Order ' . $order->order_number,
                        'description' => $order->event?->name ?: 'Event ticket order',
                    ],
                ],
            ]],
        ]);

        $transaction = $this->storePendingTransaction(
            $order,
            $session->id,
            $session->payment_intent ?? null,
            $session->toArray()
        );
        $order->markPaymentPending();

        return [
            'id' => $session->id,
            'url' => $session->url,
            'transaction' => $transaction,
        ];
    }

    public function markCheckoutSuccess(Order $order, ?string $sessionId = null, array $payload = []): PaymentTransaction
    {
        $transaction = $this->findOrCreateTransaction($order, $sessionId, $payload);

        $transaction->forceFill([
            'provider_payment_intent_id' => $payload['payment_intent'] ?? $transaction->provider_payment_intent_id,
            'status' => PaymentTransaction::STATUS_PAID,
            'raw_payload' => $payload ?: $transaction->raw_payload,
            'paid_at' => now(),
        ])->save();

        $order->markPaid();
        $this->organizerLedgerService->postPaidOrder($order->fresh(['event.organizerProfile']));

        return $transaction;
    }

    public function markCheckoutFailed(Order $order, ?string $sessionId = null, array $payload = []): PaymentTransaction
    {
        $transaction = $this->findOrCreateTransaction($order, $sessionId, $payload);

        $transaction->forceFill([
            'status' => PaymentTransaction::STATUS_FAILED,
            'raw_payload' => $payload ?: $transaction->raw_payload,
        ])->save();

        $order->markPaymentFailed();

        return $transaction;
    }

    protected function storePendingTransaction(Order $order, ?string $sessionId, ?string $paymentIntentId, array $payload): PaymentTransaction
    {
        return PaymentTransaction::updateOrCreate(
            [
                'order_id' => $order->id,
                'provider' => PaymentTransaction::PROVIDER_STRIPE,
                'provider_session_id' => $sessionId,
            ],
            [
                'provider_payment_intent_id' => $paymentIntentId,
                'amount' => $order->total,
                'currency' => $order->currency ?: 'BDT',
                'status' => PaymentTransaction::STATUS_PENDING,
                'raw_payload' => $payload,
            ]
        );
    }

    protected function findOrCreateTransaction(Order $order, ?string $sessionId = null, array $payload = []): PaymentTransaction
    {
        $query = PaymentTransaction::where('order_id', $order->id)
            ->where('provider', PaymentTransaction::PROVIDER_STRIPE);

        if ($sessionId) {
            $query->where('provider_session_id', $sessionId);
        }

        $transaction = $query->latest()->first();

        if ($transaction) {
            return $transaction;
        }

        return PaymentTransaction::create([
            'order_id' => $order->id,
            'provider' => PaymentTransaction::PROVIDER_STRIPE,
            'provider_session_id' => $sessionId,
            'provider_payment_intent_id' => $payload['payment_intent'] ?? null,
            'amount' => $order->total,
            'currency' => $order->currency ?: 'BDT',
            'status' => PaymentTransaction::STATUS_PENDING,
            'raw_payload' => $payload,
        ]);
    }

    protected function shouldUseFakeStripeSession(): bool
    {
        if (app()->environment('testing')) {
            return true;
        }

        return (bool) config('services.stripe.fake_checkout', false);
    }
}
