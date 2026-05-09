@extends('organizer.layouts.app', ['title' => 'Create Event'])
@section('content')
<div class="card">
    <h3>Create Event Draft</h3>
    <form method="POST" action="{{ route('organizer.events.store') }}">
        @include('organizer.events._form')
    </form>
</div>
@endsection
