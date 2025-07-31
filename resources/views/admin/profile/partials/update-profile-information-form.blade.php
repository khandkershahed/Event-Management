<div class="card card-flash">

    <div class="card-header border-0 cursor-pointer">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0"> {{ __('Profile Information') }} </h3>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('admin.verification.send') }}">
        @csrf
    </form>


    <form class="form" method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')
        <div class="card-body border-top p-9">
            <div class="row fv-row">
                <div class="col-lg-6 mb-7">
                    <x-metronic.label for="name"
                        class="required fw-bold fs-6 mb-2">{{ __('Full Name') }}</x-metronic.label>
                    <x-metronic.input id="name" type="text" class="form-control-solid mb-3 mb-lg-0"
                        name="name" :value="old('name', $user->name)" placeholder="Enter Full name"></x-metronic.input>
                </div>
                <div class="col-lg-6 mb-7">
                    <x-metronic.label for="email"
                        class="required fw-bold fs-6 mb-2">{{ __('Email') }}</x-metronic.label>
                    <x-metronic.input type="email" name="email"
                        class="form-control form-control-lg form-control-solid" placeholder="Enter your email address"
                        value="{{ old('email', $user->email) }}" autocomplete="off"></x-metronic.input>

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div>
                            <p class="small mt-2 text-secondary">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification"
                                    class="text-decoration-underline small text-secondary hover:text-dark rounded-0 focus:outline-none"
                                    style="border: none; box-shadow: none;" onclick="this.blur();">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 fw-semibold small text-success">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-lg-4 mb-7">
                    <x-metronic.label for="username" class="fw-bold fs-6 mb-2">{{ __('User Name') }}</x-metronic.label>
                    <x-metronic.input id="username" type="text" class="form-control-solid mb-3 mb-lg-0"
                        name="username" :value="old('username', $user->username)" placeholder="Enter User name"></x-metronic.input>
                </div>

                <div class="col-lg-4 mb-7">
                    <x-metronic.label for="designation"
                        class="fw-bold fs-6 mb-2">{{ __('Designation') }}</x-metronic.label>
                    <x-metronic.input id="designation" type="text" class="form-control-solid mb-3 mb-lg-0"
                        name="designation" :value="old('designation', $user->designation)" placeholder="Designation"></x-metronic.input>
                </div>
                <div class="col-lg-4 mb-7">
                    <x-metronic.label for="photo" class="fw-bold fs-6 mb-2">{{ __('Photo') }}</x-metronic.label>
                    <x-metronic.file-input id="photo" type="file" class="form-control-solid mb-3 mb-lg-0"
                        :source="asset('storage/' . $user->photo)" name="photo" :value="old('photo')"
                        placeholder="example@domain.com"></x-metronic.file-input>
                </div>


                <div class="mb-7">

                    <label class="required fw-bold fs-6 mb-5">Role</label>
                    @foreach ($roles as $role)
                        <div class="d-flex fv-row">
                            <div class="form-check form-check-custom form-check-solid">
                                <x-metronic.checkbox id="role-name-{{ $role->id }}" type="checkbox" name="roles[]"
                                    :value="$role->name"></x-metronic.checkbox>
                                <x-metronic.label for="role-name-{{ $role->id }}"
                                    class="form-check-label">{{ $role->name }}</x-metronic.label>

                            </div>
                        </div>
                    @endforeach
                </div>


            </div>
        </div>


        <div class="card-footer d-flex justify-content-end py-4 px-9">
            <x-metronic.button type="submit" class="primary">
                {{ __('Submit') }}
            </x-metronic.button>
        </div>

    </form>


</div>
