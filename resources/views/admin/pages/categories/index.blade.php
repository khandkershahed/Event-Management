<x-admin-app-layout :title="'Category List'">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div class="container-xxl">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        {{-- @if (Auth::guard('admin')->user()->can('add.category')) --}}
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-light-primary">Add Category</a>
                        {{-- @endif --}}
                    </div>

                </div>


                <div class="card-body pt-0">

                    <div class="table-responsive">
                        <table id="kt_datatable_example_5"
                            class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                            <thead class="bg-dark text-light">

                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">

                                    <th class="min-w-10px">{{ __('category.Sl') }}</th>
                                    <th class="min-w-150px">{{ __('category.Name') }}</th>
                                    <th class="min-w-150px">{{ __('category.Slug') }}</th>
                                    <th class="min-w-150px">{{ __('category.Status') }}</th>
                                    <th class="min-w-70px">{{ __('category.Action') }}</th>
                                </tr>

                            </thead>


                            <tbody class="fw-bold text-gray-600">
                                @forelse ($categories as $category)

                                    <tr>


                                        <td>

                                            <span class="fw-bolder"> {{ $loop->iteration }}</span>

                                        </td>
                                        <td>

                                            <span class="fw-bolder"> {{ $category->name }}</span>

                                        </td>
                                        <td>

                                            <span class="fw-bolder"> {{ $category->slug }}</span>

                                        </td>
                                        <td>

                                            <div
                                                class="badge {{ $category->status == 'active' ? 'badge-light-success' : 'badge-light-danger' }}">
                                                {{ $category->status == 'active' ? 'Active' : 'InActive' }}
                                            </div>

                                        </td>
                                        <td>

                                            <span class="fw-bolder">
                                                {{ $category->parent_id ? $category->parent->name : 'N/A' }}</span>

                                        </td>

                                        <td>

                                            {{-- @if (Auth::guard('admin')->user()->can('show.category')) --}}

                                            <a href="{{ route('admin.categories.show', $category->id) }}"
                                                class="menu-link"><i
                                                    class="fa-solid fa-eye text-success me-1 fs-4"></i></a>

                                            {{-- @endif --}}

                                            {{-- @if (Auth::guard('admin')->user()->can('edit.category')) --}}

                                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                class="menu-link"><i
                                                    class="fa-solid fa-edit text-primary me-1 fs-4"></i></a>

                                            {{-- @endif --}}

                                            {{-- @if (Auth::guard('admin')->user()->can('delete.category')) --}}

                                            <a href="{{ route('admin.categories.destroy', $category->id) }}"
                                                class="menu-link delete"><i
                                                    class="fa-solid fa-trash text-danger fs-4"></i></a>

                                            {{-- @endif --}}

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>

        </div>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $("#kt_datatable_example_5").DataTable({
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
                        ">",
                });

                // Rebind the action buttons after table is redrawn (pagination, sorting, etc.)
                table.on('draw', function() {
                    // Bind actions for newly drawn table rows
                    $(".btn-light").on("click", function() {
                        // Your action button click logic here, for example:
                        // console.log('Button clicked!');
                    });
                });
            });
        </script>
    @endpush
</x-admin-app-layout>
