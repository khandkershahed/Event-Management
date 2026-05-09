@extends('organizer.layouts.app', ['title' => 'Edit Venue'])
@section('content')
<div class="card"><h3>Edit Venue</h3><form method="POST" action="{{ route('organizer.venues.update', $venue) }}">@method('PUT')@include('organizer.venues._form')</form></div>
@endsection
