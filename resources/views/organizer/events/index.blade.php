@extends('organizer.layouts.app', ['title' => 'My Events'])
@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h3>My Events</h3>
        <a class="btn btn-primary" href="{{ route('organizer.events.create') }}">Create Event</a>
    </div>
    <table class="table">
        <thead><tr><th>Name</th><th>Venue</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($events as $event)
            <tr>
                <td>{{ $event->name }}</td>
                <td>{{ optional($event->venueModel)->name ?? $event->venue ?? '-' }}</td>
                <td>{{ $event->start_date ? $event->start_date->format('Y-m-d') : '-' }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $event->status)) }}</td>
                <td>
                    <a class="btn btn-light" href="{{ route('organizer.events.show', $event) }}">View</a>
                    <a class="btn btn-light" href="{{ route('organizer.events.ticket-types.index', $event) }}">Tickets</a>
                    @if($event->canBeEditedByOrganizer())
                        <a class="btn btn-primary" href="{{ route('organizer.events.edit', $event) }}">Edit</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No events found.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $events->links() }}
</div>
@endsection
