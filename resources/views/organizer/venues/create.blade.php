@extends('organizer.layouts.app', ['title' => 'Create Venue'])
@section('content')
<div class="card"><h3>Create Venue</h3><form method="POST" action="{{ route('organizer.venues.store') }}">@include('organizer.venues._form')</form></div>
@endsection
