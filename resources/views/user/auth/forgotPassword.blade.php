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
                                <h2 class="text-center">Reset Your <span class="site-color">Password</span></h2>
                                <p class="pt-3">We understand that losing access to your account can be
                                    frustrating. Please follow the steps below to recover your password and
                                    regain access to your account. If you need any assistance, feel free to
                                    contact our support team.</p>
                                @if (session('status'))
                                <div class="mb-0 alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                                @endif

                                <form method="POST" action="{{ route('password.email') }}" class="mt-3 text-center">
                                    @csrf
                                    <!-- Email Address -->
                                    <div class="mb-3 input-group">
                                        <input type="email" name="email" class="form-control h_50" placeholder="Enter your Registered email" aria-label="Enter your Registered email"
                                            aria-describedby="basic-addon2" value="{{ old('email') }}">
                                        @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <button class="input-group-text main-btn btn-hover" type="submit" id="basic-addon2"> Reset Link</button>
                                    </div>
                                    <!-- Back to Login -->
                                    <div class="mt-4 text-center">
                                        <p>Remembered your password? <a href="{{ route('login') }}" class="login-link">Login</a>
                                        </p>
                                    </div>
                                </form>
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
</x-frontend-app-layout>