@extends('organizer.layouts.app', ['title' => 'Event Control Panel'])
@section('content')
    <div style="margin-bottom:14px"><a class="btn btn-light" href="{{ route('organizer.events.index') }}">Back to Events</a></div>
    @include('shared.event-control.panel', ['panel' => $panel, 'event' => $event])
@endsection
