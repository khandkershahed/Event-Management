<x-admin-app-layout :title="'User Edit'">
    <div class="card card-flash">
        <!--begin::Card header-->
        <div class="card-header mt-6">
            <div class="card-title"></div>

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('admin.user.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                fill="currentColor" />
                            <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                            <rect x="6.01041" y="10.9247" width="12" height="2" rx="1"
                                fill="currentColor" />
                        </svg>
                    </span>
                    Back to the list
                </a>
            </div>
        </div>

        <!--begin::Card body-->
        <div class="card-body pt-0">
            <form method="POST" action="{{ route('admin.user.update', $user->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Status -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select a Status') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option></option>
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </x-metronic.select-option>
                    </div>

                    <!-- Name -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="name"
                            class="col-form-label required fw-bold fs-6">{{ __('Name') }}</x-metronic.label>
                        <x-metronic.input id="name" type="text" name="name" placeholder="Enter the name"
                            value="{{ old('name', $user->name) }}"></x-metronic.input>
                    </div>

                    <!-- Email -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="email"
                            class="col-form-label required fw-bold fs-6">{{ __('Email') }}</x-metronic.label>
                        <x-metronic.input id="email" type="text" name="email" placeholder="Enter the email"
                            value="{{ old('email', $user->email) }}"></x-metronic.input>
                    </div>

                    <!-- Phone -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="phone"
                            class="col-form-label fw-bold fs-6">{{ __('Phone') }}</x-metronic.label>
                        <x-metronic.input id="phone" type="number" name="phone" placeholder="Enter the phone"
                            value="{{ old('phone', $user->phone) }}"></x-metronic.input>
                    </div>

                    <!-- Address -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="address"
                            class="col-form-label fw-bold fs-6">{{ __('Address') }}</x-metronic.label>
                        <x-metronic.input id="address" type="text" name="address" placeholder="Enter the address"
                            value="{{ old('address', $user->address) }}"></x-metronic.input>
                    </div>

                    <!-- Password -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="password"
                            class="col-form-label fw-bold fs-6">{{ __('Password') }}</x-metronic.label>
                        <x-metronic.input id="password" type="password" name="password"
                            placeholder="Enter the password" autocomplete="new-password"></x-metronic.input>
                        <small class="text-muted">Leave blank if you don't want to change the password.</small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="password_confirmation"
                            class="col-form-label fw-bold fs-6">{{ __('Confirm Password') }}</x-metronic.label>
                        <x-metronic.input id="password_confirmation" type="password" name="password_confirmation"
                            placeholder="Confirm password" autocomplete="new-password"></x-metronic.input>
                    </div>

                    <!-- Image -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="image"
                            class="col-form-label fw-bold fs-6">{{ __('Image') }}</x-metronic.label>
                        <x-metronic.file-input id="image" name="image"></x-metronic.file-input>
                        @if ($user->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $user->image) }}" alt="User Image" width="100">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-end pt-15">
                    <x-metronic.button type="submit"
                        class="dark rounded-1 px-5">{{ __('Update') }}</x-metronic.button>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
