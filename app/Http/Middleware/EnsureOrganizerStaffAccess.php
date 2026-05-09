<?php

namespace App\Http\Middleware;

use App\Services\OrganizerTeamAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizerStaffAccess
{
    public function handle(Request $request, Closure $next, string $ability): Response
    {
        $user = $request->user('web');

        if (! $user) {
            return redirect()->route('login');
        }

        $profile = $request->attributes->get('organizer_profile') ?: app(OrganizerTeamAccessService::class)->resolveProfileFor($user);
        abort_unless($profile, 403);

        if (! app(OrganizerTeamAccessService::class)->userCan($user, $ability, $profile)) {
            abort(403, 'Your organizer staff role cannot access this area.');
        }

        return $next($request);
    }
}
