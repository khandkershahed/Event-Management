<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\EventType;
use App\Services\HomeMarketplaceService;
use App\Services\SeoMetaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function home(Request $request, HomeMarketplaceService $homeMarketplaceService, SeoMetaService $seoMetaService)
    {
        $sections = $homeMarketplaceService->sections($request->user());

        return view('frontend.pages.homePage', array_merge($sections, [
            'event_types' => $sections['eventTypes'],
            'events' => $sections['featuredEvents'],
            'seoMeta' => $seoMetaService->homepage(),
        ]));
    }

    public function allEvents(Request $request)
    {
        $event_types = EventType::where('status', 'active')->orderBy('name')->get();
        $query = Event::with('eventType')->publiclyVisible();
        $this->applyFilters($query, $request);
        $events = $query->latest()->paginate(8);

        return view('frontend.pages.allEvents', compact('event_types', 'events'));
    }

    public function fetchEvents(Request $request)
    {
        $query = Event::with('eventType')->publiclyVisible();
        $this->applyFilters($query, $request);
        $events = $query->latest()->paginate(8);
        $html = view('frontend.layouts.event_grid', compact('events'))->render();

        return response()->json(['html' => $html, 'hasMorePages' => $events->hasMorePages()]);
    }

    public function eventDetails($slug)
    {
        try {
            $event = Event::where('slug', $slug)->with(['images', 'eventType'])->publiclyVisible()->first();

            if (! $event) {
                abort(404);
            }

            $relatedEvents = Event::where('event_type_id', $event->event_type_id)->where('slug', '!=', $slug)->publiclyVisible()->latest()->get();

            return view('frontend.pages.eventDetails', [
                'event' => new EventResource($event),
                'relatedEvents' => EventResource::collection($relatedEvents),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'An error occurred while fetching event details.');
            return redirect()->route('all.events');
        }
    }

    public function aboutUs() { return view('frontend.pages.static-placeholder', ['title' => 'About']); }
    public function faq() { return view('frontend.pages.static-placeholder', ['title' => 'FAQ']); }
    public function contactUs() { return view('frontend.pages.static-placeholder', ['title' => 'Contact Us']); }
    public function helpCenter() { return view('frontend.pages.static-placeholder', ['title' => 'Help Center']); }
    public function sellTicketOnline() { return view('frontend.pages.static-placeholder', ['title' => 'Sell Ticket Online']); }
    public function privacyPolicy() { return view('frontend.pages.static-placeholder', ['title' => 'Privacy Policy']); }
    public function termsConditions() { return view('frontend.pages.static-placeholder', ['title' => 'Terms & Conditions']); }
    public function blog() { return view('frontend.pages.static-placeholder', ['title' => 'Blog']); }
    public function referFriend() { return view('frontend.pages.static-placeholder', ['title' => 'Refer Friend']); }
    public function eventCreate() { return view('frontend.pages.static-placeholder', ['title' => 'Create Event']); }
    public function onlineEventCreate() { return view('frontend.pages.static-placeholder', ['title' => 'Online Event']); }
    public function venueEventCreate() { return view('frontend.pages.static-placeholder', ['title' => 'Venue Event']); }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('event_type_id') && $request->event_type_id != '0') {
            $query->where('event_type_id', $request->event_type_id);
        }

        if ($request->filled('category_slug') && $request->category_slug != 'all') {
            $query->whereHas('eventType', fn ($q) => $q->where('slug', $request->category_slug));
        }

        if ($request->filled('date_filter') && $request->date_filter != 'all') {
            $now = Carbon::now();
            match ($request->date_filter) {
                'today' => $query->whereDate('start_date', $now->today()),
                'tomorrow' => $query->whereDate('start_date', $now->tomorrow()),
                'this_week' => $query->whereBetween('start_date', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]),
                'this_month' => $query->whereBetween('start_date', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()]),
                default => null,
            };
        }
    }
}
