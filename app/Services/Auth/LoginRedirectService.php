<?php

namespace App\Services\Auth;

use App\Models\OrganizerProfile;
use App\Models\OrganizerTeamMember;
use App\Models\User;
use App\Services\OrganizerTeamAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class LoginRedirectService
{
    public function __construct(private readonly OrganizerTeamAccessService $organizerAccess)
    {
    }

    public function redirectAfterLogin(Request $request, User $user): RedirectResponse
    {
        $fallback = $this->defaultPathFor($user);
        $intended = $request->session()->get('url.intended');

        if ($this->intendedUrlIsAllowed($user, $intended)) {
            return redirect()->intended($fallback);
        }

        $request->session()->forget('url.intended');

        return redirect($fallback);
    }

    public function defaultPathFor(User $user): string
    {
        if (($user->role ?? null) === 'moderator' && Route::has('moderator.dashboard')) {
            return route('moderator.dashboard', absolute: false);
        }

        $ownedProfile = $user->organizerProfile;

        if ($ownedProfile && $ownedProfile->isApproved() && Route::has('organizer.dashboard')) {
            return route('organizer.dashboard', absolute: false);
        }

        $membership = $this->organizerAccess->activeMembershipFor($user);

        if ($membership) {
            return $this->pathForMembership($membership);
        }

        if ($ownedProfile && Route::has('organizer.status')) {
            return route('organizer.status', absolute: false);
        }

        return $this->customerDashboardPath();
    }

    private function pathForMembership(OrganizerTeamMember $membership): string
    {
        return match ($membership->role) {
            OrganizerTeamMember::ROLE_CHECK_IN_STAFF => $this->routePath('organizer.check-in.index', '/organizer/check-in'),
            OrganizerTeamMember::ROLE_FINANCE_VIEWER => $this->routePath('organizer.reports.index', '/organizer/reports'),
            OrganizerTeamMember::ROLE_MANAGER => $this->routePath('organizer.dashboard', '/organizer/dashboard'),
            OrganizerTeamMember::ROLE_OWNER => $this->routePath('organizer.dashboard', '/organizer/dashboard'),
            default => $this->customerDashboardPath(),
        };
    }

    private function intendedUrlIsAllowed(User $user, mixed $intended): bool
    {
        if (! is_string($intended) || trim($intended) === '') {
            return false;
        }

        $path = '/' . ltrim((string) (parse_url($intended, PHP_URL_PATH) ?: ''), '/');

        if ($path === '/' || $path === '/login' || $path === '/dashboard' || str_starts_with($path, '/admin')) {
            return false;
        }

        if (! str_starts_with($path, '/organizer')) {
            return true;
        }

        return $this->organizerPathIsAllowed($user, $path);
    }

    private function organizerPathIsAllowed(User $user, string $path): bool
    {
        $ownedProfile = $user->organizerProfile;

        if ($ownedProfile && ! $ownedProfile->isApproved()) {
            return $path === '/organizer/status' || $path === '/organizer/profile';
        }

        if ($ownedProfile && $ownedProfile->isApproved()) {
            return true;
        }

        $membership = $this->organizerAccess->activeMembershipFor($user);

        if (! $membership) {
            return false;
        }

        if ($this->isSharedOrganizerPath($path)) {
            return true;
        }

        return match ($membership->role) {
            OrganizerTeamMember::ROLE_MANAGER => $this->isManagerPath($path),
            OrganizerTeamMember::ROLE_CHECK_IN_STAFF => $this->isCheckInPath($path),
            OrganizerTeamMember::ROLE_FINANCE_VIEWER => $this->isFinancePath($path),
            OrganizerTeamMember::ROLE_OWNER => true,
            default => false,
        };
    }

    private function isSharedOrganizerPath(string $path): bool
    {
        return $path === '/organizer/status'
            || $path === '/organizer/profile'
            || str_starts_with($path, '/organizer/notifications')
            || str_starts_with($path, '/organizer/support-tickets');
    }

    private function isManagerPath(string $path): bool
    {
        foreach ([
            '/organizer/dashboard',
            '/organizer/approved-profile',
            '/organizer/orders',
            '/organizer/events',
            '/organizer/venues',
            '/organizer/seating-plans',
            '/organizer/team-members',
            '/organizer/check-in',
        ] as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return true;
            }
        }

        return false;
    }

    private function isCheckInPath(string $path): bool
    {
        return $path === '/organizer/check-in' || str_starts_with($path, '/organizer/check-in/');
    }

    private function isFinancePath(string $path): bool
    {
        foreach ([
            '/organizer/reports',
            '/organizer/reviews',
            '/organizer/payouts',
            '/organizer/finance-profile',
        ] as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return true;
            }
        }

        return false;
    }

    private function routePath(string $routeName, string $fallback): string
    {
        return Route::has($routeName) ? route($routeName, absolute: false) : $fallback;
    }

    private function customerDashboardPath(): string
    {
        return $this->routePath('user.dashboard', '/user/dashboard');
    }
}
