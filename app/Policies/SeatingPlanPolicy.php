<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\SeatingPlan;
use App\Models\User;
use App\Services\OrganizerTeamAccessService;

class SeatingPlanPolicy
{
    public function view(Admin|User $actor, SeatingPlan $seatingPlan): bool
    {
        if ($actor instanceof Admin) {
            return true;
        }

        return $this->canManage($actor, $seatingPlan);
    }

    public function update(User $user, SeatingPlan $seatingPlan): bool
    {
        return $this->canManage($user, $seatingPlan) && $seatingPlan->status !== SeatingPlan::STATUS_LOCKED;
    }

    public function delete(User $user, SeatingPlan $seatingPlan): bool
    {
        return $this->canManage($user, $seatingPlan) && $seatingPlan->status !== SeatingPlan::STATUS_LOCKED;
    }

    protected function canManage(User $user, SeatingPlan $seatingPlan): bool
    {
        $organizer = $seatingPlan->organizerProfile;

        return $organizer && app(OrganizerTeamAccessService::class)->userCan($user, 'operations.manage', $organizer);
    }
}
