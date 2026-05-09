<?php

namespace Tests\Feature;

use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizerOnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_become_organizer_page_loads(): void
    {
        $this->get(route('organizer.become'))->assertOk()->assertSee('Become an Organizer');
    }

    public function test_user_can_create_and_submit_organizer_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'web')
            ->post(route('organizer.profile.store'), [
                'organization_name' => 'New Organizer Company',
                'contact_person' => 'Test Person',
                'phone' => '01700000000',
                'email' => 'organizer.company@example.com',
                'website' => 'https://example.com',
                'address' => 'Dhaka',
                'description' => 'Organizer description.',
            ])
            ->assertRedirect(route('organizer.status'));

        $this->assertDatabaseHas('organizer_profiles', [
            'user_id' => $user->id,
            'organization_name' => 'New Organizer Company',
            'status' => OrganizerProfile::STATUS_DRAFT,
        ]);

        $this->actingAs($user, 'web')
            ->post(route('organizer.profile.submit'))
            ->assertRedirect(route('organizer.status'));

        $this->assertDatabaseHas('organizer_profiles', [
            'user_id' => $user->id,
            'status' => OrganizerProfile::STATUS_PENDING,
        ]);
    }
}
