<x-admin-guest-layout>

    <div class="d-flex flex-column flex-column-fluid">

        <div class="d-flex flex-column flex-column-fluid text-center p-10 py-lg-15">

            <a href="{{ route('homePage') }}" class="mb-10 pt-lg-10">
                <img alt="Logo" src="{{ !empty(optional($setting)->site_logo_black) ? asset('storage/' . optional($setting)->site_logo_black) : asset('frontend/img/logo.png') }}" class="h-40px mb-5" />
            </a>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-2 fw-semibold small text-success">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif


            <div class="pt-lg-10 mb-10">

                <h1 class="fw-bolder fs-2qx text-gray-800 mb-7">Verify Your Email</h1>


                <div class="fs-3 fw-bold text-muted mb-10">We have sent an email to
                    <a href="#" class="link-primary fw-bolder">
                        {{ Auth::guard('admin')->user()->email }}
                    </a>
                    <br />pelase follow a link to verify your email.
                </div>


                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf

                    <div class="text-center mb-10">
                        <x-metronic.button type="submit" class="primary btn-lg fw-bolder">
                            {{ __('Log Out') }}
                        </x-metronic.button>
                    </div>
                </form>



                <form method="POST" action="{{ route('admin.verification.send') }}">
                    @csrf
                    <div class="fs-5">
                        <span class="fw-bold text-gray-700">Did’t receive an email?</span>
                        <button type="submit" class="link-primary badge badge-dark">Resend</button>
                    </div>
                </form>

            </div>


            <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px"
                style="background-image: url({{ asset('admin/assets/media/illustrations/sketchy-1/17.png') }}"></div>

        </div>




    </div>

</x-admin-guest-layout>
