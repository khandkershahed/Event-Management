<x-admin-guest-layout>

    <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
        style="background-image: url({{ asset('admin/assets/media/illustrations/sketchy-1/14.png') }}">

        <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">

            <a href="../../demo1/dist/index.html" class="mb-12">
                <img alt="Logo" src="{{ asset('admin/assets/media/logos/logo-1.svg') }}" class="h-40px" />
            </a>


            <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">


                <form class="form w-100" action="{{ route('admin.password.confirm') }}" method="POST">
                    @csrf


                    <div class="text-center mb-10">

                        <h1 class="text-dark mb-3">Confirm Password</h1>

                    </div>


                    <div class="fv-row mb-10">

                        <x-metronic.label
                            class="form-label fs-6 fw-bolder text-dark">{{ __('Password') }}</x-metronic.label>


                        <x-metronic.input type="password" name="password" class="form-control form-control-lg"
                            placeholder="Enter your password" required
                            autocomplete="current-password"></x-metronic.input>

                    </div>



                    <div class="text-center">

                        <x-metronic.button type="submit" class="primary w-100 mb-5">
                            <span class="indicator-label"> {{ __('Confirm') }}</span>
                        </x-metronic.button>

                    </div>

                </form>

            </div>

        </div>


        

    </div>

</x-admin-guest-layout>
