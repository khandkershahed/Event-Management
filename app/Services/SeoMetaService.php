<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\OrganizerProfile;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SeoMetaService
{
    public function homepage(): array
    {
        return $this->meta(
            title: config('app.name', 'Events Tailor') . ' | Discover Events Near You',
            description: 'Discover featured, trending, online, upcoming, and trusted organizer events on the marketplace.',
            canonical: route('homepage'),
            type: 'website'
        );
    }

    public function eventBrowse(array $filters = []): array
    {
        $title = 'Browse Events | ' . config('app.name', 'Events Tailor');
        $description = 'Browse published events by category, city, date, price, and format.';

        if (! empty($filters['search'])) {
            $title = 'Search Events for ' . e((string) $filters['search']) . ' | ' . config('app.name', 'Events Tailor');
        }

        return $this->meta(
            title: $title,
            description: $description,
            canonical: route('all.events'),
            type: 'website'
        );
    }

    public function event(Event $event): array
    {
        $description = $this->plainText($event->tagline ?: $event->description ?: 'View event details, tickets, organizer information, reviews, and venue information.');
        $image = $this->eventImage($event);

        return $this->meta(
            title: $event->name . ' | ' . config('app.name', 'Events Tailor'),
            description: $description,
            canonical: route('event.details', $event->slug),
            type: 'event',
            image: $image,
            jsonLd: [$this->eventJsonLd($event)]
        );
    }

    public function organizer(OrganizerProfile $organizer): array
    {
        $description = $this->plainText($organizer->description ?: 'View organizer profile, trust badges, reviews, followers, and published events.');
        $image = $this->organizerImage($organizer);

        return $this->meta(
            title: $organizer->organization_name . ' | Organizer Profile',
            description: $description,
            canonical: route('public.organizers.show', $organizer->slug),
            type: 'profile',
            image: $image,
            jsonLd: [$this->organizerJsonLd($organizer)]
        );
    }

    public function meta(string $title, string $description, string $canonical, string $type = 'website', ?string $image = null, array $jsonLd = []): array
    {
        return [
            'title' => Str::limit($this->plainText($title), 70, ''),
            'description' => Str::limit($this->plainText($description), 160, ''),
            'canonical' => $canonical,
            'og_type' => $type,
            'image' => $image ?: $this->defaultImage(),
            'twitter_card' => 'summary_large_image',
            'robots' => 'index,follow',
            'json_ld' => array_values(array_filter($jsonLd)),
        ];
    }

    public function eventJsonLd(Event $event): array
    {
        $event->loadMissing(['organizerProfile', 'venueRecord', 'publicTickets']);
        $ticket = $event->publicTickets->sortBy('price')->first();
        $location = $this->eventLocationJsonLd($event);

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event->name,
            'description' => $this->plainText($event->description ?: $event->tagline ?: $event->name),
            'url' => route('event.details', $event->slug),
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => ($event->event_type === 'online') ? 'https://schema.org/OnlineEventAttendanceMode' : 'https://schema.org/OfflineEventAttendanceMode',
            'startDate' => $this->dateTimeIso($event, 'start'),
            'endDate' => $this->dateTimeIso($event, 'end'),
            'organizer' => [
                '@type' => 'Organization',
                'name' => $event->organizerProfile?->organization_name ?: $event->organizer_name ?: config('app.name', 'Events Tailor'),
                'url' => $event->organizerProfile?->slug ? route('public.organizers.show', $event->organizerProfile->slug) : route('homepage'),
            ],
        ];

        if ($image = $this->eventImage($event)) {
            $data['image'] = [$image];
        }

        if ($location) {
            $data['location'] = $location;
        }

        if ($ticket) {
            $data['offers'] = [
                '@type' => 'Offer',
                'url' => route('event.details', $event->slug),
                'price' => number_format((float) $ticket->price, 2, '.', ''),
                'priceCurrency' => $ticket->currency ?: 'USD',
                'availability' => $ticket->isSoldOut() ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock',
                'validFrom' => optional($ticket->sales_start_at)->toIso8601String() ?: now()->toIso8601String(),
            ];
        }

        return array_filter($data, fn ($value) => ! is_null($value));
    }

    public function organizerJsonLd(OrganizerProfile $organizer): array
    {
        $organizer->loadMissing(['ratingSummary', 'publicTrustBadges']);

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $organizer->organization_name,
            'description' => $this->plainText($organizer->description ?: $organizer->organization_name),
            'url' => route('public.organizers.show', $organizer->slug),
        ];

        if ($logo = $this->organizerImage($organizer)) {
            $data['logo'] = $logo;
            $data['image'] = $logo;
        }

        if ($organizer->ratingSummary && (int) $organizer->ratingSummary->approved_reviews_count > 0) {
            $data['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => number_format((float) $organizer->ratingSummary->average_rating, 2, '.', ''),
                'reviewCount' => (int) $organizer->ratingSummary->approved_reviews_count,
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }

        return $data;
    }

    private function eventLocationJsonLd(Event $event): ?array
    {
        if ($event->event_type === 'online') {
            return [
                '@type' => 'VirtualLocation',
                'url' => route('event.details', $event->slug),
            ];
        }

        $venue = $event->venueRecord;
        $name = $venue?->name ?: $event->venue;

        if (! $name) {
            return null;
        }

        return [
            '@type' => 'Place',
            'name' => $name,
            'address' => trim(collect([$venue?->address, $venue?->city, $venue?->country])->filter()->implode(', ')) ?: $name,
        ];
    }

    private function dateTimeIso(Event $event, string $type): ?string
    {
        $date = $type === 'start' ? $event->start_date : $event->end_date;
        $time = $type === 'start' ? $event->start_time : $event->end_time;

        if (! $date) {
            return null;
        }

        $dateText = $date->format('Y-m-d');
        $timeText = $time ? $time->format('H:i:s') : '00:00:00';

        return Carbon::parse($dateText . ' ' . $timeText, config('app.timezone'))->toIso8601String();
    }

    private function eventImage(Event $event): ?string
    {
        foreach ([$event->banner_image, $event->image, $event->venue_image, $event->logo] as $image) {
            if ($image) {
                return asset('storage/' . ltrim($image, '/'));
            }
        }

        return $this->defaultImage();
    }

    private function organizerImage(OrganizerProfile $organizer): ?string
    {
        foreach ([$organizer->banner, $organizer->logo] as $image) {
            if ($image) {
                return asset('storage/' . ltrim($image, '/'));
            }
        }

        return $this->defaultImage();
    }

    private function defaultImage(): string
    {
        return asset('images/image_why_choose.png');
    }

    private function plainText(?string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags((string) $value))) ?: config('app.name', 'Events Tailor');
    }
}
