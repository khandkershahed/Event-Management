<x-admin-app-layout :title="'Role'">

    {{-- @if (Auth::guard('admin')->user()->can('all.role')) --}}
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container">
                <!--begin::Products-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="">
                        <!--begin::Card toolbar-->
                        <div class="float-end m-4">
                            <!--begin::Add product-->
                            <a href="" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addModal">Add Role</a>
                        </div>

                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table id="kt_datatable_example_5"
                            class="table table-striped table-row-bordered gy-5 gs-7 border rounded">

                            <!--begin::Table head-->
                            <thead class="bg-dark text-light">
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 gs-0">

                                    <th>Sl</th>
                                    <th>Role Name</th>
                                    <th>Action</th>

                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->

                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">

                                <!--begin::Table row-->
                                @foreach ($roles as $key => $role)
                                    <tr>

                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $role->name }}</td>

                                        <td>

                                            <a href="" title="Edit" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $role->id }}"><i
                                                    class="bi bi-pencil-square fs-3 text-primary"></i></a>

                                            {{-- Edit Modal --}}

                                            <div class="modal fade" id="editModal{{ $role->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit
                                                                Role
                                                            </h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <form action="{{ route('update.role') }}" method="POST"
                                                            id="myForm">

                                                            @csrf

                                                            <input type="hidden" name="id"
                                                                value="{{ $role->id }}">

                                                            <div class="modal-body">

                                                                <div class="form-group">
                                                                    <label for="">Role Name</label>
                                                                    <input type="text" name="name"
                                                                        value="{{ $role->name }}" autocomplete="off"
                                                                        placeholder="Role Name" required
                                                                        class="form-control">
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit"
                                                                    class="btn btn-primary btn-sm">Update
                                                                    Role</button>
                                                            </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                            <a href="{{ route('delete.role', $role->id) }}" title="Delete"
                                                id="delete"><i class="fa fa-trash fs-4 text-danger"></i></a>
                                        </td>

                                    </tr>
                                @endforeach
                                <!--end::Table row-->


                            </tbody>
                            <!--end::Table body-->

                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->

                </div>
                <!--end::Products-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    {{-- @endif --}}


    {{-- Add Role --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('store.role') }}" method="POST" id="myForm">

                    @csrf

                    <div class="modal-body">

                        <div class="form-group">
                            <label for="">Role Name</label>
                            <input type="text" name="name" autocomplete="off" placeholder="Role Name" required
                                class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    {{-- Add Role --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    {{-- validate code  --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: 'Enter Role Name',
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });
    </script>

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
