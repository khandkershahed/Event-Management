<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\TicketCheckInService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckInController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->organizerProfile;

        return view('organizer.check-in.index', [
            'events' => $profile->events()->whereIn('status', [Event::STATUS_PUBLISHED, Event::STATUS_COMPLETED])->orderByDesc('start_date')->get(),
            'selectedEvent' => null,
            'checkInResult' => null,
        ]);
    }

    public function validateTicket(Request $request, TicketCheckInService $service): View
    {
        $data = $request->validate([
            'event_id' => ['required', 'integer'],
            'ticket_code' => ['required', 'string', 'max:1000'],
        ]);

        $profile = $request->user()->organizerProfile;
        $selectedEvent = $profile->events()->whereKey($data['event_id'])->firstOrFail();
        $result = $service->checkIn($data['ticket_code'], $selectedEvent, $profile, $request->user(), $request);

        return view('organizer.check-in.index', [
            'events' => $profile->events()->whereIn('status', [Event::STATUS_PUBLISHED, Event::STATUS_COMPLETED])->orderByDesc('start_date')->get(),
            'selectedEvent' => $selectedEvent,
            'checkInResult' => $result,
        ]);
    }
}
