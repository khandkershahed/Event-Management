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
                                    <th class="min-w-150px">{{ __('category.Parent') }}</th>
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


                                        {{-- <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions

                                                <span class="svg-icon svg-icon-5 m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>


                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                                data-kt-menu="true">



                                                @if (Auth::guard('admin')->user()->can('show.category'))
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.categories.show', $category->id) }}"
                                                            class="menu-link px-3">Show</a>
                                                    </div>
                                                @endif



                                                @if (Auth::guard('admin')->user()->can('edit.category'))
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                            class="menu-link px-3">Edit</a>
                                                    </div>
                                                @endif



                                                @if (Auth::guard('admin')->user()->can('delete.category'))
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.categories.destroy', $category->id) }}"
                                                            class="menu-link px-3 delete">Delete</a>
                                                    </div>
                                                @endif


                                            </div>

                                        </td> --}}

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

                                    @foreach ($category->children as $child)
                                        <tr>

                                            <td>

                                                <span class="fw-bolder">
                                                    {{ $loop->parent->iteration }}.{{ $loop->iteration }}</span>

                                            </td>
                                            <td>

                                                <span class="fw-bolder"> -- {{ $child->name }}</span>

                                            </td>
                                            <td>

                                                <span class="fw-bolder"> {{ $child->slug }}</span>

                                            </td>
                                            <td>

                                                {{-- <div
                                                    class="badge {{ $child->status == 1 ? 'badge-light-success' : 'badge-light-danger' }}">
                                                    {{ $child->status == 1 ? 'Active' : 'InActive' }}
                                                </div> --}}

                                                <div
                                                    class="badge {{ $child->status == 'active' ? 'badge-light-success' : 'badge-light-danger' }}">
                                                    {{ $child->status == 'active' ? 'Active' : 'InActive' }}
                                                </div>

                                            </td>
                                            <td>

                                                <span class="fw-bolder">
                                                    {{ $child->parent->name ?? 'N/A' }}

                                            </td>

                                            <td>

                                                {{-- @if (Auth::guard('admin')->user()->can('show.category')) --}}


                                                <a href="{{ route('admin.categories.show', $child->id) }}"
                                                    class="menu-link"><i
                                                        class="fa-solid fa-eye text-success me-1 fs-4"></i></a>

                                                {{-- @endif --}}


                                                {{-- @if (Auth::guard('admin')->user()->can('show.category')) --}}

                                                <a href="{{ route('admin.categories.edit', $child->id) }}"
                                                    class="menu-link"><i
                                                        class="fa-solid fa-edit text-primary me-1 fs-4"></i></a>

                                                {{-- @endif --}}


                                                {{-- @if (Auth::guard('admin')->user()->can('show.category')) --}}

                                                <a href="{{ route('admin.categories.destroy', $child->id) }}"
                                                    class="menu-link delete"><i
                                                        class="fa-solid fa-trash text-danger fs-4"></i></a>

                                                {{-- @endif --}}

                                            </td>

                                        </tr>
                                    @endforeach
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
