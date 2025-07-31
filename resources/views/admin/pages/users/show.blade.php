<x-admin-app-layout :title="'User Details of ' . $user->name">

    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
            <div class="card mb-5 mb-xl-8">
                <div class="card-body">
                    <div class="d-flex flex-center flex-column py-5">

                        <div class="symbol symbol-100px symbol-circle mb-7">
                            <img src="{{ asset('admin/assets/media/svg/avatars/blank-dark.svg') }}" alt="image" />
                        </div>
                        <a href="#"
                            class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-3">{{ $user->name }}</a>
                        {{-- <div class="mb-9">
                            <div class="badge badge-lg badge-light-primary d-inline">Roles</div>
                        </div> --}}
                        <div class="fw-bolder mb-3 fs-3">{{ $user->first_name }} {{ $user->last_name }}
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover"
                                data-bs-trigger="hover" data-bs-html="true"
                                data-bs-content="Your Profile Name"></i>
                        </div>
                    </div>
                    <div class="separator"></div>
                    <div class="collapse show" id="user_collapse_details_{{ $user->id }}">
                        <div class="pb-5 fs-6">
                            {{-- <div class="fw-bolder mt-5">Account ID</div>
                            <div class="text-gray-600">ID-45453423</div> --}}
                            <div class="fw-bolder mt-5">Email</div>
                            <div class="text-gray-600">
                                <a href="#"
                                    class="text-gray-600 text-hover-primary">{{ $user->email ?? 'Not Available' }}</a>
                            </div>
                            <div class="fw-bolder mt-5">Address</div>
                            <div class="text-gray-600">{{ $user->address ?? 'Not Available' }}</div>
                            <div class="fw-bolder mt-5">Language</div>
                            <div class="text-gray-600">English</div>
                            <div class="fw-bolder mt-5">Account Created At</div>
                            <div class="text-gray-600">{{ $user->created_at->format('d M Y, g:i a') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-lg-row-fluid ms-lg-8">
            <div class="card mb-lg-10 mb-5">
                <div class="card-body py-4">
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold align-items-center"
                        role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary px-4 py-3 active rounded-3" data-kt-countup-tabs="true"
                                data-bs-toggle="tab" href="#kt_user_view_overview_tab" aria-selected="true"
                                role="tab" tabindex="-1">Overview</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary px-4 py-3 rounded-3 ms-0" data-bs-toggle="tab"
                                href="#kt_user_view_overview_details" aria-selected="false" role="tab"
                                tabindex="-1">Details</a>
                        </li>
                        <li class="nav-item ms-auto">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <span class="text-info fw-bolder">User Status Update : &nbsp;&nbsp;</span>
                                <input class="form-check-input status-toggle" type="checkbox"
                                    id="status_toggle_{{ $user->id }}" @checked($user->status == 'active')
                                    data-id="{{ $user->id }}" />
                            </div>
                        </li>
                    </ul>
                </div>
            </div>



            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active" id="kt_user_view_overview_tab" role="tabpanel">
                    @include('admin.pages.users.partials.overview')
                </div>

                <div class="tab-pane fade" id="kt_user_view_overview_details" role="tabpanel">
                    @include('admin.pages.users.partials.details')
                </div>

            </div>

        </div>
    </div>


    @push('scripts')
        <script>
            $(document).on('change', '.status-toggle', function() {
                const id = $(this).data('id');
                const route = "{{ route('admin.user.toggle-status', ':id') }}".replace(':id', id);
                toggleStatus(route, id);
            });

            function toggleStatus(route, id) {
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        Swal.fire(
                            'User Status updated successfully!',
                            data.success,
                            'success'
                        ).then(function() {
                            location.reload(); // Reload the page to reflect changes
                        });
                    },
                    error: function(xhr) {
                        console.log('AJAX Error Response:', xhr
                            .responseText); // Log full response for debugging
                        let errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                            .responseJSON.error : 'An unexpected error occurred.';
                        Swal.fire(
                            'Oops...',
                            errorMessage,
                            'error'
                        );
                    }
                });
            }
        </script>
    @endpush



</x-admin-app-layout>
