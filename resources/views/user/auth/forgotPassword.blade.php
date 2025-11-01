<x-frontend-app-layout :title="'Forget Password'">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="text-center">Reset Your <span class="site-color">Password</span></h2>
                        <p class="pt-3">We understand that losing access to your account can be
                            frustrating. Please follow the steps below to recover your password and
                            regain access to your account. If you need any assistance, feel free to
                            contact our support team.</p>

                        @if (session('status'))
                            <div class="alert alert-success mb-0" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" class="mt-3 text-center">
                            @csrf

                            <!-- Email Address -->
                            <div class="form-group mt-5">
                                {{-- <label class="form-label">Your Email*</label> --}}
                                <input class="form-control h_50" type="email" name="email"
                                    placeholder="Enter your Registered email" required value="{{ old('email') }}" />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button class="main-btn btn-hover w-200px mt-4" type="submit">
                                Send Password Reset Link
                            </button>
                            <!-- Submit Button -->
                            {{-- <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-common-one">Send Password Reset Link</button>
                            </div> --}}

                            <!-- Back to Login -->
                            <div class="text-center mt-4">
                                <p>Remembered your password? <a href="{{ route('login') }}" class="login-link">Login</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
