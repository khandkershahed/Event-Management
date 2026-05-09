<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizerPayoutMethodRequest;
use App\Models\OrganizerPayoutMethod;
use App\Services\OrganizerTeamAccessService;
use App\Services\PayoutMethodService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceProfileController extends Controller
{
    public function __construct(protected PayoutMethodService $payoutMethodService)
    {
    }

    public function show(Request $request): View
    {
        $profile = $request->user()->organizerProfile;
        abort_if(! $profile, 404);

        $teamAccess = app(OrganizerTeamAccessService::class);

        return view('organizer.finance-profile.show', [
            'profile' => $profile,
            'payoutMethod' => $profile->payoutMethod,
            'methodTypes' => OrganizerPayoutMethod::methodTypes(),
            'canManageFinance' => $teamAccess->userCan($request->user(), 'finance.manage', $profile),
        ]);
    }

    public function update(OrganizerPayoutMethodRequest $request)
    {
        $profile = $request->user()->organizerProfile;
        abort_if(! $profile, 404);

        $this->payoutMethodService->upsertForOrganizer($profile, $request->validated());

        return redirect()->route('organizer.finance-profile.show')
            ->with('success', 'Payout method saved and sent for admin review.');
    }
}
