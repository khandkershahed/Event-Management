<x-admin-app-layout :title="'User List'">

    <div class="card card-flash">
        <div class="card-header mt-6">
            <div class="card-title"></div>
            <div class="card-toolbar">

                {{-- @if (Auth::guard('admin')->user()->can('add.user')) --}}
                <a href="{{ route('admin.user.create') }}" class="btn btn-light-primary">
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
                    Add User
                </a>
                {{-- @endif --}}

            </div>
        </div>

        <div class="card-body pt-0">
            <table id="kt_datatable_example_5" class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                <thead class="bg-dark text-light">
                    <tr>
                        <th width="2%">No</th>
                        <th width="5%">Image</th>
                        <th width="10%">Name</th>
                        <th width="10%">Email</th>
                        <th width="5%">Status</th>
                        <th width="5%">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">

                    @foreach ($users as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td class="">
                                <img src="{{ !empty($user->image) ? url('storage/' . $user->image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                    height="40" width="40" alt="{{ $user->name }}">

                            </td>

                            <td class="text-start">{{ $user->name }}</td>
                            <td class="text-start">{{ $user->email }}</td>

                            <td class="text-start">
                                <p>
                                    <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </p>

                            </td>


                            <td>


                                {{-- @if (Auth::guard('admin')->user()->can('edit.user')) --}}
                                <a href="{{ route('admin.user.edit', $user->id) }}" class="text-primary">
                                    <i class="fa-solid fa-pencil text-primary"></i>
                                </a>
                                {{-- @endif

                                @if (Auth::guard('admin')->user()->can('delete.user')) --}}
                                <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border-0 bg-transparent">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </button>
                                </form>

                                {{-- @endif --}}

                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>

    </div>

    @push('scripts')
        <script>
            $("#kt_datatable_example_5").DataTable({
                "language": {
                    "lengthMenu": "Show _MENU_",
                },
                "dom": "<'row'" +
                    "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                    "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                    ">" +

                    "<'table-responsive'tr>" +

                    "<'row'" +
                    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                    ">"
            });
        </script>
    @endpush

</x-admin-app-layout>
