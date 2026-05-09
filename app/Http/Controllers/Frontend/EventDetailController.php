<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CustomerSavedEvent;
use App\Models\Event;
use App\Services\SeoMetaService;
use Illuminate\Http\Request;

class EventDetailController extends Controller
{
    public function show(string $slug, Request $request, SeoMetaService $seoMetaService)
    {
        $event = Event::with([
            'eventType',
            'organizerProfile',
            'venueRecord',
            'seatingPlan.sections.seats',
            'publicTickets',
            'approvedReviews.user',
        ])->withAvg('approvedReviews', 'rating')->withCount('approvedReviews')->publiclyVisible()->where('slug', $slug)->firstOrFail();

        $relatedEvents = Event::with(['eventType', 'venueRecord'])
            ->publiclyVisible()
            ->where('id', '!=', $event->id)
            ->when($event->event_type_id, fn ($query) => $query->where('event_type_id', $event->event_type_id))
            ->latest()
            ->take(6)
            ->get();

        if ($relatedEvents->count() < 3) {
            $relatedEvents = Event::with(['eventType', 'venueRecord'])
                ->publiclyVisible()
                ->where('id', '!=', $event->id)
                ->latest()
                ->take(6)
                ->get();
        }

        $isSaved = false;
        if ($request->user()) {
            $isSaved = CustomerSavedEvent::query()
                ->where('user_id', $request->user()->id)
                ->where('event_id', $event->id)
                ->exists();
        }

        return view('frontend.pages.eventDetails', [
            'event' => $event,
            'ticketTypes' => $event->publicTickets,
            'relatedEvents' => $relatedEvents,
            'isSaved' => $isSaved,
            'savedEventIds' => $request->user() ? CustomerSavedEvent::where('user_id', $request->user()->id)->pluck('event_id')->all() : [],
            'seoMeta' => $seoMetaService->event($event),
        ]);
    }
}
