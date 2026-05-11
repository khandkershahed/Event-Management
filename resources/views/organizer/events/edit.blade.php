@extends('organizer.layouts.app', ['title' => 'Edit Event'])
@section('content')
<div class="card">
    <h3>Edit Event</h3>
    <form method="POST" action="{{ route('organizer.events.update', $event) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('organizer.events._form')
    </form>
</div>
@endsection
