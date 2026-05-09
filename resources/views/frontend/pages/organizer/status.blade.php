<x-frontend-app-layout :title="'Organizer Status'">
    <section class="container py-5">
        <h1>Organizer Status</h1>
        @if($profile)
            <p><strong>Organization:</strong> {{ $profile->organization_name }}</p>
            <p><strong>Status:</strong> {{ ucfirst($profile->status) }}</p>
            @if($profile->rejection_reason)<p><strong>Reason:</strong> {{ $profile->rejection_reason }}</p>@endif
            @if($profile->isApproved())<a class="btn btn-primary" href="{{ route('organizer.dashboard') }}">Open Organizer Dashboard</a>@else<a class="btn btn-secondary" href="{{ route('organizer.profile') }}">Edit Profile</a>@endif
        @else
            <p>You have not created an organizer profile yet.</p>
            <a class="btn btn-primary" href="{{ route('organizer.profile') }}">Create Organizer Profile</a>
        @endif
    </section>
</x-frontend-app-layout>
