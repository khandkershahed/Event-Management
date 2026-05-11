@csrf
<div class="card" style="box-shadow:none;border:1px solid #e5e7eb;background:#f8fafc"><strong>Simple seating plan setup</strong><p style="margin-bottom:0;color:#64748b">First save the plan details. Then open the Visual Seat Map Designer to draw sections, general admission areas, tables, and seats.</p></div>
<div class="mb-3">
    <label>Seating Plan Name *</label>
    <input class="form-control" type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" required>
    @error('name')<small style="color:#dc2626">{{ $message }}</small>@enderror
</div>
<div class="mb-3">
    <label>Venue *</label>
    <select class="form-control" name="venue_id" required>
        <option value="">Select venue</option>
        @foreach($venues as $venue)
            <option value="{{ $venue->id }}" @selected((string) old('venue_id', $plan->venue_id ?? '') === (string) $venue->id)>{{ $venue->name }}{{ $venue->city ? ' - '.$venue->city : '' }}</option>
        @endforeach
    </select>
    @error('venue_id')<small style="color:#dc2626">{{ $message }}</small>@enderror
</div>
<div class="mb-3">
    <label>Status</label>
    <select class="form-control" name="status">
        <option value="draft" @selected(old('status', $plan->status ?? 'draft') === 'draft')>Draft</option>
        <option value="active" @selected(old('status', $plan->status ?? 'draft') === 'active')>Active</option>
        <option value="archived" @selected(old('status', $plan->status ?? 'draft') === 'archived')>Archived</option>
    </select>
    <small>Locked seating plans cannot be edited from organizer tools.</small>
    @error('status')<small style="color:#dc2626">{{ $message }}</small>@enderror
</div>
<button class="btn btn-primary" type="submit">Save Seating Plan</button>
<a class="btn btn-light" href="{{ route('organizer.seating-plans.index') }}">Cancel</a>
