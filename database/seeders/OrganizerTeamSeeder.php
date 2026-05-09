<?php

namespace Database\Seeders;

use App\Models\OrganizerProfile;
use App\Models\OrganizerTeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrganizerTeamSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = OrganizerProfile::where('status', OrganizerProfile::STATUS_APPROVED)->first();

        if (! $organizer) {
            return;
        }

        $manager = User::firstOrCreate(
            ['email' => 'organizer.manager@example.com'],
            ['name' => 'Organizer Manager', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        $checkIn = User::firstOrCreate(
            ['email' => 'organizer.checkin@example.com'],
            ['name' => 'Check In Staff', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        $finance = User::firstOrCreate(
            ['email' => 'organizer.finance@example.com'],
            ['name' => 'Finance Viewer', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        $this->syncMember($organizer, $manager, OrganizerTeamMember::ROLE_MANAGER);
        $this->syncMember($organizer, $checkIn, OrganizerTeamMember::ROLE_CHECK_IN_STAFF);
        $this->syncMember($organizer, $finance, OrganizerTeamMember::ROLE_FINANCE_VIEWER);

        $organizer->teamMembers()->updateOrCreate(
            ['email' => 'pending.staff@example.com'],
            [
                'user_id' => null,
                'name' => 'Pending Staff Invite',
                'role' => OrganizerTeamMember::ROLE_CHECK_IN_STAFF,
                'status' => OrganizerTeamMember::STATUS_PENDING,
                'invite_token' => 'demo-pending-staff-token',
                'invited_by' => $organizer->user_id,
                'invited_at' => now(),
            ]
        );
    }

    private function syncMember(OrganizerProfile $organizer, User $user, string $role): void
    {
        $organizer->teamMembers()->updateOrCreate(
            ['email' => strtolower($user->email)],
            [
                'user_id' => $user->id,
                'name' => $user->name,
                'role' => $role,
                'status' => OrganizerTeamMember::STATUS_ACTIVE,
                'invite_token' => null,
                'invited_by' => $organizer->user_id,
                'invited_at' => now(),
                'accepted_at' => now(),
                'deactivated_at' => null,
            ]
        );
    }
}
