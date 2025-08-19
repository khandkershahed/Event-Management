<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Models\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Mail\EmailVerificationMail;
use App\Http\Controllers\Controller;
use App\Models\EventSeat;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    private $otp;

    public function __construct()
    {
        // $this->otp = new Otp();
    }

    /**
     * Register a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:8|confirmed',
    //     ], [
    //         'name.required' => 'Name is required',
    //         'email.required' => 'Email is required',
    //         'password.required' => 'Password is required',
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     return response()->json([
    //         'message' => 'Registration Success',
    //         'status' => 'success'
    //     ], 201);
    // }
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'phone' => 'required|string|max:19|',
    //         'password' => 'required|string|min:8|confirmed',
    //     ], [
    //         'name.required' => 'Name is required',
    //         'email.required' => 'Email is required',
    //         'phone.required' => 'Your Phone Number is required',
    //         'password.required' => 'Password is required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error.', $validator->errors());
    //     }
    //     $input = $request->all();
    //     // $input['password'] = bcrypt($input['password']);
    //     $input['password'] = Hash::make($input['password']);
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'password' => Hash::make($request->password),
    //     ]);
    //     $success['token'] =  $user->createToken('apiToken')->plainTextToken;
    //     $success['name'] =  $user->name;
    //     // $otp = Otp::generate($user->email, 6, 15);
    //     // Mail::to($user->email)->send(new EmailVerificationMail($otp->token, $user->name));
    //     return $this->sendResponse($success, 'User is registered successfully.');
    // }
    // public function sendemailVerification(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|max:255|exists:users',
    //     ], [
    //         'email.required' => 'Email is required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error.', $validator->errors());
    //     }
    //     $user = User::where('email', $request->email)->first();
    //     $otp = Otp::generate($user->email, 6, 15);
    //     Mail::to($user->email)->send(new EmailVerificationMail($otp->token, $user->name));
    //     return $this->sendResponse($user, 'Email verification OTP sent successfully.');
    // }
    // public function emailVerification(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|max:255|exists:users',
    //         'otp' => 'required|max:6',
    //     ], [
    //         'email.required' => 'Email is required',
    //         'otp.required' => 'OTP is required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error.', $validator->errors());
    //     }
    //     $otp2 = Otp::verify($request->email, $request->otp);
    //     if ($otp2) {
    //         $user = User::where('email', $request->email)->first();
    //         $user->update(['email_verified_at' => now()]);
    //         return $this->sendResponse($user, 'Email is verified successfully.');
    //     } else {
    //         return $this->sendError('Validation Error.', ['otp' => 'OTP is not valid']);
    //     }
    // }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ], [
    //         'email.required' => 'Email is required',
    //         'password.required' => 'Password is required',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'message' => 'The provided credentials are incorrect.',
    //             'status' => 'error'
    //         ], 401);
    //     }

    //     return response()->json([
    //         'token' => $user->createToken('apiToken')->plainTextToken,
    //         'message' => 'Login Success',
    //         'status' => 'success'
    //     ], 200);
    // }

    // public function logout(Request $request)
    // {
    //     // $request->user()->currentAccessToken()->delete();
    //     $request->user()->tokens()->delete();
    //     return response()->json([
    //         'message' => 'User Logged Out Successfully',
    //         'status' => 'success'
    //     ], 200);
    // }

    // public function updatePassword(Request $request)
    // {
    //     $request->validate([
    //         'current_password' => ['required', 'string'],
    //         'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);

    //     if (!Hash::check($request->current_password, $request->user()->password)) {
    //         return response()->json([
    //             'message' => 'Current password is incorrect',
    //             'status' => 'error'
    //         ], 400);
    //     }

    //     $request->user()->update(['password' => Hash::make($request->new_password)]);

    //     return response()->json([
    //         'message' => 'Password changed successfully',
    //         'status' => 'success'
    //     ], 200);
    // }

    // public function reset(Request $request, $token)
    // {
    //     // Delete Token older than 2 minute
    //     $formatted = now()->subMinutes(2)->toDateTimeString();
    //     DB::table('password_reset_tokens')->where('created_at', '<=', $formatted)->delete();

    //     $request->validate([
    //         'password' => 'required|confirmed',
    //     ]);

    //     $passwordreset = DB::table('password_reset_tokens')->where('token', $token)->first();

    //     if (!$passwordreset) {
    //         return response([
    //             'message' => 'Token is Invalid or Expired',
    //             'status' => 'failed'
    //         ], 404);
    //     }

    //     // Update the user's password
    //     User::where('email', $passwordreset->email)->update([
    //         'password' => Hash::make($request->password),
    //     ]);

    //     // Delete the token after resetting password
    //     DB::table('password_reset_tokens')->where('email', $passwordreset->email)->delete();

    //     return response([
    //         'message' => 'Password Reset Success',
    //         'status' => 'success'
    //     ], 200);
    // }

    // public function forgotPassword(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);

    //     $email = $request->email;

    //     // Check if the email exists
    //     $user = User::where('email', $email)->first();
    //     if (!$user) {
    //         return response([
    //             'message' => 'Email does not exist',
    //             'status' => 'failed'
    //         ], 404);
    //     }

    //     // Generate Token
    //     $token = Str::random(60);

    //     // Saving Data to Password Reset Table
    //     DB::table('password_reset_tokens')->upsert([
    //         'email' => $email,
    //         'token' => $token,
    //         'created_at' => now()
    //     ], ['email'], ['token', 'created_at']);

    //     // Sending EMail with Password Reset Token
    //     Mail::raw("Your password reset token is: $token", function ($message) use ($email) {
    //         $message->subject('Reset Your Password');
    //         $message->to($email);
    //     });

    //     return response([
    //         'message' => 'Password Reset Email Sent... Check Your Email',
    //         'status' => 'success'
    //     ], 200);
    // }

    // public function profile(Request $request)
    // {
    //     return response()->json([
    //         'user' => $request->user(),
    //         'message' => 'User profile retrieved successfully.',
    //         'status' => 'success'
    //     ], 200);
    // }

    // public function editProfile(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
    //     ], [
    //         'name.required' => 'Name is required',
    //         'email.required' => 'Email is required',
    //     ]);

    //     $user = $request->user();

    //     $user->update([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //     ]);

    //     return response()->json([
    //         'user' => $user,
    //         'message' => 'Profile updated successfully',
    //         'status' => 'success'
    //     ], 200);
    // }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:19',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'role'     => 'user',
            'password' => Hash::make($request->password),
        ]);

        $success['token'] = $user->createToken('apiToken')->plainTextToken;
        $success['name']  = $user->name;

        return $this->sendResponse($success, 'User registered successfully.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
                'status'  => 'error'
            ], 401);
        }
        // dd($request->user());
        return response()->json([
            'token'   => $user->createToken('apiToken')->plainTextToken,
            'message' => 'Login success',
            'status'  => 'success'
        ]);
    }

    // public function logout(Request $request)
    // {
    //     // dd($request->user());
    //     $request->user()->tokens()->delete();

    //     return response()->json([
    //         'message' => 'User logged out successfully.',
    //         'status'  => 'success'
    //     ]);
    // }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'User logged out successfully.',
            'status'  => 'success'
        ]);
    }

    // public function profile(Request $request)
    // {
    //     return response()->json($request->user());
    // }
    public function profile(Request $request)
    {
        return response()->json([
            'user'    => $request->user(),
            'message' => 'User profile retrieved successfully.',
            'status'  => 'success'
        ]);
    }

    public function sendemailVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|exists:users',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::where('email', $request->email)->first();
        // $otp  = Otp::generate($user->email, 6, 15);

        // Mail::to($user->email)->send(new EmailVerificationMail($otp->token, $user->name));

        return $this->sendResponse([], 'Email verification OTP sent successfully.');
    }

    public function emailVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'otp'   => 'required|string|max:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // $otp2 = Otp::validate($request->email, $request->otp);

        // if (!$otp2->status) {
        //     return $this->sendError('Invalid OTP.', ['otp' => 'The provided OTP is not valid or expired']);
        // }

        $user = User::where('email', $request->email)->first();
        $user->email_verified_at = now();
        $user->save();

        return $this->sendResponse($user, 'Email verified successfully.');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email does not exist', 'status' => 'error'], 404);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->upsert([
            'email'      => $request->email,
            'token'      => $token,
            'created_at' => now()
        ], ['email'], ['token', 'created_at']);

        Mail::raw("Your password reset token is: $token", function ($message) use ($request) {
            $message->to($request->email)->subject('Password Reset');
        });

        return response()->json([
            'message' => 'Password reset token sent.',
            'status'  => 'success'
        ]);
    }

    public function reset(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);

        DB::table('password_reset_tokens')
            ->where('created_at', '<=', now()->subMinutes(2))
            ->delete();

        $record = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$record) {
            return response()->json(['message' => 'Invalid or expired token.', 'status' => 'error'], 404);
        }

        $user = User::where('email', $record->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return response()->json([
            'message' => 'Password reset successfully.',
            'status'  => 'success'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|confirmed|min:8',
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.',
                'status'  => 'error'
            ], 400);
        }

        $request->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'message' => 'Password updated successfully.',
            'status'  => 'success'
        ]);
    }



    public function editProfile(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username'      => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
            'city'          => 'nullable|string|max:255',
            'country'       => 'nullable|string|max:255',
            'zipcode'       => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle image upload if present
        $files = [
            'profile_image' => $request->file('profile_image'),
        ];

        $uploadedFiles = [];

        foreach ($files as $key => $file) {
            if (!empty($file)) {
                $filePath = 'user/' . $key;
                $oldFile  = $event->$key ?? null;

                if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }

                $uploadedFiles[$key] = customUpload($file, $filePath);
                if ($uploadedFiles[$key]['status'] === 0) {
                    return redirect()->back()->with('error', $uploadedFiles[$key]['error_message']);
                }
            } else {
                $uploadedFiles[$key] = ['status' => 0];
            }
        }


        $user->update([
            $validatedData,
            'profile_image' => $uploadedFiles['profile_image']['status'] == 1 ? $uploadedFiles['profile_image']['file_path'] : null,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Profile updated successfully.',
            'user'    => $user,
        ]);
    }



    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $user->update($request->only(['name', 'email', 'username', 'phone', 'address', 'profile_image', 'country', 'city', 'zipcode']));
        return response()->json(['message' => 'Profile updated.', 'user' => $user]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 403);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return response()->json(['message' => 'Password updated successfully.']);
    }

    public function deleteAccount(Request $request)
    {
        $request->user()->delete();
        return response()->json(['message' => 'Account deleted successfully.']);
    }

    // User tickets
    public function tickets(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::with([
                'user:id,name,email',
                'event:id,name,start_date,start_time,venue,end_date,end_time'
            ])
            ->where('user_id', $user->id)
            ->get();
        // Attach seat details to each booking
        $bookings->transform(function ($booking) {
            $eventSeats = json_decode($booking->event_seats, true);
            $seatIds = $eventSeats['seat_ids'] ?? [];
            // Fetch seat details
            $seats = EventSeat::whereIn('id', $seatIds)
                ->get(['name', 'code', 'price', 'row', 'column', 'status']);

            $booking->seats = $seats;
            return $booking;
        });

        return response()->json([
            'status' => 'success',
            'bookings' => $bookings,
            'message' => 'User tickets retrieved successfully.'
        ]);
    }
}
