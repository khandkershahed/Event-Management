<x-admin-app-layout :title="'Terms And Condition'">
    <div class="card">
        <div class="card-header bg-info align-items-center d-flex justify-content-between">
            <div class="card-title text-white">
                Create Or Delete Terms & Policy
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.terms-condition.create') }}" class="btn btn-light-primary">
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
                    Create
                </a>
            </div>
        </div>
        <div class="card-body py-0">
            <table class="table my-datatable table-striped table-row-bordered gy-5 gs-7">
                <thead class="bg-light-dark">
                    <tr class="fw-semibold fs-6 text-gray-800">
                        <th width="5%">SL</th>
                        <th width="37%">Name</th>
                        <th width="12%">Version</th>
                        <th width="13%">Effective Date</th>
                        <th width="13%">Expiration Date</th>
                        <th width="10%">Status</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($terms as $key => $term)
                        <tr class="fw-bold text-gray-400 text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $term->title }}</td>
                            <td>{{ $term->version }}</td>
                            <td>{{ Carbon\Carbon::parse($term->effective_date)->format('D,d M Y') }}</td>
                            <td>{{ Carbon\Carbon::parse($term->expiration_date)->format('D,d M Y') }}</td>
                            <td>
                                @if ($term->status == 'active')
                                    <span class="badge badge-light-success">Active</span>
                                @else
                                    <span class="badge badge-light-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.terms-condition.edit', $term->id) }}"
                                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="{{ route('admin.terms-condition.destroy', $term->id) }}"
                                    class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1 delete"
                                    data-kt-docs-table-filter="delete_row">
                                    <i class="fas fa-trash-alt"></i>
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
            $(document).ready(function() {


                $(document).on('change', '.status-toggle', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('admin.brands.toggle-status', ':id') }}".replace(':id', id);
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
            });
        </script>
    @endpush
</x-admin-app-layout>
