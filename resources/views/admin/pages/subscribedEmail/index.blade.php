<x-admin-app-layout :title="'Subscribed Emails List'">
    <div class="card card-flush">
        <div class="card-body table-responsive">
            <table class="kt_datatable_example table align-middle table-row-dashed fs-6 gy-5 mb-0 datatable" id="kt_example_table">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th width="5%" class="text-center">Sl No.</th>
                        <th width="45%" class="text-center">Email</th>
                        <th width="20%" class="text-center">IP Address</th>
                        <th width="15%" class="text-center">Subscribe Date</th>
                        <th width="15" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600 text-center">
                    @foreach ($emails as $email)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $email->email }}</td>
                            <td>{{ $email->ip_address }}</td>
                            <td>{{ $email->created_at ->format('d-M-Y')}}</td>
                            <td class="text-center">
                                {{-- <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3"
                                    data-bs-toggle="modal" data-bs-target="#replayEmail">
                                    <span class="svg-icon svg-icon-3">
                                        <i class="fas fa-reply"></i>
                                    </span>
                                </a> --}}
                                <a href="{{ route('admin.newsletters.destroy', $email->id) }}"
                                    class="btn btn-icon btn-active-light-danger w-30px h-30px delete">
                                    <span class="svg-icon svg-icon-3">
                                        <i class="fas fa-trash-alt"></i>
                                    </span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- Replay Modal --}}

    <div class="modal fade" tabindex="-1" id="replayEmail" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content position-absolute">
                <div class="modal-header">
                    <h5 class="modal-header py-3">Reply Mail</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <h1 class="m-0 p-0 fs-1 text-danger">X</h1>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form action="" method="post">
                        <div class="row">

                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')

    @endpush
</x-admin-app-layout>
