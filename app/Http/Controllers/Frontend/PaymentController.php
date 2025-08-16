<?php

namespace App\Http\Controllers\Frontend;

use Stripe\Stripe;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\TemporaryBooking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

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
        Log::info('🔔 Stripe webhook received.');

        $payload        = $request->getContent();
        $sigHeader      = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            Log::info('✅ Stripe event constructed successfully.', ['type' => $event->type]);
        } catch (\Exception $e) {
            Log::error('❌ Stripe Webhook Error: ' . $e->getMessage());
            return response('Invalid payload', 400);
        }

        if ($event->type === 'checkout.session.completed' || $event->type === 'payment_intent.succeeded') {
            $session   = $event->data->object;
            $bookingId = $session->metadata->temporary_booking_id ?? null;

            Log::info('📦 Webhook session data:', [
                'temporary_booking_id' => $bookingId,
                'session_id' => $session->id,
                'payment_intent' => $session->payment_intent ?? null,
            ]);

            if (!$bookingId) {
                Log::warning('⚠️ No temporary_booking_id found in metadata.');
                return response('Missing booking ID', 400);
            }

            try {
                DB::transaction(function () use ($bookingId, $session) {
                    Log::info("🔄 Fetching TemporaryBooking ID: $bookingId");

                    $tempBooking = TemporaryBooking::with('seats.seat')->find($bookingId);

                    if (!$tempBooking) {
                        Log::error("❌ TemporaryBooking ID $bookingId not found.");
                        return;
                    }

                    Log::info("✅ TemporaryBooking found", [
                        'status' => $tempBooking->status,
                        'reserved_until' => $tempBooking->reserved_until,
                    ]);

                    if ($tempBooking->status !== 'pending') {
                        Log::warning("⚠️ Booking status is not 'pending'. It is: " . $tempBooking->status);
                        return;
                    }

                    $seatIds   = $tempBooking->seats->pluck('seat_id')->toArray();
                    $seatNames = $tempBooking->seats->pluck('seat.name')->toArray();

                    Log::info('🎟️ Seats for booking:', [
                        'seat_ids' => $seatIds,
                        'seat_names' => $seatNames,
                    ]);

                    // Create the final Booking
                    $booking = Booking::create([
                        'user_id'        => $tempBooking->user_id,
                        'event_id'       => $tempBooking->event_id,
                        'booking_id'     => strtoupper(Str::random(8)),
                        'user_name'      => $tempBooking->user_name,
                        'user_email'     => $tempBooking->user_email,
                        'invoice_number' => strtoupper(Str::random(8)),
                        'event_seats'    => json_encode([
                            'seat_ids'   => $seatIds,
                            'seat_names' => $seatNames,
                        ]),
                        'event_datetime'         => $tempBooking->event_datetime,
                        'status'                 => 'confirmed',
                        'total_amount'           => $session->amount_total / 100,
                        'payment_status'         => 'paid',
                        'payment_type'           => 'Credit Card',
                        'card_type'              => null,
                        'transaction_id'         => null,
                        'purchase_date'          => now()->toDateString(),
                        'billing_name'           => $tempBooking->user_name,
                        'paid_at'                => now(),
                        'payment_transaction_id' => $session->payment_intent ?? null,
                    ]);

                    Log::info('✅ Booking created successfully', [
                        'booking_id' => $booking->id,
                    ]);

                    // Mark temporary booking as paid
                    $tempBooking->status = 'paid';
                    $tempBooking->save();
                    Log::info("📝 TemporaryBooking status updated to 'paid'.");

                    // Update event seat status
                    DB::table('event_seats')
                        ->whereIn('id', $seatIds)
                        ->update(['status' => 'booked']);
                    Log::info("🪑 Seat status updated to 'booked'.");

                    // Cleanup temp booking and seats
                    $tempBooking->seats()->delete();
                    $tempBooking->delete();
                    Log::info("🧹 Temporary booking and associated seats deleted.");
                });
            } catch (\Throwable $e) {
                Log::error("🔥 Exception during webhook transaction: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);
                return response('Error processing booking', 500);
            }
        }

        return response('Webhook handled', 200);
    }

    /**
     * API for frontend to check booking status and get invoice
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

        $booking = Booking::where('payment_transaction_id', $transactionId)->first();

        if (!$booking) {
            return response()->json([
                'status' => 'pending',
                'message' => 'Booking not confirmed yet, please wait.',
            ], 202);
        }

        // Optionally retrieve billing details from PaymentIntent
        $paymentIntent = \Stripe\PaymentIntent::retrieve($transactionId);
        $paymentMethodId = $paymentIntent->payment_method ?? null;

        $paymentMethod = $paymentMethodId
            ? \Stripe\PaymentMethod::retrieve($paymentMethodId)
            : null;

        $billingDetails = $paymentMethod ? $paymentMethod->billing_details : null;

        $booking->event_name = $booking->event->name ?? null;

        return response()->json([
            'status'  => 'confirmed',
            'invoice' => $booking,
            'billing' => [
                'name'       => $billingDetails->name ?? null,
                'email'      => $billingDetails->email ?? null,
                'phone'      => $billingDetails->phone ?? null,
                'address'    => $billingDetails->address ?? null,
                'card_brand' => $paymentMethod && $paymentMethod->card ? $paymentMethod->card->brand : null,
                'last4'      => $paymentMethod && $paymentMethod->card ? $paymentMethod->card->last4 : null,
            ],
        ]);
    }
}
