<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\CustomerSavedEvent;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Services\SeoMetaService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EventBrowseController extends Controller
{
    public function index(Request $request, SeoMetaService $seoMetaService)
    {
        $eventTypes = EventType::where('status', 'active')->orderBy('name')->get();
        $cities = $this->cityOptions();

        $events = $this->baseQuery($request)
            ->latest('start_date')
            ->paginate(9)
            ->withQueryString();

        $savedEventIds = $this->savedEventIds($request);

        return view('frontend.pages.allEvents', [
            'event_types' => $eventTypes,
            'eventTypes' => $eventTypes,
            'events' => $events,
            'cities' => $cities,
            'filters' => $request->only(['search', 'event_type_id', 'category_slug', 'date_filter', 'price', 'city', 'format']),
            'savedEventIds' => $savedEventIds,
            'seoMeta' => $seoMetaService->eventBrowse($request->only(['search', 'event_type_id', 'category_slug', 'date_filter', 'price', 'city', 'format'])),
        ]);
    }

    public function fetch(Request $request)
    {
        $events = $this->baseQuery($request)
            ->latest('start_date')
            ->paginate(9)
            ->withQueryString();

        $savedEventIds = $this->savedEventIds($request);
        $html = view('frontend.layouts.event_grid', compact('events', 'savedEventIds'))->render();

        return response()->json([
            'html' => $html,
            'hasMorePages' => $events->hasMorePages(),
        ]);
    }

    private function savedEventIds(Request $request): array
    {
        if (! $request->user() || ! Schema::hasTable('customer_saved_events')) {
            return [];
        }

        return CustomerSavedEvent::query()
            ->where('user_id', $request->user()->id)
            ->pluck('event_id')
            ->all();
    }

    private function baseQuery(Request $request): Builder
    {
        $query = Event::query()
            ->publiclyVisible()
            ->with([
                'eventType',
                'organizerProfile',
                'venueModel',
                'publicTickets' => fn ($ticketQuery) => $ticketQuery->orderBy('price')->orderBy('name'),
            ]);

        $this->applyFilters($query, $request);

        return $query;
    }

    private function applyFilters(Builder $query, Request $request): void
    {
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('tagline', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('venue', 'like', '%' . $search . '%')
                    ->orWhereHas('organizerProfile', function (Builder $organizerQuery) use ($search): void {
                        $organizerQuery->where('organization_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('venueModel', function (Builder $venueQuery) use ($search): void {
                        $venueQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('city', 'like', '%' . $search . '%')
                            ->orWhere('address', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('event_type_id') && $request->input('event_type_id') !== '0') {
            $query->where('event_type_id', $request->input('event_type_id'));
        }

        if ($request->filled('category_slug') && $request->input('category_slug') !== 'all') {
            $query->whereHas('eventType', fn (Builder $typeQuery) => $typeQuery->where('slug', $request->input('category_slug')));
        }

        if ($request->filled('city')) {
            $city = trim((string) $request->input('city'));

            $query->where(function (Builder $query) use ($city): void {
                $query->where('venue', 'like', '%' . $city . '%')
                    ->orWhereHas('venueModel', fn (Builder $venueQuery) => $venueQuery->where('city', $city));
            });
        }

        if ($request->filled('format') && $request->input('format') !== 'all') {
            $format = $request->input('format');

            $query->where('event_type', $format);
        }

        if ($request->filled('price') && $request->input('price') !== 'all') {
            $price = $request->input('price');

            if ($price === 'free') {
                $query->whereHas('tickets', function (Builder $ticketQuery): void {
                    $ticketQuery->public()->active()->onSale()->where(function (Builder $query): void {
                        $query->where('ticket_type', EventTicket::TYPE_FREE)->orWhere('price', '<=', 0);
                    });
                });
            }

            if ($price === 'paid') {
                $query->whereHas('tickets', function (Builder $ticketQuery): void {
                    $ticketQuery->public()->active()->onSale()->where('price', '>', 0)->whereIn('ticket_type', [EventTicket::TYPE_PAID, EventTicket::TYPE_DONATION]);
                });
            }

            if ($price === 'donation') {
                $query->whereHas('tickets', fn (Builder $ticketQuery) => $ticketQuery->public()->active()->onSale()->where('ticket_type', EventTicket::TYPE_DONATION));
            }

            if ($price === 'invite_only') {
                $query->whereHas('tickets', fn (Builder $ticketQuery) => $ticketQuery->public()->active()->onSale()->where('ticket_type', EventTicket::TYPE_INVITE_ONLY));
            }
        }

        if ($request->filled('date_filter') && $request->input('date_filter') !== 'all') {
            $this->applyDateFilter($query, (string) $request->input('date_filter'));
        }
    }

    private function applyDateFilter(Builder $query, string $dateFilter): void
    {
        $now = Carbon::now();

        match ($dateFilter) {
            'today' => $query->whereDate('start_date', $now->toDateString()),
            'tomorrow' => $query->whereDate('start_date', $now->copy()->addDay()->toDateString()),
            'this_week' => $query->whereBetween('start_date', [$now->copy()->startOfWeek()->toDateString(), $now->copy()->endOfWeek()->toDateString()]),
            'this_month' => $query->whereBetween('start_date', [$now->copy()->startOfMonth()->toDateString(), $now->copy()->endOfMonth()->toDateString()]),
            'upcoming' => $query->whereDate('start_date', '>=', $now->toDateString()),
            default => null,
        };
    }

    private function cityOptions()
    {
        return Event::query()
            ->publiclyVisible()
            ->join('venues', 'events.venue_id', '=', 'venues.id')
            ->whereNotNull('venues.city')
            ->select('venues.city')
            ->distinct()
            ->orderBy('venues.city')
            ->pluck('venues.city');
    }
}
