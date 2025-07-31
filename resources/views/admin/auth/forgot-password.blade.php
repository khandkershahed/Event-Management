<x-admin-guest-layout>
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative"
            style="background-image:linear-gradient(45deg,#0a1d5b,#051225)">
            <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                <div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">
                    <a href="" class="py-9 mb-5">
                        <img alt="Logo" src="{{ asset('admin/assets/media/logos/logo-1.svg') }}" class="h-60px">
                    </a>
                    <h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #ffffff;">Welcome to Metronic</h1>
                    <p class="fw-bold fs-2" style="color: #ffffff;">Discover Amazing Metronic
                        <br>with great build tools
                    </p>
                </div>
                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px"
                    style="background-image: url(assets/media/illustrations/sketchy-1/13.png"></div>
            </div>
        </div>


        <div class="d-flex flex-column flex-lg-row-fluid py-10">

            <div class="d-flex flex-center flex-column flex-column-fluid">

                <div class="w-lg-500px p-10 p-lg-15 mx-auto">


                    <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework"
                        action="{{ route('admin.password.email') }}" method="POST">
                        @csrf


                        <div class="text-center mb-10">
                            <h1 class="text-dark mb-3">Forgot your password?</h1>
                            <div class="text-gray-400 fw-bold fs-4">
                                No problem. Just let us know your email address and we will email you a password reset
                                link that will allow you to choose a new one.
                            </div>
                        </div>

                        <div class="fv-row mb-10">
                            <x-metronic.label
                                class="form-label fs-6 fw-bolder text-dark">{{ __('Email') }}</x-metronic.label>

                            <x-metronic.input type="email" name="email"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Enter your email address" value="{{ old('email') }}"
                                autocomplete="off"></x-metronic.input>
                        </div>

                        <div class="text-center">

                            <x-metronic.button type="submit" class="primary btn-lg w-100 mb-5">
                                <span class="indicator-label"> {{ __('Email Password Reset Link') }}</span>
                            </x-metronic.button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>


</x-admin-guest-layout>
