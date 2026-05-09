<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Order;
use App\Models\User;
use App\Services\OrganizerTeamAccessService;

class OrderPolicy
{
    public function view(Admin|User $actor, Order $order): bool
    {
        if ($actor instanceof Admin) {
            return true;
        }

        if ((int) $order->user_id === (int) $actor->id) {
            return true;
        }

        $organizer = $order->event?->organizerProfile;

        return $organizer && app(OrganizerTeamAccessService::class)->userCan($actor, 'finance.view', $organizer);
    }
}
