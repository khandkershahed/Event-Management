{{-- <div class="container py-5 d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg border-0 rounded-4 p-5" style="max-width: 480px; width: 100%; background: #f9f9f9;">
        <div class="card-body">
            <!-- Logo or App Name (Optional) -->
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark">Welcome Back!</h3>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Input -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold text-muted">Email</label>
                    <input id="email" type="email" name="email" class="form-control form-control-lg shadow-sm"
                        required autocomplete="username" placeholder="Enter your email">
                    <x-input-error :messages="$errors->get('email')" class="text-danger mt-1" />
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold text-muted">Password</label>
                    <input id="password" type="password" name="password" class="form-control form-control-lg shadow-sm"
                        required autocomplete="current-password" placeholder="Enter your password">
                    <x-input-error :messages="$errors->get('password')" class="text-danger mt-1" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                        <label class="form-check-label text-muted" for="remember_me">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-decoration-none text-muted fw-semibold">Forgot password?</a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="btn btn-primary w-100 rounded-pill py-2 shadow-lg transition-all hover:scale-105">Log
                    In</button>
            </form>


        </div>
    </div>
</div> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Now</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #97989967;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 900px;
        }

        .card {
            border-radius: 5px;
            overflow: hidden;
        }

        .card-body {
            padding: 40px;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
        }

        .btn-common-one {
            background: #013c7a;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-common-one:hover {
            background: #0563c2;
            color: #fff;
        }

        .input-group-text {
            background: #f1f1f1;
            border-radius: 10px 0 0 10px;
            border: none;
        }

        .form-outline {
            position: relative;
        }

        .form-outline i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #aaa;
        }

        .form-control {
            padding-left: 20px;
        }

        .login-link {
            text-decoration: none;
            font-weight: bold;
            color: #007bff;
        }

        .login-link:hover {
            color: #0a0a0a;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="pb-1 text-center">Welcome Here!</p>
                        <h2 class="mb-4 fw-bold text-center">Login Now</h2>

                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger mb-0">
                                <p class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <span class="mb-0">{{ $error }}</span>
                                    @endforeach
                                </p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="mt-3">
                            @csrf

                            <!-- Email -->
                            <div class="mb-3 form-outline">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" class="form-control" name="email"
                                    placeholder="Email Address" required value="{{ old('email') }}">
                            </div>

                            <!-- Password -->
                            <div class="row">
                                <div class="col-md-12 mb-3 form-outline">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="Password" required>
                                </div>
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-common-one">Login Now</button>
                            </div>

                            <!-- No Registered? -->
                            <div class="text-center mt-4">
                                <p>No have any account? <a href="{{ route('register') }}"
                                        class="login-link">Register</a></p>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
