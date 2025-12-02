<x-admin-app-layout :title="'Seats List'">

    <div class="card card-flash">

        <div class="card-header mt-6 d-flex justify-content-between">
            <h3 class="card-title">Seats Inventory</h3>

            <a href="{{ route('admin.seating-plans.index') }}" class="btn btn-light-info">
                <i class="fas fa-arrow-left"></i> Back to Seating Plans
            </a>
        </div>

        <div class="card-body pt-0">

            <table id="seatsTable" class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                <thead class="bg-dark text-light">
                    <tr>
                        <th>#</th>
                        <th>Section</th>
                        <th>Seat Label</th>
                        <th>Row</th>
                        <th>Number</th>
                        <th>X</th>
                        <th>Y</th>
                        <th width="8%">View</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($seats as $key => $seat)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ optional($seat->section)->name }}</td>
                        <td>{{ $seat->label }}</td>
                        <td>{{ $seat->row_label }}</td>
                        <td>{{ $seat->seat_number }}</td>
                        <td>{{ $seat->x }}</td>
                        <td>{{ $seat->y }}</td>

                        <td>
                            <a href="{{ route('admin.seats.show', $seat->id) }}">
                                <i class="fas fa-eye text-primary fs-4"></i>
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
        $("#seatsTable").DataTable();
    </script>
    @endpush

</x-admin-app-layout>
