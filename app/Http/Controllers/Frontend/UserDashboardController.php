<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class UserDashboardController extends Controller
{
    function index(): View
    {
        return view('dashboard');
    }
}
