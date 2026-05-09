<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\OrganizerDashboardService;
use App\Services\OrganizerTeamAccessService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, OrganizerDashboardService $dashboardService, OrganizerTeamAccessService $accessService): View
    {
        $profile = $accessService->resolveProfileFor($request->user());
        abort_unless($profile, 404);

        return view('organizer.dashboard', [
            'profile' => $profile,
            ...$dashboardService->data($profile),
        ]);
    }

    public function orders(Request $request, OrganizerTeamAccessService $accessService): View
    {
        $profile = $accessService->resolveProfileFor($request->user());
        abort_unless($profile, 404);

        return view('organizer.orders', ['profile' => $profile]);
    }

    public function reports(Request $request, OrganizerTeamAccessService $accessService): View
    {
        $profile = $accessService->resolveProfileFor($request->user());
        abort_unless($profile, 404);

        return view('organizer.reports', ['profile' => $profile]);
    }
}
