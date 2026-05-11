<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Login</title>
    <style>body{font-family:Arial,sans-serif;background:#f3f4f6;margin:0}.auth-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}.auth-card{width:100%;max-width:460px;background:#fff;border-radius:14px;padding:30px;box-shadow:0 12px 30px rgba(15,23,42,.08)}label{display:block;margin:14px 0 6px;font-weight:600}.form-control{width:100%;box-sizing:border-box;padding:12px;border:1px solid #d1d5db;border-radius:8px}.btn{display:inline-block;border:0;border-radius:8px;padding:11px 16px;text-decoration:none;cursor:pointer}.btn-primary{background:#f59e0b;color:#fff}.btn-light{background:#eef2f7;color:#111827}.error{color:#dc2626;font-size:13px}.hint{color:#64748b;font-size:14px;line-height:1.5}</style>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <h1>Organizer Login</h1>
        <p class="hint">Use this page only for organizer owners and organizer team members. Customers should use the normal customer login page.</p>
        @if(session('success'))<p style="color:#15803d">{{ session('success') }}</p>@endif
        @if(session('error'))<p class="error">{{ session('error') }}</p>@endif
        <form method="POST" action="{{ route('organizer.login.store') }}">
            @csrf
            <label>Email</label>
            <input class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')<div class="error">{{ $message }}</div>@enderror
            <label>Password</label>
            <input class="form-control" type="password" name="password" required>
            @error('password')<div class="error">{{ $message }}</div>@enderror
            <label style="font-weight:400"><input type="checkbox" name="remember" value="1"> Remember me</label>
            <div style="display:flex;gap:10px;align-items:center;margin-top:18px;flex-wrap:wrap">
                <button class="btn btn-primary" type="submit">Login to Organizer Panel</button>
                <a class="btn btn-light" href="{{ route('organizer.register') }}">Register as Organizer</a>
                <a class="btn btn-light" href="{{ route('login') }}">Customer Login</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
