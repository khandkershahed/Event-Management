<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\CustomerPanelService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(protected CustomerPanelService $customerPanelService)
    {
    }

    public function dashboard(): View
    {
        return view('user.pages.userDashboard');
    }

    public function myEvents(Request $request): View
    {
        return view('user.pages.myEvents', $this->customerPanelService->marketplaceActivity($request->user()));
    }

    public function myCoupons(Request $request): View
    {
        return view('user.pages.myCoupons', $this->customerPanelService->marketplaceActivity($request->user()));
    }

    public function myCards(Request $request): View
    {
        return view('user.pages.myCards', $this->customerPanelService->marketplaceActivity($request->user()));
    }

    public function myReports(Request $request): View
    {
        return view('user.pages.myReports', $this->customerPanelService->marketplaceActivity($request->user()));
    }

    public function mySubscription(Request $request): View
    {
        return view('user.pages.mySubscription', $this->customerPanelService->marketplaceActivity($request->user()));
    }

    public function myInformation(Request $request): View
    {
        return view('user.pages.myInformation', $this->customerPanelService->profile($request->user()));
    }

    public function myTeam(Request $request): View
    {
        return view('user.pages.myTeam', $this->customerPanelService->marketplaceActivity($request->user()));
    }

    public function myProfile(Request $request): View
    {
        return view('user.pages.myProfile', $this->customerPanelService->profile($request->user()));
    }
}
