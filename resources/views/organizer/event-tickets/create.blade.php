@extends('organizer.layouts.app', ['title' => 'Create Ticket Type'])
@section('content')
<div class="card">
    <h3>Create Ticket Type</h3>
    <p style="color:#6b7280">Event: {{ $event->name }}</p>
    <form method="POST" action="{{ route('organizer.events.ticket-types.store', $event) }}">
        @csrf
        @include('organizer.event-tickets._form')
    </form>
</div>
@endsection
