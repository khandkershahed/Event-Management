<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OrganizerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizerOnboardingController extends Controller
{
    public function become(): View
    {
        return view('frontend.pages.organizer.become');
    }

    public function profile(Request $request): View
    {
        $profile = $request->user()->organizerProfile;

        return view('frontend.pages.organizer.profile', compact('profile'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        $user = $request->user();

        $data['slug'] = OrganizerProfile::uniqueSlug($data['organization_name']);
        $data['status'] = OrganizerProfile::STATUS_DRAFT;

        $user->organizerProfile()->create($data);

        return redirect()->route('organizer.status')->with('success', 'Organizer profile saved. You can submit it for approval when ready.');
    }

    public function update(Request $request): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;
        abort_unless($profile, 404);

        $data = $request->validate($this->rules());
        $data['slug'] = OrganizerProfile::uniqueSlug($data['organization_name'], $profile->id);

        if (in_array($profile->status, [OrganizerProfile::STATUS_REJECTED, OrganizerProfile::STATUS_SUSPENDED], true)) {
            $data['status'] = OrganizerProfile::STATUS_DRAFT;
            $data['rejection_reason'] = null;
        }

        $profile->update($data);

        return redirect()->route('organizer.profile')->with('success', 'Organizer profile updated.');
    }

    public function submit(Request $request): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;
        abort_unless($profile, 404);

        if (! in_array($profile->status, [OrganizerProfile::STATUS_DRAFT, OrganizerProfile::STATUS_REJECTED], true)) {
            return redirect()->route('organizer.status');
        }

        $profile->update([
            'status' => OrganizerProfile::STATUS_PENDING,
            'submitted_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->route('organizer.status')->with('success', 'Organizer profile submitted for approval.');
    }

    public function status(Request $request): View
    {
        $profile = $request->user()->organizerProfile;

        return view('frontend.pages.organizer.status', compact('profile'));
    }

    private function rules(): array
    {
        return [
            'organization_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
