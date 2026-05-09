<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizerTeamMemberStoreRequest;
use App\Http\Requests\OrganizerTeamMemberUpdateRequest;
use App\Models\OrganizerTeamMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->organizerProfile;

        return view('organizer.team-members.index', [
            'profile' => $profile,
            'teamMembers' => $profile->teamMembers()->with('user')->latest('id')->paginate(15),
            'roles' => OrganizerTeamMember::staffRoles(),
        ]);
    }

    public function store(OrganizerTeamMemberStoreRequest $request): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;
        $email = strtolower($request->input('email'));

        if (strtolower($profile->user->email) === $email) {
            return back()->withInput()->with('error', 'The organizer owner is already the account owner.');
        }

        $user = User::where('email', $email)->first();
        $existing = $profile->teamMembers()->withTrashed()->where('email', $email)->first();

        if ($existing && ! $existing->trashed()) {
            return back()->withInput()->with('error', 'This email is already invited to this organizer team.');
        }

        if ($existing && $existing->trashed()) {
            $existing->restore();
            $existing->update([
                'user_id' => $user?->id,
                'name' => $request->input('name') ?: $user?->name,
                'role' => $request->input('role'),
                'status' => $user ? OrganizerTeamMember::STATUS_ACTIVE : OrganizerTeamMember::STATUS_PENDING,
                'invite_token' => $user ? null : Str::random(48),
                'invited_by' => $request->user()->id,
                'invited_at' => now(),
                'accepted_at' => $user ? now() : null,
                'deactivated_at' => null,
            ]);
        } else {
            $profile->teamMembers()->create([
                'user_id' => $user?->id,
                'email' => $email,
                'name' => $request->input('name') ?: $user?->name,
                'role' => $request->input('role'),
                'status' => $user ? OrganizerTeamMember::STATUS_ACTIVE : OrganizerTeamMember::STATUS_PENDING,
                'invite_token' => $user ? null : Str::random(48),
                'invited_by' => $request->user()->id,
                'invited_at' => now(),
                'accepted_at' => $user ? now() : null,
            ]);
        }

        return redirect()->route('organizer.team-members.index')->with('success', 'Team member invitation saved successfully.');
    }

    public function update(OrganizerTeamMemberUpdateRequest $request, OrganizerTeamMember $teamMember): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;
        abort_unless((int) $teamMember->organizer_profile_id === (int) $profile->id, 403);

        $teamMember->update([
            'role' => $request->input('role'),
            'status' => $request->input('status'),
            'deactivated_at' => $request->input('status') === OrganizerTeamMember::STATUS_INACTIVE ? now() : null,
            'accepted_at' => $request->input('status') === OrganizerTeamMember::STATUS_ACTIVE && $teamMember->accepted_at === null ? now() : $teamMember->accepted_at,
        ]);

        return redirect()->route('organizer.team-members.index')->with('success', 'Team member updated successfully.');
    }

    public function activate(Request $request, OrganizerTeamMember $teamMember): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;
        abort_unless((int) $teamMember->organizer_profile_id === (int) $profile->id, 403);

        if (! $teamMember->user_id) {
            return back()->with('error', 'A pending invite without a registered user cannot be activated yet.');
        }

        $teamMember->update([
            'status' => OrganizerTeamMember::STATUS_ACTIVE,
            'accepted_at' => $teamMember->accepted_at ?: now(),
            'deactivated_at' => null,
        ]);

        return redirect()->route('organizer.team-members.index')->with('success', 'Team member activated.');
    }

    public function deactivate(Request $request, OrganizerTeamMember $teamMember): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;
        abort_unless((int) $teamMember->organizer_profile_id === (int) $profile->id, 403);

        $teamMember->update([
            'status' => OrganizerTeamMember::STATUS_INACTIVE,
            'deactivated_at' => now(),
        ]);

        return redirect()->route('organizer.team-members.index')->with('success', 'Team member deactivated.');
    }

    public function destroy(Request $request, OrganizerTeamMember $teamMember): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;
        abort_unless((int) $teamMember->organizer_profile_id === (int) $profile->id, 403);
        $teamMember->delete();

        return redirect()->route('organizer.team-members.index')->with('success', 'Team member removed safely.');
    }
}
