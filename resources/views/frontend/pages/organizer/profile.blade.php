<x-frontend-app-layout :title="'Organizer Profile'">
    <section class="container py-5">
        <h1>Organizer Profile</h1>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form method="POST" action="{{ $profile ? route('organizer.profile.update') : route('organizer.profile.store') }}">
            @csrf
            @if($profile) @method('PUT') @endif
            <div class="mb-3"><label>Organization Name *</label><input class="form-control" name="organization_name" value="{{ old('organization_name', $profile->organization_name ?? '') }}" required></div>
            <div class="mb-3"><label>Contact Person</label><input class="form-control" name="contact_person" value="{{ old('contact_person', $profile->contact_person ?? '') }}"></div>
            <div class="mb-3"><label>Phone</label><input class="form-control" name="phone" value="{{ old('phone', $profile->phone ?? '') }}"></div>
            <div class="mb-3"><label>Email</label><input class="form-control" name="email" value="{{ old('email', $profile->email ?? '') }}"></div>
            <div class="mb-3"><label>Website</label><input class="form-control" name="website" value="{{ old('website', $profile->website ?? '') }}"></div>
            <div class="mb-3"><label>Address</label><textarea class="form-control" name="address">{{ old('address', $profile->address ?? '') }}</textarea></div>
            <div class="mb-3"><label>Description</label><textarea class="form-control" name="description">{{ old('description', $profile->description ?? '') }}</textarea></div>
            <button class="btn btn-primary" type="submit">Save Profile</button>
        </form>
        @if($profile && in_array($profile->status, ['draft', 'rejected'], true))
            <form method="POST" action="{{ route('organizer.profile.submit') }}" class="mt-3">@csrf<button class="btn btn-success" type="submit">Submit for Review</button></form>
        @endif
    </section>
</x-frontend-app-layout>
