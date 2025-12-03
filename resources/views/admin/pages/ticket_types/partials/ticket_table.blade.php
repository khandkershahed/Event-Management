<table class="table table-striped table-bordered align-middle">
    <thead class="bg-dark text-light">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Price</th>
            <th>Capacity</th>
            <th>Sections</th>
            <th width="90px">Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($ticketTypes as $i => $t)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $t->name }}</td>
            <td>${{ number_format($t->price, 2) }}</td>
            <td>{{ $t->quantity }}</td>
            <td>
                @foreach($t->sections as $s)
                    <span class="ticket-badge">{{ $s->name }}</span>
                @endforeach
            </td>
            <td>
                <button class="btn btn-sm btn-warning btn-edit-ticket" data-id="{{ $t->id }}">
                    <i class="fa fa-edit"></i>
                </button>

                <button class="btn btn-sm btn-danger btn-delete-ticket" data-id="{{ $t->id }}">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
        @endforeach

        @if(count($ticketTypes) == 0)
        <tr>
            <td colspan="6" class="text-center text-muted py-5">
                No ticket types added yet.
            </td>
        </tr>
        @endif
    </tbody>
</table>
