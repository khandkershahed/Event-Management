<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\StripeCheckoutService;
use Illuminate\Http\Request;
use RuntimeException;

class PaymentController extends Controller
{
    public function __construct(protected StripeCheckoutService $stripeCheckoutService)
    {
    }

    public function stripe(Order $order, Request $request)
    {
        $this->authorizeOrderAccess($order, $request);

        if ($order->isFreeOrder() || $order->isCompleted()) {
            return redirect()
                ->route('frontend.order.success', $order)
                ->with('error', 'This order does not require Stripe payment.');
        }

        try {
            $session = $this->stripeCheckoutService->createCheckoutSession($order);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('frontend.order.success', $order)
                ->with('error', $exception->getMessage());
        }

        return redirect()->away($session['url']);
    }

    public function success(Order $order, Request $request)
    {
        $this->authorizeOrderAccess($order, $request);

        if (! $order->isCompleted()) {
            $this->stripeCheckoutService->markCheckoutSuccess($order, $request->query('session_id'), [
                'id' => $request->query('session_id'),
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'order_number' => (string) $order->order_number,
                ],
                'source' => 'success_return',
            ]);
        }

        return redirect()
            ->route('frontend.order.success', $order)
            ->with('success', 'Payment completed successfully.');
    }

    public function cancel(Order $order, Request $request)
    {
        $this->authorizeOrderAccess($order, $request);

        if (! $order->isCompleted()) {
            $this->stripeCheckoutService->markCheckoutFailed($order, $request->query('session_id'), [
                'id' => $request->query('session_id'),
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'order_number' => (string) $order->order_number,
                ],
                'source' => 'cancel_return',
            ]);
        }

        return redirect()
            ->route('frontend.order.success', $order)
            ->with('error', 'Payment was cancelled. You can try again from this order page.');
    }

    protected function authorizeOrderAccess(Order $order, Request $request): void
    {
        $userId = auth()->id();
        $sessionId = $request->session()->getId();

        abort_unless(
            ($userId && (int) $order->user_id === $userId) || (! $order->user_id && (string) $order->session_id === $sessionId),
            403
        );
    }
}
