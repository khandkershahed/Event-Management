<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\EventTicket;
use App\Models\User;
use App\Services\OrganizerTeamAccessService;

class EventTicketPolicy
{
    public function view(Admin|User $actor, EventTicket $eventTicket): bool
    {
        if ($actor instanceof Admin) {
            return true;
        }

        return $this->canManage($actor, $eventTicket);
    }

    public function update(User $user, EventTicket $eventTicket): bool
    {
        return $this->canManage($user, $eventTicket);
    }

    public function delete(User $user, EventTicket $eventTicket): bool
    {
        return $this->canManage($user, $eventTicket) && (int) ($eventTicket->sold_quantity ?? 0) === 0;
    }

    protected function canManage(User $user, EventTicket $eventTicket): bool
    {
        $organizer = $eventTicket->event?->organizerProfile;

        return $organizer && app(OrganizerTeamAccessService::class)->userCan($user, 'operations.manage', $organizer);
    }
}
