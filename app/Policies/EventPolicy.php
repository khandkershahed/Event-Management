<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Event;
use App\Models\User;
use App\Services\OrganizerTeamAccessService;

class EventPolicy
{
    public function viewAny(Admin|User $actor): bool
    {
        return true;
    }

    public function view(Admin|User $actor, Event $event): bool
    {
        if ($actor instanceof Admin) {
            return true;
        }

        return $this->belongsToOrganizer($actor, $event, 'operations.manage') || $event->status === Event::STATUS_PUBLISHED;
    }

    public function update(User $user, Event $event): bool
    {
        return $event->canBeEditedByOrganizer() && $this->belongsToOrganizer($user, $event, 'operations.manage');
    }

    public function submit(User $user, Event $event): bool
    {
        return $event->canBeSubmittedByOrganizer() && $this->belongsToOrganizer($user, $event, 'operations.manage');
    }

    public function publish(User $user, Event $event): bool
    {
        return $event->canBePublishedByOrganizer() && $this->belongsToOrganizer($user, $event, 'operations.manage');
    }

    protected function belongsToOrganizer(User $user, Event $event, string $ability): bool
    {
        $organizer = $event->organizerProfile;

        return $organizer && app(OrganizerTeamAccessService::class)->userCan($user, $ability, $organizer);
    }
}
