<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\RefundRequest;
use App\Models\User;
use App\Services\OrganizerTeamAccessService;

class RefundRequestPolicy
{
    public function view(Admin|User $actor, RefundRequest $refundRequest): bool
    {
        if ($actor instanceof Admin) {
            return true;
        }

        if ((int) $refundRequest->user_id === (int) $actor->id) {
            return true;
        }

        return $refundRequest->organizerProfile
            && app(OrganizerTeamAccessService::class)->userCan($actor, 'finance.view', $refundRequest->organizerProfile);
    }
}
