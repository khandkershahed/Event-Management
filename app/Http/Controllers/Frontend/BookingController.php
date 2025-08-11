<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TemporaryBooking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\TemporaryBookingSeat;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    // public function initiateBooking(Request $request)
    // {
    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'user.name'   => 'required|string|max:100',
    //             'user.email'  => 'required|email',
    //             'seat_id'     => 'required|integer|exists:seats,id',
    //             'event_id'    => 'required|integer|exists:events,id',
    //         ],
    //         [
    //             'user.name.required'   => 'The user name is required.',
    //             'user.name.string'     => 'The user name must be a string.',
    //             'user.name.max'        => 'The user name may not be greater than 100 characters.',
    //             'user.email.required'  => 'The user email is required.',
    //             'user.email.email'     => 'The user email must be a valid email address.',
    //             'seat_id.required'     => 'The seat id is required.',
    //             'seat_id.integer'      => 'The seat id must be an integer.',
    //             'seat_id.exists'       => 'The selected seat id is invalid.',
    //             'event_id.required'    => 'The event id is required.',
    //             'event_id.integer'     => 'The event id must be an integer.',
    //             'event_id.exists'      => 'The selected event id is invalid.',
    //         ]
    //     );
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }
    //     $user_id = User::where('email', $request->user['email'])->value('id');
    //     // Store temporary booking
    //     $booking = TemporaryBooking::create([
    //         'user_id'    => $user_id,
    //         'user_name'  => $request->user['name'],
    //         'user_email' => $request->user['email'],
    //         'seat_id'    => $request->seat_id,
    //         'event_id'   => $request->event_id,
    //         'status'     => 'pending',
    //     ]);


    //     // Generate redirect URL
    //     $paymentPageUrl = url('/payment/' . $booking->id);

    //     return response()->json([
    //         'redirect_url' => $paymentPageUrl
    //     ]);
    // }


    public function initiateBooking(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user.name' => 'required|string|max:100',
                'user.email' => 'required|email',
                'event_id' => 'required|integer|exists:events,id',
                'seat_ids' => 'required|array|min:1',
                'seat_ids.*' => 'integer|exists:event_seats,id',
            ],
            [
                'user.name.required'   => 'The user name is required.',
                'user.name.string'     => 'The user name must be a string.',
                'user.name.max'        => 'The user name may not be greater than 100 characters.',
                'user.email.required'  => 'The user email is required.',
                'user.email.email'     => 'The user email must be a valid email address.',
                'seat_id.required'     => 'The seat id is required.',
                'seat_id.integer'      => 'The seat id must be an integer.',
                'seat_id.exists'       => 'The selected seat id is invalid.',
                'event_id.required'    => 'The event id is required.',
                'event_id.integer'     => 'The event id must be an integer.',
                'event_id.exists'      => 'The selected event id is invalid.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
        $user_id = User::where('email', $request->user['email'])->value('id');
        $seatIds = $request->input('seat_ids');

        // Clear expired reservations (optional cleanup)
        TemporaryBooking::where('reserved_until', '<', now())->delete();

        // Check if any requested seat is already reserved within 10 minutes
        $conflictingReservations = TemporaryBookingSeat::whereIn('seat_id', $seatIds)
            ->whereHas('temporaryBooking', function ($query) {
                $query->where('status', 'pending')
                    ->where('reserved_until', '>', now());
            })->exists();

        if ($conflictingReservations) {
            return response()->json([
                'status' => 'error',
                'message' => 'One or more selected seats are already reserved. Please select different seats.',
            ], 409);
        }

        // Create Temporary Booking with 10-min expiry
        $booking = TemporaryBooking::create([
            'user_id'        => $user_id,
            'user_name'      => $request->user['name'],
            'user_email'     => $request->user['email'],
            'event_id'       => $request->event_id,
            'total_amount'   => $request->total_amount,
            'status'         => 'pending',
            'reserved_until' => Carbon::now()->addMinutes(10),
        ]);

        // Attach seats
        foreach ($seatIds as $seatId) {
            TemporaryBookingSeat::create([
                'temporary_booking_id' => $booking->id,
                'seat_id' => $seatId,
            ]);
            DB::table('event_seats')
                ->where('id', $seatId)
                ->update(['status' => 'reserved']);
        }
        $redirectUrl = URL::temporarySignedRoute(
            'payment.page',
            now()->addMinutes(10),
            ['booking' => $booking->id]
        );
        // Return URL to redirect user to payment page
        return response()->json([
            'status' => 'success',
            'redirect_url' => $redirectUrl,
        ]);
    }
}
