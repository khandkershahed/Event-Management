<x-admin-app-layout :title="'Subscription List'">


    <div class="card card-flash">
        {{-- <div class="card-header mt-6">
            <div class="card-title"></div>
            <div class="card-toolbar">

                @if (Auth::guard('admin')->user()->can('add.brand'))
                    <a href="{{ route('admin.contact.create') }}" class="btn btn-light-primary">
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
                        Add Contact
                    </a>
                @endif

            </div>
        </div> --}}

        <div class="card-body pt-5">
            <table id="kt_datatable_example_5" class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                <thead class="bg-dark text-light">
                    <tr>
                        <th width="2%">No</th>
                        <th width="10%">Name</th>
                        <th width="7%">Email</th>
                        <th width="5%">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">

                    @foreach ($subscriptions as $key => $subscription)
                        <tr>
                            <td>{{ $key + 1 }}</td>


                            <td class="text-start">{{ $subscription->name }}</td>
                            <td class="text-start">{{ $subscription->email }}</td>

                            <td class="text-start">
                                <p>
                                    <span class="badge bg-danger">
                                        {{ $subscription->status }}
                                    </span>
                                </p>

                            </td>


                            <td>
                                {{-- @if (Auth::guard('admin')->user()->can('edit.subscription')) --}}
                                {{-- <a href="{{ route('admin.subscription.edit', $subscription->id) }}" class="text-primary">
                                    <i class="fa-solid fa-edit text-primary me-1 fs-4"></i>
                                </a> --}}
                                {{-- @endif

                                @if (Auth::guard('admin')->user()->can('delete.subscription')) --}}
                                <a href="{{ route('admin.subscription.destroy', $subscription->id) }}" class="delete">
                                    <i class="fa-solid fa-trash text-danger fs-4"></i>
                                </a>
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
