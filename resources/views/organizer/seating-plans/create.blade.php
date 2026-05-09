@extends('organizer.layouts.app', ['title' => 'Create Seating Plan'])
@section('content')
<div class="card"><h3>Create Seating Plan</h3><form method="POST" action="{{ route('organizer.seating-plans.store') }}">@include('organizer.seating-plans._form')</form></div>
@endsection
