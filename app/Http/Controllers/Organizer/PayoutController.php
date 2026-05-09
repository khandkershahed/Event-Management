<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use App\Services\OrganizerLedgerService;
use App\Services\OrganizerTeamAccessService;
use App\Services\PayoutMethodService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class PayoutController extends Controller
{
    public function __construct(
        protected OrganizerLedgerService $organizerLedgerService,
        protected PayoutMethodService $payoutMethodService,
        protected AuditLogService $auditLogService
    ) {
    }

    public function index(Request $request): View
    {
        $profile = $request->user()->organizerProfile;
        abort_if(! $profile, 404);

        $teamAccess = app(OrganizerTeamAccessService::class);

        return view('organizer.payouts.index', [
            'profile' => $profile,
            'payoutMethod' => $profile->payoutMethod,
            'canManageFinance' => $teamAccess->userCan($request->user(), 'finance.manage', $profile),
            'availableBalance' => $this->organizerLedgerService->availableBalance($profile),
            'ledgers' => $profile->organizerLedgers()->latest('id')->paginate(15, ['*'], 'ledger_page'),
            'payouts' => $profile->organizerPayouts()->latest('id')->paginate(10, ['*'], 'payout_page'),
        ]);
    }

    public function store(Request $request)
    {
        $profile = $request->user()->organizerProfile;
        abort_if(! $profile, 404);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $this->payoutMethodService->assertCanRequestPayout($profile);

            $payout = $this->organizerLedgerService->requestPayout(
                $profile,
                (float) $data['amount'],
                $request->user()->id,
                $data['notes'] ?? null
            );

            $this->auditLogService->record(
                'organizer.payout.requested',
                $payout,
                [],
                $payout->only(['organizer_profile_id', 'payout_number', 'amount', 'currency', 'status', 'requested_by', 'requested_at']),
                'Organizer requested payout ' . $payout->payout_number,
                $request->user(),
                'web',
                $request
            );
        } catch (RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->route('organizer.payouts.index')->with('success', 'Payout request submitted successfully.');
    }
}
