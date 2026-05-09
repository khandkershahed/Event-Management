@extends('organizer.layouts.app', ['title' => 'My Venues'])
@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h3>My Venues</h3>
        <a class="btn btn-primary" href="{{ route('organizer.venues.create') }}">Create Venue</a>
    </div>
    <table class="table">
        <thead><tr><th>Name</th><th>City</th><th>Capacity</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($venues as $venue)
            <tr>
                <td>{{ $venue->name }}</td><td>{{ $venue->city ?? '-' }}</td><td>{{ $venue->capacity ?? '-' }}</td>
                <td>
                    <a class="btn btn-light" href="{{ route('organizer.venues.show', $venue) }}">View</a>
                    <a class="btn btn-primary" href="{{ route('organizer.venues.edit', $venue) }}">Edit</a>
                    <form action="{{ route('organizer.venues.destroy', $venue) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this venue?')">@csrf @method('DELETE')<button class="btn btn-danger" type="submit">Delete</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No venues found.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $venues->links() }}
</div>
@endsection
