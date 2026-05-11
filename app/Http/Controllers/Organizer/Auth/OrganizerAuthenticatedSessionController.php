<?php

namespace App\Http\Controllers\Organizer\Auth;

use App\Http\Controllers\Controller;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTeamMember;
use App\Models\User;
use App\Services\OrganizerTeamAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrganizerAuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('organizer.auth.login');
    }

    public function store(Request $request, OrganizerTeamAccessService $access): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $key = 'organizer-login:' . strtolower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Too many organizer login attempts. Please try again shortly.',
            ]);
        }

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        $ownedProfile = $user->organizerProfile;
        $membership = $access->activeMembershipFor($user);

        if (! $ownedProfile && ! $membership) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'email' => 'This account is a customer account. Please use the customer login page or register as an organizer.',
            ]);
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        RateLimiter::clear($key);

        if ($membership) {
            return redirect($this->pathForMembership($membership));
        }

        if ($ownedProfile && $ownedProfile->status === OrganizerProfile::STATUS_APPROVED) {
            return redirect()->route('organizer.dashboard');
        }

        return redirect()->route('organizer.status');
    }

    private function pathForMembership(OrganizerTeamMember $membership): string
    {
        return match ($membership->role) {
            OrganizerTeamMember::ROLE_CHECK_IN_STAFF => route('organizer.check-in.index', absolute: false),
            OrganizerTeamMember::ROLE_FINANCE_VIEWER => route('organizer.reports.index', absolute: false),
            default => route('organizer.dashboard', absolute: false),
        };
    }
}
