@extends('organizer.layouts.app', ['title' => 'Team Members'])
@section('content')
<div class="card">
    <h3>Invite Team Member</h3>
    <form method="POST" action="{{ route('organizer.team-members.store') }}">
        @csrf
        <div class="grid">
            <div>
                <label>Email</label>
                <input class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')<small style="color:#dc2626">{{ $message }}</small>@enderror
            </div>
            <div>
                <label>Name</label>
                <input class="form-control" type="text" name="name" value="{{ old('name') }}">
                @error('name')<small style="color:#dc2626">{{ $message }}</small>@enderror
            </div>
            <div>
                <label>Role</label>
                <select class="form-control" name="role" required>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" @selected(old('role') === $role)>{{ ucwords(str_replace('_', ' ', $role)) }}</option>
                    @endforeach
                </select>
                @error('role')<small style="color:#dc2626">{{ $message }}</small>@enderror
            </div>
        </div>
        <div style="margin-top:16px">
            <button class="btn btn-primary" type="submit">Invite / Add Staff</button>
        </div>
    </form>
</div>

<div class="card">
    <h3>Team Members</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $profile->user->name }}</td>
            <td>{{ $profile->user->email }}</td>
            <td>Owner</td>
            <td>Active</td>
            <td>Primary account owner</td>
        </tr>
        @forelse($teamMembers as $member)
            <tr>
                <td>{{ $member->name ?: optional($member->user)->name ?: '-' }}</td>
                <td>{{ $member->email }}</td>
                <td>
                    <form method="POST" action="{{ route('organizer.team-members.update', $member) }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                        @csrf
                        @method('PUT')
                        <select class="form-control" name="role" style="width:180px">
                            @foreach($roles as $role)
                                <option value="{{ $role }}" @selected($member->role === $role)>{{ ucwords(str_replace('_', ' ', $role)) }}</option>
                            @endforeach
                        </select>
                        <select class="form-control" name="status" style="width:140px">
                            <option value="active" @selected($member->status === 'active')>Active</option>
                            <option value="inactive" @selected($member->status === 'inactive')>Inactive</option>
                        </select>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
                </td>
                <td>{{ ucwords(str_replace('_', ' ', $member->status)) }}</td>
                <td style="display:flex;gap:8px;flex-wrap:wrap">
                    @if($member->status !== 'active')
                        <form method="POST" action="{{ route('organizer.team-members.activate', $member) }}">@csrf<button class="btn btn-light" type="submit">Activate</button></form>
                    @endif
                    @if($member->status !== 'inactive')
                        <form method="POST" action="{{ route('organizer.team-members.deactivate', $member) }}">@csrf<button class="btn btn-light" type="submit">Deactivate</button></form>
                    @endif
                    <form method="POST" action="{{ route('organizer.team-members.destroy', $member) }}" onsubmit="return confirm('Remove this team member?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Remove</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No staff members invited yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $teamMembers->links() }}
</div>
@endsection
