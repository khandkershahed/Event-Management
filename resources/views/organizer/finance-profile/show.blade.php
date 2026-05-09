@extends('organizer.layouts.app', ['title' => 'Finance Profile'])

@section('content')
<div class="card">
    <h2>Finance Profile</h2>
    <p>Manage the payout method used for organizer payouts. Admin verification is required before payout requests can be submitted.</p>
</div>

<div class="card">
    <h3>Payout Method Status</h3>
    @if($payoutMethod)
        <table class="table">
            <tr><th>Method</th><td>{{ $payoutMethod->methodLabel() }}</td></tr>
            <tr><th>Status</th><td>{{ $payoutMethod->statusLabel() }}</td></tr>
            <tr><th>Active</th><td>{{ $payoutMethod->is_active ? 'Yes' : 'No' }}</td></tr>
            <tr><th>Requires Verification</th><td>{{ $payoutMethod->requires_verification ? 'Yes' : 'No' }}</td></tr>
            <tr><th>Complete</th><td>{{ $payoutMethod->isComplete() ? 'Yes' : 'No' }}</td></tr>
            <tr><th>Admin Note</th><td>{{ $payoutMethod->admin_note ?: '—' }}</td></tr>
        </table>
    @else
        <p>No payout method has been added yet.</p>
    @endif
</div>

@if($canManageFinance)
    <div class="card">
        <h3>{{ $payoutMethod ? 'Update Payout Method' : 'Add Payout Method' }}</h3>
        <form method="POST" action="{{ route('organizer.finance-profile.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Method Type</label>
                <select name="method_type" class="form-control" required>
                    <option value="bank" @selected(old('method_type', $payoutMethod?->method_type ?? 'bank') === 'bank')>Bank Account</option>
                    <option value="mobile_wallet" @selected(old('method_type', $payoutMethod?->method_type) === 'mobile_wallet')>Mobile Wallet</option>
                </select>
                @error('method_type')<small style="color:#dc2626">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Currency</label>
                <input type="text" name="currency" value="{{ old('currency', $payoutMethod?->currency ?? 'BDT') }}" class="form-control" required>
                @error('currency')<small style="color:#dc2626">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Account Holder Name</label>
                <input type="text" name="account_holder_name" value="{{ old('account_holder_name', $payoutMethod?->account_holder_name) }}" class="form-control" required>
                @error('account_holder_name')<small style="color:#dc2626">{{ $message }}</small>@enderror
            </div>

            <div class="grid">
                <div class="mb-3">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $payoutMethod?->bank_name) }}" class="form-control">
                    @error('bank_name')<small style="color:#dc2626">{{ $message }}</small>@enderror
                </div>
                <div class="mb-3">
                    <label>Branch Name</label>
                    <input type="text" name="branch_name" value="{{ old('branch_name', $payoutMethod?->branch_name) }}" class="form-control">
                    @error('branch_name')<small style="color:#dc2626">{{ $message }}</small>@enderror
                </div>
                <div class="mb-3">
                    <label>Account Number</label>
                    <input type="text" name="account_number" value="{{ old('account_number', $payoutMethod?->account_number) }}" class="form-control">
                    @error('account_number')<small style="color:#dc2626">{{ $message }}</small>@enderror
                </div>
                <div class="mb-3">
                    <label>Routing Number</label>
                    <input type="text" name="routing_number" value="{{ old('routing_number', $payoutMethod?->routing_number) }}" class="form-control">
                    @error('routing_number')<small style="color:#dc2626">{{ $message }}</small>@enderror
                </div>
                <div class="mb-3">
                    <label>Mobile Wallet Provider</label>
                    <input type="text" name="mobile_wallet_provider" value="{{ old('mobile_wallet_provider', $payoutMethod?->mobile_wallet_provider) }}" class="form-control">
                    @error('mobile_wallet_provider')<small style="color:#dc2626">{{ $message }}</small>@enderror
                </div>
                <div class="mb-3">
                    <label>Mobile Wallet Number</label>
                    <input type="text" name="mobile_wallet_number" value="{{ old('mobile_wallet_number', $payoutMethod?->mobile_wallet_number) }}" class="form-control">
                    @error('mobile_wallet_number')<small style="color:#dc2626">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label>Organizer Note</label>
                <textarea name="organizer_note" rows="3" class="form-control">{{ old('organizer_note', $payoutMethod?->organizer_note) }}</textarea>
                @error('organizer_note')<small style="color:#dc2626">{{ $message }}</small>@enderror
            </div>

            <button type="submit" class="btn btn-primary">Save Payout Method</button>
        </form>
    </div>
@else
    <div class="card">
        <p>You have read-only access to this finance profile.</p>
    </div>
@endif
@endsection
