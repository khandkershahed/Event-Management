<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceEventReview;
use App\Services\MarketplaceReviewService;
use App\Services\OrganizerTeamAccessService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request, OrganizerTeamAccessService $teamAccessService, MarketplaceReviewService $reviewService)
    {
        $organizerProfile = $teamAccessService->resolveProfileFor($request->user());
        abort_unless($organizerProfile, 403);
        $query = MarketplaceEventReview::with(['event', 'user', 'order'])->where('organizer_profile_id', $organizerProfile->id)->latest();
        if ($request->filled('status') && in_array($request->status, MarketplaceEventReview::statuses(), true)) { $query->where('status', $request->status); }
        if ($request->filled('rating') && in_array((int) $request->rating, [1, 2, 3, 4, 5], true)) { $query->where('rating', (int) $request->rating); }
        $reviews = $query->paginate(15)->withQueryString();
        $ratingSummary = $reviewService->refreshOrganizerRating($organizerProfile->id);
        return view('organizer.reviews.index', compact('reviews', 'organizerProfile', 'ratingSummary'));
    }
}
