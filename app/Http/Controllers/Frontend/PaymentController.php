<?php

namespace App\Http\Controllers\Frontend;

use Stripe\Stripe;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\TemporaryBooking;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{


public function showPaymentPage($id)
{
    $booking = TemporaryBooking::findOrFail($id);

    Stripe::setApiKey(env('STRIPE_SECRET'));

    $session = Session::create([
        'payment_method_types' => ['card'],
        'customer_email' => $booking->user_email,
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Seat Booking for Event #' . $booking->event_id,
                ],
                'unit_amount' => 5000, // $50.00
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('payment.cancel'),
        'metadata' => [
            'booking_id' => $booking->id,
        ]
    ]);

    return redirect($session->url);
}

}
