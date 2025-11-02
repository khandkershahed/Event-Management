<x-frontend-app-layout :title="'User Login'">
    <style>
        header {
            display: none;
        }

        .wrapper {
            margin-top: 0px;
        }

        footer {
            display: none;
        }
    </style>
    <div class="bg-white form-wrapper">
        <div class="app-form d-flex justify-content-between align-items-center">
            <div class="app-form-sidebar">
                <div class="sidebar-sign-logo">
                    <a href="{{ route('homepage') }}">
                        <img src="{{ !empty($setting->site_logo_black) && file_exists(public_path('storage/' . $setting->site_logo_black)) ? asset('storage/' . $setting->site_logo_black) : asset('images/logo.webp') }}" alt="">
                    </a>
                </div>
                <div class="sign_sidebar_text">
                    <h1>The Easiest Way to Create Events and Sell More Tickets Online</h1>
                </div>
            </div>
            <div class="app-form-content ">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10 col-md-10">
                            <div class="app-top-items">
                                <a href="{{ route('homepage') }}">
                                    <div class="sign-logo" id="logo">
                                        <img src="{{ !empty($setting->site_logo_black) && file_exists(public_path('storage/' . $setting->site_logo_black)) ? asset('storage/' . $setting->site_logo_black) : asset('images/logo.webp') }}" alt="">
                                        <img class="logo-inverse" src="{{ !empty($setting->site_logo_black) && file_exists(public_path('storage/' . $setting->site_logo_black)) ? asset('storage/' . $setting->site_logo_black) : asset('images/logo.webp') }}" alt="">
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-6 col-md-7">
                            <div class="registration">
                                <form method="POST" action="{{ route('register') }}" class="mt-3">
                                    @csrf
                                    <h2 class="registration-title">Sign up to {{ $setting->website_name }}</h2>
                                    <div class="mt-3 row">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="mt-4 form-group">
                                                <label class="form-label">Name*</label>
                                                <input type="text" id="name" class="form-control h_50" name="name"
                                                    placeholder="Full Name" required value="{{ old('name') }}">
                                                @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="mt-4 form-group">
                                                <label class="form-label">Phone</label>
                                                <input type="text" id="phone" class="form-control h_50" name="phone"
                                                    placeholder="Phone Number" required value="{{ old('phone') }}">
                                                @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="mt-4 form-group">
                                                <label class="form-label">Your Email*</label>
                                                <input type="email" id="email" class="form-control h_50" name="email"
                                                    placeholder="Your Email*" required value="{{ old('email') }}">
                                                @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="mt-4 form-group">
                                                <div class="field-password">
                                                    <label class="form-label">Password*</label>
                                                </div>
                                                <div class="loc-group position-relative">
                                                    <input type="password" id="password" class="form-control h_50" name="password"
                                                        placeholder="Password" required />
                                                    <span class="pass-show-eye"><i class="fas fa-eye-slash"></i></span>
                                                </div>
                                                @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="mt-4 form-group">
                                                <div class="field-password">
                                                    <label class="form-label">Confirm Password*</label>
                                                </div>
                                                <div class="loc-group position-relative">
                                                    <input type="password" id="password_confirmation" class="form-control h_50"
                                                        name="password_confirmation" placeholder="Confirm Password" required>
                                                    <span class="pass-show-eye"><i class="fas fa-eye-slash"></i></span>
                                                </div>
                                                @error('password_confirmation')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <button class="mt-4 main-btn btn-hover w-100" type="submit">
                                                Sign Up
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="mt-5 text-center app-top-right-link">
                                    Already have an account?<a class="sidebar-register-link" href="{{ route('login') }}">Sign In</a>
                                </div>
                                <div class="new-sign-link">
                                    New to Barren?<button class="signup-link" type="submit">Sign up</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="copyright-footer">
                    © 2025, <strong>FlixzaGlobal</strong>. All rights reserved. Powered
                    by {{ $setting->website_name }}
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        $(document).ready(function() {
            // =============================
            // Toggle Password Visibility
            // =============================
            $('.pass-show-eye').on('click', function() {
                const input = $(this).siblings('input');
                const icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });

            // =====================================
            // Realtime Confirm Password Checker
            // =====================================
            $('#password, #password_confirmation').on('keyup', function() {
                const password = $('#password').val();
                const confirm = $('#password_confirmation').val();

                // Remove previous message if exists
                $('#password_match_message').remove();

                if (confirm.length > 0) {
                    if (password === confirm) {
                        $('#password_confirmation')
                            .after(
                                '<span id="password_match_message" class="mt-1 text-success d-block">✅ Passwords match</span>'
                            );
                    } else {
                        $('#password_confirmation')
                            .after(
                                '<span id="password_match_message" class="mt-1 text-danger d-block">❌ Passwords do not match</span>'
                            );
                    }
                }
            });
        });
    </script>
    @endpush
</x-frontend-app-layout>