<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\OrganizerProfile;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => route('homepage'), 'priority' => '1.0', 'changefreq' => 'daily', 'lastmod' => now()],
            ['loc' => route('all.events'), 'priority' => '0.9', 'changefreq' => 'daily', 'lastmod' => now()],
        ]);

        Event::query()
            ->publiclyVisible()
            ->select(['slug', 'updated_at'])
            ->latest('updated_at')
            ->limit(1000)
            ->get()
            ->each(function (Event $event) use ($urls): void {
                $urls->push([
                    'loc' => route('event.details', $event->slug),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                    'lastmod' => $event->updated_at ?: now(),
                ]);
            });

        OrganizerProfile::query()
            ->where('status', OrganizerProfile::STATUS_APPROVED)
            ->whereNotNull('slug')
            ->select(['slug', 'updated_at'])
            ->latest('updated_at')
            ->limit(1000)
            ->get()
            ->each(function (OrganizerProfile $organizer) use ($urls): void {
                $urls->push([
                    'loc' => route('public.organizers.show', $organizer->slug),
                    'priority' => '0.7',
                    'changefreq' => 'weekly',
                    'lastmod' => $organizer->updated_at ?: now(),
                ]);
            });

        $xml = view('frontend.seo.sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /organizer',
            'Disallow: /user',
            'Disallow: /checkout',
            'Disallow: /cart',
            'Disallow: /payment',
            'Sitemap: ' . url('/sitemap.xml'),
            '',
        ]);

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
