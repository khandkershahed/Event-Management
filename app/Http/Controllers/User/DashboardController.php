<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CustomerDashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, CustomerDashboardService $dashboardService): View
    {
        return view('user.pages.dashboard', $dashboardService->build($request->user()));
    }
}
