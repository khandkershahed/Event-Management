<x-admin-app-layout :title="'Review Payout Method'">
    <div class="card card-flush mb-5">
        <div class="card-header"><h2>Review Payout Method</h2></div>
        <div class="card-body">
            <table class="table table-row-dashed gy-3">
                <tr><th>Organizer</th><td>{{ $payoutMethod->organizerProfile?->organization_name }}</td></tr>
                <tr><th>Owner</th><td>{{ $payoutMethod->organizerProfile?->user?->name }} ({{ $payoutMethod->organizerProfile?->user?->email }})</td></tr>
                <tr><th>Method</th><td>{{ $payoutMethod->methodLabel() }}</td></tr>
                <tr><th>Status</th><td>{{ $payoutMethod->statusLabel() }}</td></tr>
                <tr><th>Active</th><td>{{ $payoutMethod->is_active ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Requires Verification</th><td>{{ $payoutMethod->requires_verification ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Complete</th><td>{{ $payoutMethod->isComplete() ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Currency</th><td>{{ $payoutMethod->currency }}</td></tr>
                <tr><th>Account Holder</th><td>{{ $payoutMethod->account_holder_name }}</td></tr>
                <tr><th>Bank</th><td>{{ $payoutMethod->bank_name ?: '—' }}</td></tr>
                <tr><th>Branch</th><td>{{ $payoutMethod->branch_name ?: '—' }}</td></tr>
                <tr><th>Account Number</th><td>{{ $payoutMethod->account_number ?: '—' }}</td></tr>
                <tr><th>Routing Number</th><td>{{ $payoutMethod->routing_number ?: '—' }}</td></tr>
                <tr><th>Wallet Provider</th><td>{{ $payoutMethod->mobile_wallet_provider ?: '—' }}</td></tr>
                <tr><th>Wallet Number</th><td>{{ $payoutMethod->mobile_wallet_number ?: '—' }}</td></tr>
                <tr><th>Organizer Note</th><td>{{ $payoutMethod->organizer_note ?: '—' }}</td></tr>
                <tr><th>Admin Note</th><td>{{ $payoutMethod->admin_note ?: '—' }}</td></tr>
            </table>
        </div>
    </div>

    <div class="card card-flush">
        <div class="card-header"><h3>Decision</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.payout-methods.verify', $payoutMethod) }}" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Admin Note</label>
                    <textarea name="admin_note" rows="3" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Mark Verified</button>
            </form>

            <form method="POST" action="{{ route('admin.payout-methods.reject', $payoutMethod) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Rejection Note</label>
                    <textarea name="admin_note" rows="3" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-danger">Reject Payout Method</button>
            </form>
        </div>
    </div>
</x-admin-app-layout>
