<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OrganizerFollower;
use App\Models\OrganizerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\SeoMetaService;

class OrganizerProfileController extends Controller
{
    public function show(string $slug, Request $request, SeoMetaService $seoMetaService): View
    {
        $organizer = OrganizerProfile::query()
            ->with([
                'ratingSummary',
                'publicTrustBadges',
                'payoutMethod',
            ])
            ->withCount(['followers', 'events as published_events_count' => fn ($query) => $query->publiclyVisible()])
            ->where('slug', $slug)
            ->where('status', OrganizerProfile::STATUS_APPROVED)
            ->firstOrFail();

        $publishedEvents = $organizer->events()
            ->with(['eventType', 'venueRecord', 'publicTickets'])
            ->publiclyVisible()
            ->latest()
            ->paginate(9);

        $recentReviews = $organizer->marketplaceEventReviews()
            ->with(['event', 'user'])
            ->where('status', \App\Models\MarketplaceEventReview::STATUS_APPROVED)
            ->latest()
            ->take(5)
            ->get();

        $isFollowing = false;
        if ($request->user()) {
            $isFollowing = OrganizerFollower::query()
                ->where('user_id', $request->user()->id)
                ->where('organizer_profile_id', $organizer->id)
                ->exists();
        }

        $seoMeta = $seoMetaService->organizer($organizer);

        return view('frontend.pages.organizers.show', compact('organizer', 'publishedEvents', 'recentReviews', 'isFollowing', 'seoMeta'));
    }

    public function follow(OrganizerProfile $organizer, Request $request): RedirectResponse
    {
        abort_unless($organizer->status === OrganizerProfile::STATUS_APPROVED, 404);

        OrganizerFollower::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'organizer_profile_id' => $organizer->id,
        ]);

        return back()->with('success', 'Organizer followed successfully.');
    }

    public function unfollow(OrganizerProfile $organizer, Request $request): RedirectResponse
    {
        OrganizerFollower::query()
            ->where('user_id', $request->user()->id)
            ->where('organizer_profile_id', $organizer->id)
            ->delete();

        return back()->with('success', 'Organizer unfollowed successfully.');
    }
}
