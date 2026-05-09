<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AdminDashboardService;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(AdminDashboardService $dashboardService): View
    {
        return view('admin.dashboard', $dashboardService->data());
    }
}
