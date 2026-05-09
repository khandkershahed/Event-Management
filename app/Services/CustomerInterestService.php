<?php

namespace App\Services;

use App\Models\CustomerEventInterest;
use App\Models\Event;
use App\Models\User;

class CustomerInterestService
{
    public function recordFromEvent(User $user, Event $event): void
    {
        if ($event->event_type_id) {
            $this->record($user, CustomerEventInterest::TYPE_EVENT_TYPE, (string) $event->event_type_id, 2);
        }

        if ($event->organizer_profile_id) {
            $this->record($user, CustomerEventInterest::TYPE_ORGANIZER, (string) $event->organizer_profile_id, 2);
        }

        $city = $event->venueRecord?->city;
        if (! $city && is_string($event->venue)) {
            $city = trim($event->venue);
        }

        if ($city) {
            $this->record($user, CustomerEventInterest::TYPE_CITY, mb_substr($city, 0, 120), 1);
        }
    }

    public function record(User $user, string $type, string $value, int $weight = 1): void
    {
        $interest = CustomerEventInterest::query()->firstOrNew([
            'user_id' => $user->id,
            'interest_type' => $type,
            'interest_value' => $value,
        ]);

        $interest->weight = min(999, (int) ($interest->weight ?: 0) + max(1, $weight));
        $interest->last_recorded_at = now();
        $interest->save();
    }
}
