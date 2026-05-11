@csrf
<div class="card" style="box-shadow:none;border:1px solid #e5e7eb">
    <h3>1. Basic Details</h3>
    <div class="mb-3">
        <label>Event Name *</label>
        <input class="form-control" type="text" name="name" value="{{ old('name', $event->name ?? '') }}" required>
        @error('name')<small style="color:#dc2626">{{ $message }}</small>@enderror
    </div>
    <div class="grid">
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
            <label>Total Capacity</label>
            <input class="form-control" type="number" name="total_capacity" min="0" value="{{ old('total_capacity', $event->total_capacity ?? '') }}">
        </div>
        <div class="mb-3">
            <label>Age Restriction</label>
            <input class="form-control" type="text" name="age_restriction" value="{{ old('age_restriction', $event->age_restriction ?? '') }}">
        </div>
    </div>
    <div class="mb-3">
        <label>Tagline</label>
        <input class="form-control" type="text" name="tagline" value="{{ old('tagline', $event->tagline ?? '') }}">
    </div>
</div>

<div class="card" style="box-shadow:none;border:1px solid #e5e7eb">
    <h3>2. Media</h3>
    <p style="color:#64748b">Upload the main event images. Existing images stay unchanged when you leave a file input empty.</p>
    <div class="grid">
        <div class="mb-3"><label>Logo</label><input class="form-control" type="file" name="logo" accept="image/*">@if(!empty($event->logo))<small>Current: {{ $event->logo }}</small>@endif</div>
        <div class="mb-3"><label>Main Image</label><input class="form-control" type="file" name="image" accept="image/*">@if(!empty($event->image))<small>Current: {{ $event->image }}</small>@endif</div>
        <div class="mb-3"><label>Banner Image</label><input class="form-control" type="file" name="banner_image" accept="image/*">@if(!empty($event->banner_image))<small>Current: {{ $event->banner_image }}</small>@endif</div>
        <div class="mb-3"><label>Venue Image</label><input class="form-control" type="file" name="venue_image" accept="image/*">@if(!empty($event->venue_image))<small>Current: {{ $event->venue_image }}</small>@endif</div>
        <div class="mb-3"><label>Organizer Logo</label><input class="form-control" type="file" name="organizer_logo" accept="image/*">@if(!empty($event->organizer_logo))<small>Current: {{ $event->organizer_logo }}</small>@endif</div>
    </div>
    <div class="mb-3"><label>Video Teaser URL</label><input class="form-control" type="url" name="video_teaser_url" value="{{ old('video_teaser_url', $event->video_teaser_url ?? '') }}"></div>
</div>

<div class="card" style="box-shadow:none;border:1px solid #e5e7eb">
    <h3>3. Content</h3>
    <div class="mb-3"><label>Description</label><textarea class="form-control" name="description" rows="5">{{ old('description', $event->description ?? '') }}</textarea></div>
    <div class="mb-3"><label>Terms & Conditions</label><textarea class="form-control" name="terms_and_conditions" rows="4">{{ old('terms_and_conditions', $event->terms_and_conditions ?? '') }}</textarea></div>
</div>

<div class="card" style="box-shadow:none;border:1px solid #e5e7eb">
    <h3>4. Time & Venue</h3>
    <div class="grid">
        <div class="mb-3"><label>Start Date</label><input class="form-control" type="date" name="start_date" value="{{ old('start_date', optional($event->start_date ?? null)->format('Y-m-d')) }}"></div>
        <div class="mb-3"><label>End Date</label><input class="form-control" type="date" name="end_date" value="{{ old('end_date', optional($event->end_date ?? null)->format('Y-m-d')) }}"></div>
        <div class="mb-3"><label>Start Time</label><input class="form-control" type="time" name="start_time" value="{{ old('start_time', $event->start_time ?? '') }}"></div>
        <div class="mb-3"><label>End Time</label><input class="form-control" type="time" name="end_time" value="{{ old('end_time', $event->end_time ?? '') }}"></div>
    </div>
    <div class="grid">
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
    </div>
    <div class="mb-3"><label>Location Map URL</label><input class="form-control" type="url" name="location_map_url" value="{{ old('location_map_url', $event->location_map_url ?? '') }}"></div>
</div>

<div class="card" style="box-shadow:none;border:1px solid #e5e7eb">
    <h3>5. Organizer Branding & Settings</h3>
    <div class="grid">
        <div class="mb-3"><label>Organizer Name</label><input class="form-control" type="text" name="organizer_name" value="{{ old('organizer_name', $event->organizer_name ?? '') }}"></div>
        <div class="mb-3"><label>Organizer Brand</label><input class="form-control" type="text" name="organizer_brand" value="{{ old('organizer_brand', $event->organizer_brand ?? '') }}"></div>
        <div class="mb-3"><label>Purchase Deadline</label><input class="form-control" type="datetime-local" name="purchase_deadline" value="{{ old('purchase_deadline', optional($event->purchase_deadline ?? null)->format('Y-m-d\TH:i')) }}"></div>
    </div>
</div>
<button class="btn btn-primary" type="submit">Save and Continue</button>
@if(!empty($event->id))
    <a class="btn btn-light" href="{{ route('organizer.events.control', $event) }}">Back to Event Control Panel</a>
@endif
<a class="btn btn-light" href="{{ route('organizer.events.index') }}">Cancel</a>
