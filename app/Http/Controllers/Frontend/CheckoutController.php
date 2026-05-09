<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderPlacementService;
use Illuminate\Http\Request;
use RuntimeException;

class CheckoutController extends Controller
{
    public function __construct(protected OrderPlacementService $orderPlacementService)
    {
    }

    public function index(Request $request)
    {
        $preview = $this->orderPlacementService->preview($request->session()->getId(), auth()->id());

        if ($preview['items']->isEmpty()) {
            return redirect()->route('frontend.cart')->with('error', 'Your cart is empty. Add tickets before checkout.');
        }

        return view('frontend.pages.tickets.checkout', $preview);
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
        ]);

        if (! auth()->check() && empty($validated['customer_email'])) {
            return back()->withErrors(['customer_email' => 'Email is required for guest checkout.'])->withInput();
        }

        try {
            $order = $this->orderPlacementService->placeFromCart($request->session()->getId(), $validated, auth()->id());
        } catch (RuntimeException $exception) {
            return redirect()->route('frontend.cart')->with('error', $exception->getMessage());
        }

        return redirect()->route('frontend.order.success', $order)->with('success', 'Order created successfully.');
    }

    public function success(Order $order, Request $request)
    {
        $userId = auth()->id();
        $sessionId = $request->session()->getId();

        abort_unless(
            ($userId && (int) $order->user_id === $userId) || (! $order->user_id && (string) $order->session_id === $sessionId),
            403
        );

        $order->load(['event', 'items.tickets.seat.section', 'tickets.seat.section']);

        return view('frontend.pages.tickets.order-success', compact('order'));
    }
}
