<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventSeat;
use Illuminate\Http\Request;

class EventSeatController extends Controller
{
    public function index()
    {
        return view('admin.pages.eventSeat.index', [
            'event_seats' => EventSeat::latest()->get(),
        ]);
    }
    public function create()
    {
        return view('admin.pages.eventSeat.create');
    }
}
