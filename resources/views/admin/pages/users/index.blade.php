<x-admin-app-layout :title="'Users List'">
    <div class="card">
        <div class="border-0 card-header align-items-center bg-dark">
            <div>
                <div class="text-white card-title">Manage Your Users</div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <a href="{{ route('admin.user.create') }}" class="btn btn-primary rounded-1">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                    transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        Add User
                    </a>
                </div>
            </div>
        </div>
        <div class="py-4 card-body">
            <table class="table align-middle my-datatable table-row-dashed fs-6 gy-5" id="kt_table_users">
                <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th width="10%" class="ps-3">SL</th>
                        <th width="15%">Image</th>
                        <th width="25%">Name</th>
                        <th width="15%">Phone</th>
                        <th width="15%">Status</th>
                        <th width="15%" class="text-end min-w-100px pe-5">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold">
                    @foreach ($users as $user)
                        <tr>
                            <td class="ps-3">
                                {{ $loop->iteration }}
                            </td>
                            <td class="d-flex align-items-center">
                                <div class="overflow-hidden symbol symbol-circle symbol-50px me-3">
                                    <a href="javascript:void(0)">
                                        <div class="symbol-label"
                                            style="background-color: {{ $user->profile_image ? 'transparent' : '#d3d3d3' }}; display: flex; align-items: center; justify-content: center; width: 50px; height: 50px; border-radius: 50%; overflow: hidden;">

                                            @if ($user->profile_image)
                                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-100 h-100" />
                                            @else
                                                <img src="https://static.vecteezy.com/system/resources/thumbnails/005/545/335/small/user-sign-icon-person-symbol-human-avatar-isolated-on-white-backogrund-vector.jpg"
                                                    alt="Default Profile" class="p-2 border w-100 h-100 rounded-circle" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                                <span class="mb-1 text-gray-800 text-hover-primary"
                                                    style="display: none; font-size: 18px; font-weight: bold;">
                                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                    </a>

                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="javascript:void(0)"
                                        class="mb-1 text-gray-800 text-hover-primary">{{ $user->name }}</a>
                                </div>
                            </td>
                            <td>
                                <span>{{ $user->phone }}</span>
                            </td>
                            <td>
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input status-toggle" type="checkbox"
                                        id="status_toggle_{{ $user->id }}" @checked($user->status == 'active')
                                        data-id="{{ $user->id }}" />
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.user.show', $user->id) }}"
                                    class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-30px w-30px me-3">
                                    <i class="fa-solid fa-eye" title="User Details"></i>
                                </a>
                                <a href="{{ route('admin.user.edit', $user->id) }}"
                                    class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-30px w-30px me-3">
                                    <i class="fa-solid fa-pen" title="User Edit"></i>
                                </a>
                                <a href="{{ route('admin.user.destroy', $user->id) }}"
                                    class="btn btn-sm btn-icon btn-danger btn-active-light-danger toggle h-30px w-30px delete">
                                    <i class="fa-solid fa-trash-alt" title="User Delete"></i>
                                </a>

                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
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
                    success: function(response) {
                        if (response.success) {
                            alert('Status updated successfully!');
                            table.ajax.reload(null, false); // Reload the DataTable
                        } else {
                            alert('Failed to update status.');
                        }
                    },
                    error: function() {
                        alert('An error occurred while updating the status.');
                    }
                });
            }
        </script>
    @endpush
</x-admin-app-layout>
