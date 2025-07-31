{{-- <div class="container py-5 mb-5 mb-lg-0">
    <div class="py-5 row">
        <div class="col-lg-10 offset-lg-1">
            <div class="border-0 card">
                <div class="p-0 border-0 shadow-sm card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-8 offset-lg-2">
                                    <div class="">
                                        <p class="pb-2">Welcome Here!</p>
                                        <h2 class="mb-5 fw-bold">Register Now</h2>
                                        <form method="POST" action="{{ route('register') }}">
                                            @csrf
                                            <!-- 2 column grid layout with text inputs for the first and last names -->
                                            <div class="row">
                                                <div class="mb-2 col-md-12 col-12">
                                                    <div class="form-outline">
                                                        <x-input-label class="form-label" for="name"
                                                            :value="__('Name')" />
                                                        <x-text-input id="name"
                                                            class="form-control form-control-solid" type="text"
                                                            name="name" :value="old('name')" required autofocus
                                                            autocomplete="name" placeholder="" />
                                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="mb-4 col-md-12 col-12">
                                                    <div class="form-outline">
                                                        <x-input-label class="form-label" for="email"
                                                            :value="__('Email')" />
                                                        <x-text-input id="email"
                                                            class="form-control form-control-solid" type="email"
                                                            name="email" :value="old('email')" required
                                                            autocomplete="username" />
                                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="mb-4 col-md-6 col-12">
                                                    <div class="form-outline">
                                                        <x-input-label class="form-label" for="password"
                                                            :value="__('Password')" />

                                                        <x-text-input id="password"
                                                            class="form-control form-control-solid" type="password"
                                                            placeholder="********" name="password" required
                                                            autocomplete="new-password" />
                                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="mb-4 col-md-6 col-12">
                                                    <div class="form-outline">
                                                        <x-input-label class="form-label" for="password_confirmation"
                                                            :value="__('Confirm Password')" />

                                                        <x-text-input id="password_confirmation"
                                                            class="form-control form-control-solid"
                                                            placeholder="********" type="password"
                                                            name="password_confirmation" required
                                                            autocomplete="new-password" />
                                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pt-4">
                                                <!-- Submit button -->
                                                <x-primary-button class="px-4 btn btn-common-one rounded-pill w-100">
                                                    {{ __('Register') }}
                                                </x-primary-button>
                                            </div>

                                            <!-- Checkbox -->
                                            <div
                                                class="pt-4 mb-2 form-check d-flex justify-content-center align-items-center">
                                                <h6 class="d-flex justify-content-start align-items-center">
                                                    {{ __('Already registered?') }}
                                                    <a href="{{ route('login') }}"
                                                        class="btn btn-sm btn-link text-gray fw-bold fs-6">Log In</a>
                                                </h6>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Now</title>
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
                        <h2 class="mb-4 fw-bold text-center">Register Now</h2>

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

                        <form method="POST" action="{{ route('register') }}" class="mt-3">
                            @csrf

                            <!-- Name -->
                            <div class="mb-3 form-outline">
                                <i class="fas fa-user"></i>
                                <input type="text" id="name" class="form-control" name="name"
                                    placeholder="Full Name" required value="{{ old('name') }}">
                            </div>

                            <!-- Email -->
                            <div class="mb-3 form-outline">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" class="form-control" name="email"
                                    placeholder="Email Address" required value="{{ old('email') }}">
                            </div>

                            <!-- Password -->
                            <div class="row">
                                <div class="col-md-6 mb-3 form-outline">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="Password" required>
                                </div>
                                <div class="col-md-6 mb-3 form-outline">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" id="password_confirmation" class="form-control"
                                        name="password_confirmation" placeholder="Confirm Password" required>
                                </div>
                            </div>

                            <!-- Register Button -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-common-one">Register</button>
                            </div>

                            <!-- Already Registered? -->
                            <div class="text-center mt-4">
                                <p>Already registered? <a href="{{ route('login') }}" class="login-link">Log In</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
