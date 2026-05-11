@csrf
<div class="grid">
    <div class="mb-3"><label>Venue Name *</label><input class="form-control" type="text" name="name" value="{{ old('name', $venue->name ?? '') }}" required>@error('name')<small style="color:#dc2626">{{ $message }}</small>@enderror</div>
    <div class="mb-3"><label>Capacity</label><input class="form-control" type="number" name="capacity" value="{{ old('capacity', $venue->capacity ?? '') }}" min="0"></div>
</div>
<div class="grid">
    <div class="mb-3"><label>City</label><input class="form-control" type="text" name="city" value="{{ old('city', $venue->city ?? '') }}"></div>
    <div class="mb-3"><label>Country</label><input class="form-control" type="text" name="country" value="{{ old('country', $venue->country ?? '') }}"></div>
</div>
<div class="mb-3"><label>Address</label><textarea class="form-control" name="address" rows="3">{{ old('address', $venue->address ?? '') }}</textarea></div>
<div class="mb-3"><label>Description</label><textarea class="form-control" name="description" rows="4">{{ old('description', $venue->description ?? '') }}</textarea></div>
<div class="mb-3"><label>Venue Image</label><input class="form-control" type="file" name="image" accept="image/*">@if(!empty($venue->image))<small>Current: {{ $venue->image }}</small>@endif @error('image')<small style="color:#dc2626">{{ $message }}</small>@enderror</div>
<button class="btn btn-primary" type="submit">Save Venue</button>
<a class="btn btn-light" href="{{ route('organizer.venues.index') }}">Cancel</a>
