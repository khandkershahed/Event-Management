<x-admin-app-layout :title="'Seating Plans'">

    <div class="card card-flash">
        <div class="card-header mt-6 d-flex justify-content-between">
            <h3 class="card-title">Seating Plans</h3>

            <a href="{{ route('admin.seating-plans.create') }}" class="btn btn-light-primary">
                <i class="fas fa-plus-circle me-1"></i> Add Seating Plan
            </a>
        </div>

        <div class="card-body pt-0">
            <table id="plansTable" class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                <thead class="bg-dark text-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Venue</th>
                        <th>Sections</th>
                        <th>Seats</th>
                        <th>Designer</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($plans as $key => $plan)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $plan->name }}</td>
                        <td>{{ optional($plan->venue)->name }}</td>
                        <td>{{ optional($plan->sections)->count() }}</td>
                        <td>{{ optional($plan->seats)->count() }}</td>

                        <td>
                            <a href="{{ route('admin.seating-plans.designer', $plan->id) }}" class="btn btn-light-info btn-sm">
                                <i class="fas fa-pencil-ruler"></i> Open Designer
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('admin.seating-plans.edit', $plan->id) }}" class="me-2">
                                <i class="fas fa-edit text-primary fs-4"></i>
                            </a>

                            <a href="javascript:void(0)" data-id="{{ $plan->id }}" class="deletePlan">
                                <i class="fas fa-trash text-danger fs-4"></i>
                            </a>

                            <form id="deleteForm-{{ $plan->id }}" action="{{ route('admin.seating-plans.destroy', $plan->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        $("#plansTable").DataTable();

        $(document).on('click', '.deletePlan', function () {
            if (confirm("Are you sure?")) {
                const id = $(this).data('id');
                $("#deleteForm-" + id).submit();
            }
        });
    </script>
    @endpush

</x-admin-app-layout>
