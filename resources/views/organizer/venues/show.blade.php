@extends('organizer.layouts.app', ['title' => 'Venue Details'])
@section('content')
<div class="card">
    <h3>{{ $venue->name }}</h3>
    <p><strong>Address:</strong> {{ $venue->address ?? '-' }}</p>
    <p><strong>City:</strong> {{ $venue->city ?? '-' }}</p>
    <p><strong>Country:</strong> {{ $venue->country ?? '-' }}</p>
    <p><strong>Capacity:</strong> {{ $venue->capacity ?? '-' }}</p>
    <p>{{ $venue->description }}</p>
    <a class="btn btn-primary" href="{{ route('organizer.venues.edit', $venue) }}">Edit</a>
    <a class="btn btn-light" href="{{ route('organizer.venues.index') }}">Back</a>
</div>
@endsection
