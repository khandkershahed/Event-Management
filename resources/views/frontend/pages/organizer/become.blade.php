<x-frontend-app-layout :title="'Become Organizer'">
    <section class="container py-5">
        <h1>Become an Organizer</h1>
        <p>Create events, manage venues, and sell tickets after admin approval.</p>
        @auth
            <a href="{{ route('organizer.profile') }}" class="btn btn-primary">Create Organizer Profile</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">Login to Apply</a>
        @endauth
    </section>
</x-frontend-app-layout>
