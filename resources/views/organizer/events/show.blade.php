@extends('organizer.layouts.app', ['title' => 'Event Details'])
@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <h3>{{ $event->name }}</h3>
        <span>{{ ucwords(str_replace('_', ' ', $event->status)) }}</span>
    </div>
    <p><strong>Venue:</strong> {{ optional($event->venueModel)->name ?? $event->venue ?? '-' }}</p>
    <p><strong>Seating Plan:</strong> {{ optional($event->seatingPlan)->name ?? 'General admission' }}</p>
    <p><strong>Date:</strong> {{ $event->start_date ? $event->start_date->format('Y-m-d') : '-' }}</p>
    <p><strong>Description:</strong> {{ $event->description ?: '-' }}</p>
    @if($event->rejection_reason)
        <div class="card" style="border-left:4px solid #dc2626"><strong>Rejection Reason:</strong> {{ $event->rejection_reason }}</div>
    @endif
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a class="btn btn-light" href="{{ route('organizer.events.index') }}">Back</a>
        <a class="btn btn-primary" href="{{ route('organizer.events.control', $event) }}">Open Event Control Panel</a>
        <a class="btn btn-primary" href="{{ route('organizer.events.ticket-types.index', $event) }}">Manage Ticket Types</a>
        @if($event->canBeEditedByOrganizer())
            <a class="btn btn-primary" href="{{ route('organizer.events.edit', $event) }}">Edit</a>
            <form method="POST" action="{{ route('organizer.events.submit', $event) }}">@csrf <button class="btn btn-primary" type="submit">Submit for Review</button></form>
        @endif
        @if($event->canBePublishedByOrganizer())
            <form method="POST" action="{{ route('organizer.events.publish', $event) }}">@csrf <button class="btn btn-primary" type="submit">Publish Event</button></form>
        @endif
        @if($event->status === \App\Models\Event::STATUS_DRAFT)
            <form method="POST" action="{{ route('organizer.events.destroy', $event) }}" onsubmit="return confirm('Delete this draft event?')">@csrf @method('DELETE') <button class="btn btn-danger" type="submit">Delete Draft</button></form>
        @endif
    </div>
</div>
@endsection
