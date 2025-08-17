<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\EventSeat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EventSeatType;
use App\Models\EventType;

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
        $data = [
            'events' => Event::latest()->get(['id', 'name']),
            'seat_types' => EventSeatType::latest()->get(['id', 'name']),
        ];
        return view('admin.pages.eventSeat.create',$data);
    }
}
