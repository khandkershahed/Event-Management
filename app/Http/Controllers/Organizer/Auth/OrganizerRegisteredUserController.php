<?php

namespace App\Http\Controllers\Organizer\Auth;

use App\Http\Controllers\Controller;
use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class OrganizerRegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('organizer.auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'organization_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'status' => 'active',
        ]);

        $profile = $user->organizerProfile()->create([
            'organization_name' => $data['organization_name'],
            'slug' => OrganizerProfile::uniqueSlug($data['organization_name']),
            'contact_person' => $data['contact_person'] ?: $data['name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'],
            'website' => $data['website'] ?? null,
            'address' => $data['address'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => OrganizerProfile::STATUS_PENDING,
            'submitted_at' => now(),
        ]);

        event(new Registered($user));

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect()->route('organizer.status')->with('success', 'Organizer account created. Your profile is pending admin approval.');
    }
}
