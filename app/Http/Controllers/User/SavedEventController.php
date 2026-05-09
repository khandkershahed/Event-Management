<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CustomerSavedEvent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavedEventController extends Controller
{
    public function index(Request $request): View
    {
        $savedEvents = CustomerSavedEvent::query()
            ->with(['event.eventType', 'event.organizerProfile', 'event.venueRecord', 'event.publicTickets'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(12);

        $savedEventIds = $savedEvents->pluck('event_id')->all();

        return view('user.pages.saved-events.index', compact('savedEvents', 'savedEventIds'));
    }
}
