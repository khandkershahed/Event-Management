<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventType;
use App\Models\PageBanner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function home()
    {
        $event_types = EventType::where('status', 'active')->orderBy('name', 'asc')->get();
        $events = Event::with('eventType')
            ->where('status', 'active')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $data = [
            'event_types' => $event_types,
            'events'      => $events,
        ];
        return view('frontend.pages.homePage', $data);
    }
    // public function allEvents()
    // {
    //     $event_types = EventType::where('status', 'active')->orderBy('name', 'asc')->get();
    //     $events = Event::with('eventType')
    //         ->where('status', 'active')
    //         ->where('is_featured', true)
    //         ->latest()
    //         ->take(8)
    //         ->get();

    //     $data = [
    //         'event_types' => $event_types,
    //         'events'      => $events,
    //     ];
    //     return view('frontend.pages.allEvents', $data);
    // }

    public function eventDetails($slug)
    {
        try {
            $event = Event::where('slug', $slug)
                ->with(['images', 'eventType']) // include seat type relation
                ->where('status', 'active')
                ->first();

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found.',
                ], 404);
            }

            $relatedEvents = Event::where('event_type_id', $event->event_type_id)
                ->where('slug', '!=', $slug)
                ->where('status', 'active')
                ->latest()
                ->get();
            $data = [
                'event'         => new EventResource($event),
                'relatedEvents' => EventResource::collection($relatedEvents),
            ];
            return view('frontend.pages.eventDetails', $data);
        } catch (\Exception $e) {
            Session::flash('error', 'An error occurred while fetching event details.');
            return redirect()->back();
        }
    }

    public function allEvents(Request $request)
    {
        $event_types = EventType::where('status', 'active')->orderBy('name', 'asc')->get();

        // Start the query
        $query = Event::with('eventType')->where('status', 'active');

        // Apply filters (this will also be used by our AJAX function)
        $this->applyFilters($query, $request);

        // Get the first page of events (e.g., 8 per page)
        $events = $query->latest()->paginate(8);

        $data = [
            'event_types' => $event_types,
            'events'      => $events,
        ];

        return view('frontend.pages.allEvents', $data);
    }

    /**
     * Fetch events for AJAX filtering and pagination.
     */
    public function fetchEvents(Request $request)
    {
        $query = Event::with('eventType')->where('status', 'active');

        $this->applyFilters($query, $request);

        $events = $query->latest()->paginate(8);

        $html = view('frontend.layouts.event_grid', compact('events'))->render();

        return response()->json([
            'html' => $html,
            'hasMorePages' => $events->hasMorePages()
        ]);
    }


    /**
     * A private helper function to apply all filters DRYly.
     */
    private function applyFilters($query, Request $request)
    {
        // 1. Search Filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 2. Event Type (Dropdown) Filter
        if ($request->filled('event_type_id') && $request->event_type_id != '0') {
            $query->where('event_type_id', $request->event_type_id);
        }

        // 3. Category (Button) Filter
        if ($request->filled('category_slug') && $request->category_slug != 'all') {
            $query->whereHas('eventType', function ($q) use ($request) {
                $q->where('slug', $request->category_slug);
            });
        }

        // 4. Date Filter
        if ($request->filled('date_filter') && $request->date_filter != 'all') {
            $date_filter = $request->date_filter;
            $now = Carbon::now();

            switch ($date_filter) {
                case 'today':
                    $query->whereDate('start_date', $now->today());
                    break;
                case 'tomorrow':
                    $query->whereDate('start_date', $now->tomorrow());
                    break;
                case 'this_week':
                    $query->whereBetween('start_date', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'this_weekend':
                    $query->whereBetween('start_date', [$now->next(Carbon::FRIDAY)->startOfDay(), $now->next(Carbon::SUNDAY)->endOfDay()]);
                    break;
                case 'next_week':
                    $query->whereBetween('start_date', [$now->copy()->addWeek()->startOfWeek(), $now->copy()->addWeek()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereBetween('start_date', [$now->startOfMonth(), $now->endOfMonth()]);
                    break;
                    // Add 'next_month', 'this_year' etc. as needed
            }
        }
    }
}
