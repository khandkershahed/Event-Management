<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function dashboard()
    {
        return view('user.pages.userDashboard');
    }
    public function myEvents()
    {
        return view('user.pages.myEvents');
    }
    public function myCoupons()
    {
        return view('user.pages.myCoupons');
    }
    public function myCards()
    {
        return view('user.pages.myCards');
    }
    public function myReports()
    {
        return view('user.pages.myReports');
    }
    public function mySubscription()
    {
        return view('user.pages.mySubscription');
    }
    public function myInformation()
    {
        return view('user.pages.myInformation');
    }
    public function myTeam()
    {
        return view('user.pages.myTeam');
    }
    public function myProfile()
    {
        return view('user.pages.myProfile');
    }
}
