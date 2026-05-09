@extends('organizer.layouts.app', ['title' => 'Organizer Payouts'])

@section('content')
<div class="card">
    <h2>Payouts</h2>
    <p>Track your organizer earnings and request payout from your available balance.</p>
</div>

<div class="grid">
    <div class="card">
        <strong>Available Balance</strong>
        <h2>{{ $profile->organizerLedgers()->first()?->currency ?? 'BDT' }} {{ number_format($availableBalance, 2) }}</h2>
    </div>
    <div class="card">
        <strong>Pending Requests</strong>
        <h2>{{ $payouts->where('status', \App\Models\OrganizerPayout::STATUS_PENDING)->count() }}</h2>
    </div>
</div>

<div class="card">
    <h3>Payout Method</h3>
    @if($payoutMethod)
        <p><strong>Method:</strong> {{ $payoutMethod->methodLabel() }}</p>
        <p><strong>Status:</strong> {{ $payoutMethod->statusLabel() }}</p>
        <p><strong>Complete:</strong> {{ $payoutMethod->isComplete() ? 'Yes' : 'No' }}</p>
        <p><strong>Active:</strong> {{ $payoutMethod->is_active ? 'Yes' : 'No' }}</p>
    @else
        <p>No payout method has been added yet.</p>
    @endif
    <a href="{{ route('organizer.finance-profile.show') }}" class="btn btn-secondary">View Finance Profile</a>
</div>

@if($canManageFinance)
    <div class="card">
        <h3>Request Payout</h3>
        @if(! $payoutMethod || ! $payoutMethod->isUsableForPayout())
            <p style="color:#dc2626">Complete and verify your payout method before requesting payout.</p>
        @endif
        <form method="POST" action="{{ route('organizer.payouts.store') }}">
            @csrf
            <div class="mb-3">
                <label>Amount</label>
                <input type="number" step="0.01" min="1" name="amount" value="{{ old('amount') }}" class="form-control" required>
                @error('amount')<small style="color:#dc2626">{{ $message }}</small>@enderror
            </div>
            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
                @error('notes')<small style="color:#dc2626">{{ $message }}</small>@enderror
            </div>
            <button class="btn btn-primary" type="submit">Submit Payout Request</button>
        </form>
    </div>
@else
    <div class="card">
        <p>You have read-only finance access. You can view payouts and ledgers, but only the organizer owner can request payouts.</p>
    </div>
@endif

<div class="card">
    <h3>Payout Requests</h3>
    <table class="table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Requested</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payouts as $payout)
                <tr>
                    <td>{{ $payout->payout_number }}</td>
                    <td>{{ $payout->currency }} {{ number_format((float) $payout->amount, 2) }}</td>
                    <td>{{ ucfirst($payout->status) }}</td>
                    <td>{{ optional($payout->requested_at)->format('d M Y H:i') }}</td>
                    <td>{{ $payout->notes }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No payout requests yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $payouts->links() }}
</div>

<div class="card">
    <h3>Organizer Ledger</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Direction</th>
                <th>Amount</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ledgers as $ledger)
                <tr>
                    <td>{{ optional($ledger->posted_at)->format('d M Y H:i') }}</td>
                    <td>{{ str_replace('_', ' ', ucfirst($ledger->type)) }}</td>
                    <td>{{ ucfirst($ledger->direction) }}</td>
                    <td>{{ $ledger->currency }} {{ number_format((float) $ledger->amount, 2) }}</td>
                    <td>{{ $ledger->description }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No ledger entries yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $ledgers->links() }}
</div>
@endsection
