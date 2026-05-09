<?php

namespace App\Services;

use App\Models\OrganizerProfile;
use App\Models\OrganizerTeamMember;
use App\Models\User;

class OrganizerTeamAccessService
{
    public function resolveProfileFor(User $user): ?OrganizerProfile
    {
        $owned = $user->organizerProfile;
        if ($owned && $owned->isApproved()) {
            return $owned;
        }
        $membership = $this->activeMembershipFor($user);
        return $membership ? $membership->organizerProfile : null;
    }

    public function activeMembershipFor(User $user): ?OrganizerTeamMember
    {
        return OrganizerTeamMember::with('organizerProfile')
            ->where('user_id', $user->id)
            ->where('status', OrganizerTeamMember::STATUS_ACTIVE)
            ->whereHas('organizerProfile', function ($query) {
                $query->where('status', OrganizerProfile::STATUS_APPROVED);
            })
            ->orderBy('id')
            ->first();
    }

    public function membershipForProfile(User $user, OrganizerProfile $profile): OrganizerTeamMember
    {
        if ((int) $profile->user_id === (int) $user->id) {
            return new OrganizerTeamMember([
                'organizer_profile_id' => $profile->id,
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => OrganizerTeamMember::ROLE_OWNER,
                'status' => OrganizerTeamMember::STATUS_ACTIVE,
            ]);
        }
        return OrganizerTeamMember::where('organizer_profile_id', $profile->id)
            ->where('user_id', $user->id)
            ->where('status', OrganizerTeamMember::STATUS_ACTIVE)
            ->firstOrFail();
    }

    public function userCan(User $user, string $ability, ?OrganizerProfile $profile = null): bool
    {
        $profile = $profile ?: $this->resolveProfileFor($user);
        if (! $profile) {
            return false;
        }
        return $this->membershipForProfile($user, $profile)->can($ability);
    }
}
