<x-admin-app-layout :title="'Seat Details'">

    <div class="card card-flash">

        <div class="card-header mt-6 d-flex justify-content-between">
            <h3 class="card-title">
                Seat: {{ $seat->label }}
            </h3>

            <a href="{{ route('admin.seats.index') }}" class="btn btn-light-info">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">
                    <div class="border p-5 rounded">
                        <h5 class="text-primary mb-3">Seat Information</h5>

                        <p><strong>Section:</strong> {{ optional($seat->section)->name }}</p>
                        <p><strong>Label:</strong> {{ $seat->label }}</p>
                        <p><strong>Row:</strong> {{ $seat->row_label }}</p>
                        <p><strong>Seat Number:</strong> {{ $seat->seat_number }}</p>

                        <p><strong>Position:</strong>
                            X: {{ $seat->x }},
                            Y: {{ $seat->y }}
                        </p>

                        <p><strong>Created:</strong> {{ $seat->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-admin-app-layout>
