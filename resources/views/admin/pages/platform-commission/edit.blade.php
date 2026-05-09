<x-admin-app-layout :title="'Platform Commission'">
    <div class="card card-flush">
        <div class="card-header"><div class="card-title"><h2>Platform Commission Setting</h2></div></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.platform-commission.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-5">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $setting->name) }}" required>
                    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="mb-5">
                    <label class="form-label">Commission Type</label>
                    <select name="commission_type" class="form-select" required>
                        @foreach($types as $type)
                            <option value="{{ $type }}" @selected(old('commission_type', $setting->commission_type) === $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                    @error('commission_type')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="mb-5">
                    <label class="form-label">Commission Value</label>
                    <input type="number" step="0.01" min="0" name="commission_value" class="form-control" value="{{ old('commission_value', $setting->commission_value) }}" required>
                    @error('commission_value')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="mb-5">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $setting->description) }}</textarea>
                    @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-primary">Save Commission Setting</button>
            </form>
        </div>
    </div>
</x-admin-app-layout>
