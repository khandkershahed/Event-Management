<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TemporaryBooking;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function initiateBooking(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user.name'   => 'required|string|max:100',
                'user.email'  => 'required|email',
                'seat_id'     => 'required|integer|exists:seats,id',
                'event_id'    => 'required|integer|exists:events,id',
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
        // Store temporary booking
        $booking = TemporaryBooking::create([
            'user_id'    => $user_id,
            'user_name'  => $request->user['name'],
            'user_email' => $request->user['email'],
            'seat_id'    => $request->seat_id,
            'event_id'   => $request->event_id,
            'status'     => 'pending',
        ]);


        // Generate redirect URL
        $paymentPageUrl = url('/payment/' . $booking->id);

        return response()->json([
            'redirect_url' => $paymentPageUrl
        ]);
    }
}
