<?php

namespace App\Http\Controllers\Frontend;

use App\Models\PageBanner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function home()
    {
        return view('admin.auth.login');
    }

}
