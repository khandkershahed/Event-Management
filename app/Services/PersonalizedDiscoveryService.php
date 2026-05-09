<?php

namespace App\Services;

use App\Models\CustomerEventInterest;
use App\Models\CustomerSavedEvent;
use App\Models\Event;
use App\Models\OrganizerFollower;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class PersonalizedDiscoveryService
{
    public function recommendedEventsFor(User $user, int $limit = 12): Collection
    {
        $savedEventIds = $this->tableExists('customer_saved_events')
            ? CustomerSavedEvent::query()->where('user_id', $user->id)->pluck('event_id')->all()
            : [];

        if (! $this->tableExists('customer_event_interests') && ! $this->tableExists('organizer_followers')) {
            return $this->fallbackEvents($savedEventIds, $limit);
        }

        $eventTypeIds = $this->interestValues($user, CustomerEventInterest::TYPE_EVENT_TYPE)
            ->map(fn ($value) => (int) $value)
            ->all();

        $organizerIds = $this->interestValues($user, CustomerEventInterest::TYPE_ORGANIZER)
            ->map(fn ($value) => (int) $value)
            ->all();

        $followedOrganizerIds = $this->tableExists('organizer_followers')
            ? OrganizerFollower::query()->where('user_id', $user->id)->pluck('organizer_profile_id')->all()
            : [];

        $organizerIds = array_values(array_unique(array_merge($organizerIds, $followedOrganizerIds)));

        $cities = $this->interestValues($user, CustomerEventInterest::TYPE_CITY)->all();

        $events = Event::query()
            ->with(['eventType', 'organizerProfile', 'venueRecord', 'publicTickets'])
            ->publiclyVisible()
            ->when($savedEventIds, fn ($query) => $query->whereNotIn('id', $savedEventIds))
            ->where(function ($query) use ($eventTypeIds, $organizerIds, $cities): void {
                $hasAny = false;

                if (! empty($eventTypeIds)) {
                    $hasAny = true;
                    $query->orWhereIn('event_type_id', $eventTypeIds);
                }

                if (! empty($organizerIds)) {
                    $hasAny = true;
                    $query->orWhereIn('organizer_profile_id', $organizerIds);
                }

                if (! empty($cities)) {
                    $hasAny = true;
                    $query->orWhereHas('venueRecord', fn ($venueQuery) => $venueQuery->whereIn('city', $cities));
                }

                if (! $hasAny) {
                    $query->whereNotNull('id');
                }
            })
            ->orderBy('start_date')
            ->limit($limit)
            ->get();

        if ($events->count() >= $limit) {
            return $events;
        }

        $excludeIds = array_merge($savedEventIds, $events->pluck('id')->all());
        $fallback = $this->fallbackEvents($excludeIds, $limit - $events->count());

        return $events->concat($fallback)->values();
    }

    private function interestValues(User $user, string $type): Collection
    {
        if (! $this->tableExists('customer_event_interests')) {
            return collect();
        }

        return CustomerEventInterest::query()
            ->where('user_id', $user->id)
            ->where('interest_type', $type)
            ->orderByDesc('weight')
            ->limit(10)
            ->pluck('interest_value')
            ->filter()
            ->values();
    }

    private function fallbackEvents(array $excludeIds, int $limit): Collection
    {
        if ($limit <= 0) {
            return collect();
        }

        return Event::query()
            ->with(['eventType', 'organizerProfile', 'venueRecord', 'publicTickets'])
            ->publiclyVisible()
            ->when($excludeIds, fn ($query) => $query->whereNotIn('id', $excludeIds))
            ->orderBy('start_date')
            ->limit($limit)
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
