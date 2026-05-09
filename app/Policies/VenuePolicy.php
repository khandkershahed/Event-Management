<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use App\Models\Venue;
use App\Services\OrganizerTeamAccessService;

class VenuePolicy
{
    public function view(Admin|User $actor, Venue $venue): bool
    {
        if ($actor instanceof Admin) {
            return true;
        }

        return $this->canManage($actor, $venue);
    }

    public function update(User $user, Venue $venue): bool
    {
        return $this->canManage($user, $venue);
    }

    public function delete(User $user, Venue $venue): bool
    {
        return $this->canManage($user, $venue);
    }

    protected function canManage(User $user, Venue $venue): bool
    {
        $organizer = $venue->organizerProfile;

        return $organizer && app(OrganizerTeamAccessService::class)->userCan($user, 'operations.manage', $organizer);
    }
}
