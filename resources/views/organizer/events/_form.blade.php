@csrf
<div class="mb-3">
    <label>Event Name *</label>
    <input class="form-control" type="text" name="name" value="{{ old('name', $event->name ?? '') }}" required>
    @error('name')<small style="color:#dc2626">{{ $message }}</small>@enderror
</div>
<div class="mb-3">
    <label>Event Type</label>
    <select class="form-control" name="event_type_id">
        <option value="">Select event type</option>
        @foreach($eventTypes as $type)
            <option value="{{ $type->id }}" @selected((string) old('event_type_id', $event->event_type_id ?? '') === (string) $type->id)>{{ $type->name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label>Venue *</label>
    <select class="form-control" name="venue_id" required>
        <option value="">Select your venue</option>
        @foreach($venues as $venue)
            <option value="{{ $venue->id }}" @selected((string) old('venue_id', $event->venue_id ?? '') === (string) $venue->id)>{{ $venue->name }}{{ $venue->city ? ' - '.$venue->city : '' }}</option>
        @endforeach
    </select>
    @error('venue_id')<small style="color:#dc2626">{{ $message }}</small>@enderror
</div>
<div class="mb-3">
    <label>Seating Plan</label>
    <select class="form-control" name="seating_plan_id">
        <option value="">General admission / no seating plan</option>
        @foreach($seatingPlans as $plan)
            <option value="{{ $plan->id }}" @selected((string) old('seating_plan_id', $event->seating_plan_id ?? '') === (string) $plan->id)>{{ $plan->name }} ({{ ucfirst($plan->status) }})</option>
        @endforeach
    </select>
    @error('seating_plan_id')<small style="color:#dc2626">{{ $message }}</small>@enderror
</div>
<div class="mb-3">
    <label>Tagline</label>
    <input class="form-control" type="text" name="tagline" value="{{ old('tagline', $event->tagline ?? '') }}">
</div>
<div class="mb-3">
    <label>Description</label>
    <textarea class="form-control" name="description" rows="4">{{ old('description', $event->description ?? '') }}</textarea>
</div>
<div class="grid">
    <div class="mb-3">
        <label>Start Date</label>
        <input class="form-control" type="date" name="start_date" value="{{ old('start_date', optional($event->start_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="mb-3">
        <label>End Date</label>
        <input class="form-control" type="date" name="end_date" value="{{ old('end_date', optional($event->end_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="mb-3">
        <label>Start Time</label>
        <input class="form-control" type="time" name="start_time" value="{{ old('start_time', $event->start_time ?? '') }}">
    </div>
    <div class="mb-3">
        <label>End Time</label>
        <input class="form-control" type="time" name="end_time" value="{{ old('end_time', $event->end_time ?? '') }}">
    </div>
</div>
<div class="grid">
    <div class="mb-3">
        <label>Total Capacity</label>
        <input class="form-control" type="number" name="total_capacity" min="0" value="{{ old('total_capacity', $event->total_capacity ?? '') }}">
    </div>
    <div class="mb-3">
        <label>Age Restriction</label>
        <input class="form-control" type="text" name="age_restriction" value="{{ old('age_restriction', $event->age_restriction ?? '') }}">
    </div>
    <div class="mb-3">
        <label>Purchase Deadline</label>
        <input class="form-control" type="datetime-local" name="purchase_deadline" value="{{ old('purchase_deadline', optional($event->purchase_deadline ?? null)->format('Y-m-d\TH:i')) }}">
    </div>
</div>
<div class="mb-3">
    <label>Terms & Conditions</label>
    <textarea class="form-control" name="terms_and_conditions" rows="3">{{ old('terms_and_conditions', $event->terms_and_conditions ?? '') }}</textarea>
</div>
<button class="btn btn-primary" type="submit">Save Draft</button>
<a class="btn btn-light" href="{{ route('organizer.events.index') }}">Cancel</a>
