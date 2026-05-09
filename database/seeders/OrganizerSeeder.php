<?php

namespace Database\Seeders;

use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrganizerSeeder extends Seeder
{
    public function run(): void
    {
        $pendingUser = User::updateOrCreate(
            ['email' => 'pending.organizer@example.com'],
            ['name' => 'Pending Organizer', 'password' => Hash::make('password')]
        );

        OrganizerProfile::updateOrCreate(
            ['user_id' => $pendingUser->id],
            [
                'organization_name' => 'Pending Events Ltd',
                'slug' => OrganizerProfile::uniqueSlug('Pending Events Ltd', $pendingUser->organizerProfile?->id),
                'contact_person' => 'Pending Organizer',
                'email' => $pendingUser->email,
                'phone' => '+8801700000001',
                'address' => 'Dhaka, Bangladesh',
                'description' => 'Demo pending organizer profile.',
                'status' => OrganizerProfile::STATUS_PENDING,
                'submitted_at' => now(),
            ]
        );

        $approvedUser = User::updateOrCreate(
            ['email' => 'approved.organizer@example.com'],
            ['name' => 'Approved Organizer', 'password' => Hash::make('password')]
        );

        OrganizerProfile::updateOrCreate(
            ['user_id' => $approvedUser->id],
            [
                'organization_name' => 'Approved Events Ltd',
                'slug' => OrganizerProfile::uniqueSlug('Approved Events Ltd', $approvedUser->organizerProfile?->id),
                'contact_person' => 'Approved Organizer',
                'email' => $approvedUser->email,
                'phone' => '+8801700000002',
                'address' => 'Chattogram, Bangladesh',
                'description' => 'Demo approved organizer profile.',
                'status' => OrganizerProfile::STATUS_APPROVED,
                'submitted_at' => now()->subDays(2),
                'approved_at' => now()->subDay(),
            ]
        );
    }
}
