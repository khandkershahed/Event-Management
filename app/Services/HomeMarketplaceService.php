<?php

namespace App\Services;

use App\Models\CustomerSavedEvent;
use App\Models\Event;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class HomeMarketplaceService
{
    public function __construct(private readonly PersonalizedDiscoveryService $personalizedDiscoveryService)
    {
    }

    public function sections(?User $user = null): array
    {
        $savedEventIds = ($user && $this->tableExists('customer_saved_events'))
            ? CustomerSavedEvent::query()->where('user_id', $user->id)->pluck('event_id')->all()
            : [];

        return [
            'eventTypes' => $this->eventTypes(),
            'featuredEvents' => $this->featuredEvents(),
            'trendingEvents' => $this->trendingEvents(),
            'upcomingEvents' => $this->upcomingEvents(),
            'onlineEvents' => $this->onlineEvents(),
            'cityBlocks' => $this->cityBlocks(),
            'trustedOrganizers' => $this->trustedOrganizers(),
            'personalizedEvents' => $user ? $this->personalizedDiscoveryService->recommendedEventsFor($user, 6) : collect(),
            'savedEventIds' => $savedEventIds,
        ];
    }

    private function baseEventQuery()
    {
        return Event::query()
            ->publiclyVisible()
            ->with(['eventType', 'organizerProfile', 'venueRecord', 'publicTickets']);
    }

    private function featuredEvents(): Collection
    {
        $events = $this->baseEventQuery()
            ->where('is_featured', true)
            ->orderBy('start_date')
            ->limit(6)
            ->get();

        if ($events->isNotEmpty()) {
            return $events;
        }

        return $this->baseEventQuery()
            ->orderBy('start_date')
            ->limit(6)
            ->get();
    }

    private function trendingEvents(): Collection
    {
        $query = $this->baseEventQuery();

        $countRelations = [];

        if ($this->tableExists('customer_saved_events')) {
            $countRelations['savedByCustomers as saved_count'] = fn ($savedQuery) => $savedQuery;
        }

        if ($this->tableExists('orders')) {
            $countRelations['orders as order_count'] = fn ($orderQuery) => $orderQuery;
        }

        if ($this->tableExists('marketplace_event_reviews')) {
            $countRelations['approvedReviews as approved_review_count'] = fn ($reviewQuery) => $reviewQuery;
        }

        if (! empty($countRelations)) {
            $query->withCount($countRelations);
        }

        if ($this->tableExists('customer_saved_events')) {
            $query->orderByDesc('saved_count');
        }

        if ($this->tableExists('orders')) {
            $query->orderByDesc('order_count');
        }

        if ($this->tableExists('marketplace_event_reviews')) {
            $query->orderByDesc('approved_review_count');
        }

        return $query->orderBy('start_date')
            ->limit(6)
            ->get();
    }

    private function upcomingEvents(): Collection
    {
        return $this->baseEventQuery()
            ->whereDate('start_date', '>=', Carbon::today()->toDateString())
            ->orderBy('start_date')
            ->limit(6)
            ->get();
    }

    private function onlineEvents(): Collection
    {
        return $this->baseEventQuery()
            ->where('event_type', 'online')
            ->orderBy('start_date')
            ->limit(6)
            ->get();
    }

    private function cityBlocks(): Collection
    {
        if (! $this->tableExists('venues') || ! Schema::hasColumn('venues', 'city')) {
            return collect();
        }

        return Event::query()
            ->publiclyVisible()
            ->join('venues', 'events.venue_id', '=', 'venues.id')
            ->whereNotNull('venues.city')
            ->where('venues.city', '!=', '')
            ->selectRaw('venues.city as city, COUNT(events.id) as events_count')
            ->groupBy('venues.city')
            ->orderByDesc('events_count')
            ->orderBy('venues.city')
            ->limit(8)
            ->get();
    }

    private function eventTypes(): Collection
    {
        if (! $this->tableExists('event_types')) {
            return collect();
        }

        return EventType::query()
            ->where('status', 'active')
            ->withCount(['events as published_events_count' => fn ($query) => $query->publiclyVisible()])
            ->orderBy('name')
            ->get();
    }

    private function trustedOrganizers(): Collection
    {
        $query = OrganizerProfile::query()
            ->where('status', OrganizerProfile::STATUS_APPROVED);

        $with = [];
        $withCount = ['events as published_events_count' => fn ($eventQuery) => $eventQuery->publiclyVisible()];

        if ($this->tableExists('marketplace_organizer_ratings')) {
            $with[] = 'ratingSummary';
        }

        if ($this->tableExists('organizer_trust_badges')) {
            $with[] = 'publicTrustBadges';
        }

        if ($this->tableExists('organizer_followers')) {
            $withCount[] = 'followers';
        }

        if (! empty($with)) {
            $query->with($with);
        }

        $query->withCount($withCount);

        if ($this->tableExists('organizer_trust_badges') || $this->tableExists('marketplace_organizer_ratings')) {
            $query->where(function ($query): void {
                if ($this->tableExists('organizer_trust_badges')) {
                    $query->whereHas('publicTrustBadges');
                }

                if ($this->tableExists('marketplace_organizer_ratings')) {
                    $method = $this->tableExists('organizer_trust_badges') ? 'orWhereHas' : 'whereHas';
                    $query->{$method}('ratingSummary', fn ($ratingQuery) => $ratingQuery->where('approved_reviews_count', '>', 0));
                }
            });
        }

        if ($this->tableExists('organizer_followers')) {
            $query->orderByDesc('followers_count');
        }

        return $query->orderByDesc('published_events_count')
            ->limit(4)
            ->get();
    }

    private function tableExists(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (\Throwable) {
            return false;
        }
    }
}
