<?php

namespace App\Http\Controllers\Frontend;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TemporaryBooking;

class PaymentController extends Controller
{
    /**
     * Redirect user to Stripe Checkout
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

        $amountCents = (int) ($booking->total_amount * 100); // dollars → cents

        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $booking->user_email,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Event Booking #' . $booking->event_id,
                    ],
                    'unit_amount' => $amountCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => config('app.frontend_url') . '/dashboard/tickets?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => config('app.frontend_url') . '/payment/failed',
            'metadata' => [
                'temporary_booking_id' => $booking->id,
            ],
        ]);

        return redirect($session->url);
    }

    /**
     * Stripe Webhook - listens for successful payments
     */
    public function handleStripeWebhook(Request $request)
    {
        $payload       = $request->getContent();
        $sigHeader     = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response('Invalid payload', 400);
        }

        if ($event->type === 'checkout.session.completed' || $event->type === 'payment_intent.succeeded') {
            $session   = $event->data->object;
            $bookingId = $session->metadata->temporary_booking_id ?? null;

            if ($bookingId) {
                DB::transaction(function () use ($bookingId, $session) {
                    $tempBooking = TemporaryBooking::with('seats')->find($bookingId);

                    if ($tempBooking && $tempBooking->status === 'pending') {
                        $seatIds   = $tempBooking->seats->pluck('seat_id')->toArray();
                        $seatNames = $tempBooking->seats->pluck('seat.name')->toArray();

                        // ✅ Create final booking
                        Booking::create([
                            'user_id'       => $tempBooking->user_id,
                            'event_id'      => $tempBooking->event_id,
                            'booking_id'    => strtoupper(Str::random(8)),
                            'user_name'     => $tempBooking->user_name,
                            'user_email'    => $tempBooking->user_email,
                            'invoice_number'=> strtoupper(Str::random(8)),
                            'event_seats'   => json_encode([
                                'seat_ids'   => $seatIds,
                                'seat_names' => $seatNames,
                            ]),
                            'event_datetime'=> $tempBooking->event_datetime,
                            'status'        => 'confirmed',
                            'total_amount'  => $session->amount_total / 100,
                            'payment_status'=> 'paid',
                            'payment_type'  => 'Credit Card',
                            'card_type'     => null, // you can parse from Stripe charge if needed
                            'transaction_id'=> null, // optional if you store bank ref
                            'purchase_date' => now()->toDateString(),
                            'billing_name'  => $tempBooking->user_name,
                            'paid_at'       => now(),
                            'payment_transaction_id' => $session->payment_intent ?? null,
                        ]);

                        // Mark temp booking as used
                        $tempBooking->status = 'paid';
                        $tempBooking->save();

                        // Update seat status
                        DB::table('event_seats')
                            ->whereIn('id', $seatIds)
                            ->update(['status' => 'booked']);

                        // Cleanup
                        $tempBooking->seats()->delete();
                        $tempBooking->delete();
                    }
                });
            }
        }

        return response('Webhook handled', 200);
    }

    /**
     * ✅ API for frontend to check booking status and get full invoice
     */
    public function paymentStatus(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return response()->json(['error' => 'session_id is required'], 400);
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($sessionId);

        $transactionId = $session->payment_intent ?? null;

        // Find booking
        $booking = Booking::where('payment_transaction_id', $transactionId)->first();

        if (!$booking) {
            return response()->json([
                'status' => 'pending',
                'message' => 'Booking not confirmed yet, please wait.',
            ], 202);
        }

        return response()->json([
            'status'  => 'confirmed',
            'invoice' => $booking, // ✅ returns full booking table data
        ]);
    }
}
