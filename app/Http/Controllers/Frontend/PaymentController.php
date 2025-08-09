<?php

namespace App\Http\Controllers\Frontend;

use Stripe\Stripe;
use App\Models\Booking;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\TemporaryBooking;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function showPaymentPage($id)
    {
        $booking = TemporaryBooking::with('seats')->findOrFail($id);

        if ($booking->status !== 'pending' || $booking->reserved_until < now()) {
            return abort(404, "Booking not available or expired.");
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Calculate total amount, for demo $50 per seat
        $amountCents = count($booking->seats) * 5000;

        $line_items = [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Event Seat Booking #' . $booking->event_id,
                ],
                'unit_amount' => $amountCents,
            ],
            'quantity' => 1,
        ]];

        // Create Stripe checkout session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $booking->user_email,
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
            'metadata' => [
                'temporary_booking_id' => $booking->id,
            ],
        ]);

        return redirect($session->url);
    }

    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response('Invalid payload', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $bookingId = $session->metadata->temporary_booking_id ?? null;

            if ($bookingId) {
                $booking = TemporaryBooking::with('seats')->find($bookingId);

                if ($booking && $booking->status === 'pending') {
                    // Mark temporary booking paid
                    $booking->status = 'paid';
                    $booking->save();

                    // Create confirmed bookings for each seat
                    foreach ($booking->seats as $seat) {
                        Booking::create([
                            'user_name' => $booking->user_name,
                            'user_email' => $booking->user_email,
                            'event_id' => $booking->event_id,
                            'seat_id' => $seat->seat_id,
                            'status' => 'confirmed',
                        ]);
                    }

                    // Delete temporary booking and seats
                    $booking->delete();
                }
            }
        }

        return response('Webhook handled', 200);
    }

    public function paymentSuccess(Request $request)
    {
        return "Payment Successful! Thank you for booking.";
    }

    public function paymentCancel()
    {
        return "Payment was canceled. Your seats are not reserved.";
    }
}
