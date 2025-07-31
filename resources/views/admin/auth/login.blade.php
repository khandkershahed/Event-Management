<x-admin-guest-layout>
    <div class="d-flex flex-column flex-lg-row flex-column-fluid min-vh-100"
        style="background: linear-gradient(135deg, #222935, #0f1924);">

        <div class="d-flex flex-column flex-lg-row-fluid py-10">
            <div class="d-flex flex-center flex-column flex-column-fluid">
                <div class="w-lg-600px p-15 p-lg-20 mx-auto bg-white shadow-lg rounded-3">

                    <!-- Welcome Title -->
                    <h1 class="fw-bold text-center mb-5 text-dark mb-15">
                        Welcome to {{ $setting->site_name ?? config('app.name') }}
                    </h1>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Login Form -->
                    <form class="form w-100" action="{{ route('admin.login') }}" method="POST">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-4">
                            <x-metronic.label
                                class="form-label fw-bold text-dark">{{ __('Email') }}</x-metronic.label>
                            <x-metronic.input type="email" name="email"
                                class="form-control form-control-lg rounded-3 shadow-sm" placeholder="Enter your email"
                                value="{{ old('email') }}" autocomplete="off"></x-metronic.input>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-8">
                            <x-metronic.label
                                class="form-label fw-bold text-dark">{{ __('Password') }}</x-metronic.label>
                            <div class="position-relative">
                                <x-metronic.input type="password" name="password" id="passwordField"
                                    class="form-control form-control-lg rounded-3 shadow-sm"
                                    placeholder="Enter your password" autocomplete="off">
                                </x-metronic.input>
                                <button type="button"
                                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                    onclick="togglePasswordVisibility()">
                                    <i id="eyeIcon" class="bi bi-eye-slash fs-4 text-muted"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-8">
                            <div class="form-check">
                                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                <x-metronic.label for="remember_me" class="form-check-label text-dark">
                                    {{ __('Remember me') }}
                                </x-metronic.label>
                            </div>
                            @if (Route::has('admin.password.request'))
                                <a href="{{ route('admin.password.request') }}"
                                    class="text-primary text-decoration-none fw-bold">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <x-metronic.button type="submit" class="btn btn-dark btn-lg w-100 rounded-3 shadow">
                                {{ __('Login Here') }}
                            </x-metronic.button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function togglePasswordVisibility() {
                const passwordField = document.getElementById('passwordField');
                const eyeIcon = document.getElementById('eyeIcon');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
                } else {
                    passwordField.type = 'password';
                    eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
                }
            }
        </script>
    @endpush
</x-admin-guest-layout>
