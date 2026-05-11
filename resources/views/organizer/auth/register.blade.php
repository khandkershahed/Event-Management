<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Registration</title>
    <style>body{font-family:Arial,sans-serif;background:#f3f4f6;margin:0}.auth-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}.auth-card{width:100%;max-width:760px;background:#fff;border-radius:14px;padding:30px;box-shadow:0 12px 30px rgba(15,23,42,.08)}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px}label{display:block;margin:12px 0 6px;font-weight:600}.form-control{width:100%;box-sizing:border-box;padding:12px;border:1px solid #d1d5db;border-radius:8px}.btn{display:inline-block;border:0;border-radius:8px;padding:11px 16px;text-decoration:none;cursor:pointer}.btn-primary{background:#f59e0b;color:#fff}.btn-light{background:#eef2f7;color:#111827}.error{color:#dc2626;font-size:13px}.hint{color:#64748b;font-size:14px;line-height:1.5}</style>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <h1>Create Organizer Account</h1>
        <p class="hint">After registration, your organizer profile will be sent for admin approval. You can log in and check the approval status anytime.</p>
        <form method="POST" action="{{ route('organizer.register.store') }}">
            @csrf
            <h3>Account Details</h3>
            <div class="grid">
                <div><label>Your Name *</label><input class="form-control" type="text" name="name" value="{{ old('name') }}" required>@error('name')<div class="error">{{ $message }}</div>@enderror</div>
                <div><label>Email *</label><input class="form-control" type="email" name="email" value="{{ old('email') }}" required>@error('email')<div class="error">{{ $message }}</div>@enderror</div>
                <div><label>Phone</label><input class="form-control" type="text" name="phone" value="{{ old('phone') }}">@error('phone')<div class="error">{{ $message }}</div>@enderror</div>
                <div><label>Organization Name *</label><input class="form-control" type="text" name="organization_name" value="{{ old('organization_name') }}" required>@error('organization_name')<div class="error">{{ $message }}</div>@enderror</div>
                <div><label>Password *</label><input class="form-control" type="password" name="password" required>@error('password')<div class="error">{{ $message }}</div>@enderror</div>
                <div><label>Confirm Password *</label><input class="form-control" type="password" name="password_confirmation" required></div>
            </div>
            <h3>Organizer Profile</h3>
            <div class="grid">
                <div><label>Contact Person</label><input class="form-control" type="text" name="contact_person" value="{{ old('contact_person') }}"></div>
                <div><label>Website</label><input class="form-control" type="text" name="website" value="{{ old('website') }}"></div>
            </div>
            <label>Address</label><textarea class="form-control" name="address" rows="2">{{ old('address') }}</textarea>
            <label>Description</label><textarea class="form-control" name="description" rows="4">{{ old('description') }}</textarea>
            <div style="display:flex;gap:10px;align-items:center;margin-top:18px;flex-wrap:wrap">
                <button class="btn btn-primary" type="submit">Register Organizer</button>
                <a class="btn btn-light" href="{{ route('organizer.login') }}">Already registered? Login</a>
                <a class="btn btn-light" href="{{ route('login') }}">Customer Login</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
