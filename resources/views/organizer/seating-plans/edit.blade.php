@extends('organizer.layouts.app', ['title' => 'Edit Seating Plan'])
@section('content')
<div class="card"><h3>Edit Seating Plan</h3><form method="POST" action="{{ route('organizer.seating-plans.update', $plan) }}">@method('PUT')@include('organizer.seating-plans._form')</form><div class="mt-3"><a class="btn btn-light" href="{{ route('organizer.seating-plans.designer', $plan) }}">Open Visual Seat Map Designer</a></div></div>
@endsection
