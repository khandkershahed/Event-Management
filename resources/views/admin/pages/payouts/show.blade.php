<x-admin-app-layout :title="'Payout Detail'">
    <div class="card card-flush mb-5">
        <div class="card-header"><div class="card-title"><h2>Payout {{ $payout->payout_number }}</h2></div></div>
        <div class="card-body">
            <p><strong>Organizer:</strong> {{ $payout->organizerProfile?->organization_name }}</p>
            <p><strong>Amount:</strong> {{ $payout->currency }} {{ number_format((float) $payout->amount, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($payout->status) }}</p>
            <p><strong>Notes:</strong> {{ $payout->notes ?: 'N/A' }}</p>
            <p><strong>Rejection Reason:</strong> {{ $payout->rejection_reason ?: 'N/A' }}</p>

            <div class="d-flex gap-2 flex-wrap">
                @if(in_array($payout->status, [\App\Models\OrganizerPayout::STATUS_PENDING], true))
                    <form method="POST" action="{{ route('admin.payouts.approve', $payout) }}">@csrf<button class="btn btn-sm btn-success">Approve</button></form>
                @endif
                @if(in_array($payout->status, [\App\Models\OrganizerPayout::STATUS_PENDING, \App\Models\OrganizerPayout::STATUS_APPROVED], true))
                    <form method="POST" action="{{ route('admin.payouts.mark-paid', $payout) }}">@csrf<button class="btn btn-sm btn-primary">Mark Paid</button></form>
                    <form method="POST" action="{{ route('admin.payouts.reject', $payout) }}" class="d-flex gap-2">
                        @csrf
                        <input name="rejection_reason" class="form-control form-control-sm" placeholder="Reason optional">
                        <button class="btn btn-sm btn-danger">Reject</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <a href="{{ route('admin.payouts.index') }}" class="btn btn-light">Back</a>
</x-admin-app-layout>
