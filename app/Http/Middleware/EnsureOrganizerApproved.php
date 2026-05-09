<?php

namespace App\Http\Middleware;

use App\Models\OrganizerProfile;
use App\Services\OrganizerTeamAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizerApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('web');

        if (! $user) {
            return redirect()->route('login');
        }

        $profile = app(OrganizerTeamAccessService::class)->resolveProfileFor($user);

        if (! $profile || $profile->status !== OrganizerProfile::STATUS_APPROVED) {
            return redirect()->route('organizer.status')
                ->with('error', 'Your organizer profile or team access must be approved before accessing this area.');
        }

        $user->setRelation('organizerProfile', $profile);
        $request->attributes->set('organizer_profile', $profile);

        return $next($request);
    }
}
