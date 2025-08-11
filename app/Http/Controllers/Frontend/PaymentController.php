<?php

namespace App\Http\Controllers\Frontend;

use Stripe\Stripe;
use App\Models\Booking;
use App\Models\TemporaryBooking;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show Stripe checkout page (redirect).
     */
    public function showPaymentPage(Request $request, TemporaryBooking $booking)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired link.');
        }

        if ($booking->status !== 'pending' || $booking->reserved_until < now()) {
            return abort(404, "Booking not available or expired.");
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $amountCents = $booking->total_amount; 

        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $booking->user_email,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Event Seat Booking #' . $booking->event_id,
                    ],
                    'unit_amount' => $amountCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
            'metadata' => [
                'temporary_booking_id' => $booking->id,
            ],
        ]);

        return redirect($session->url);
    }

    /**
     * Handle Stripe webhook events.
     */
    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

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
                DB::transaction(function () use ($bookingId, $session) {
                    $tempBooking = TemporaryBooking::with('seats')->find($bookingId);

                    if ($tempBooking && $tempBooking->status === 'pending') {
                        // Mark temporary booking as paid
                        $tempBooking->status = 'paid';
                        $tempBooking->save();

                        $seatIds = $tempBooking->seats->pluck('seat_id')->toArray();

                        // Create one booking record
                        $booking = Booking::create([
                            'user_id'                => $tempBooking->user_id,
                            'event_id'               => $tempBooking->event_id,
                            'user_name'              => $tempBooking->user_name,
                            'user_email'             => $tempBooking->user_email,
                            'invoice_number'         => strtoupper(Str::random(10)),
                            'event_datetime'         => now(), // Adjust if you have event datetime stored elsewhere
                            'status'                 => 'confirmed',
                            'total_amount'           => $tempBooking->total_amount, // amount in dollars
                            'payment_status'         => 'paid',
                            'paid_at'                => now(),
                            'payment_transaction_id' => $session->payment_intent ?? null,
                        ]);

                        // Create booking_seats records
                        foreach ($seatIds as $seatId) {
                            $booking->bookingSeats()->create([
                                'seat_id' => $seatId,
                            ]);
                        }

                        // Update seats to 'booked'
                        DB::table('event_seats')
                            ->whereIn('id', $seatIds)
                            ->update(['status' => 'booked']);

                        // Delete temporary booking seats and temporary booking
                        $tempBooking->seats()->delete();
                        $tempBooking->delete();
                    }
                });
            }
        }

        return response('Webhook handled', 200);
    }

    /**
     * Payment success redirect.
     */
    public function paymentSuccess(Request $request)
    {
        return response()->view('frontend.paymentSuccess');
    }

    /**
     * Payment cancelled.
     */
    public function paymentCancel()
    {
        return response()->view('frontend.paymentCancel');
    }
}
