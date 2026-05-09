@extends('organizer.layouts.app', ['title' => 'Edit Ticket Type'])
@section('content')
<div class="card">
    <h3>Edit Ticket Type</h3>
    <p style="color:#6b7280">Event: {{ $event->name }}</p>
    @if(! $ticket->canBeEditedSafely())
        <div class="card" style="border-left:4px solid #f59e0b;box-shadow:none">
            This ticket type already has sales. Price, quantity, type, section restrictions, and fee fields are locked for safety.
        </div>
    @endif
    <form method="POST" action="{{ route('organizer.events.ticket-types.update', [$event, $ticket]) }}">
        @csrf
        @method('PUT')
        @include('organizer.event-tickets._form')
    </form>
</div>
@endsection
