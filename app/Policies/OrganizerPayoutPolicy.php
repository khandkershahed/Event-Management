<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\OrganizerPayout;
use App\Models\User;
use App\Services\OrganizerTeamAccessService;

class OrganizerPayoutPolicy
{
    public function view(Admin|User $actor, OrganizerPayout $organizerPayout): bool
    {
        if ($actor instanceof Admin) {
            return true;
        }

        return $organizerPayout->organizerProfile
            && app(OrganizerTeamAccessService::class)->userCan($actor, 'finance.view', $organizerPayout->organizerProfile);
    }

    public function create(User $user): bool
    {
        $organizer = $user->organizerProfile;

        return $organizer && app(OrganizerTeamAccessService::class)->userCan($user, 'finance.manage', $organizer);
    }
}
