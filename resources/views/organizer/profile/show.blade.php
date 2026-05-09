@extends('organizer.layouts.app', ['title' => 'Approved Organizer Profile'])
@section('content')<div class="card"><h3>{{ $profile->organization_name }}</h3><p>Status: {{ ucfirst($profile->status) }}</p><p>{{ $profile->description }}</p></div>@endsection
