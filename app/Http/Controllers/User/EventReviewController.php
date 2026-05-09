<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MarketplaceEventReview;
use App\Services\MarketplaceNotificationService;
use App\Services\MarketplaceReviewService;
use Illuminate\Http\Request;

class EventReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = MarketplaceEventReview::with(['event', 'order'])->where('user_id', $request->user()->id)->latest()->paginate(15);
        return view('user.pages.reviews.index', compact('reviews'));
    }
    public function create(Request $request, Event $event, MarketplaceReviewService $reviewService)
    {
        $event->loadMissing('organizerProfile');
        $orders = $reviewService->eligibleOrdersFor($request->user(), $event);
        return view('user.pages.reviews.create', compact('event', 'orders'));
    }
    public function store(Request $request, Event $event, MarketplaceReviewService $reviewService, MarketplaceNotificationService $notificationService)
    {
        $validated = $request->validate(['order_id' => ['nullable', 'integer', 'exists:orders,id'], 'rating' => ['required', 'integer', 'min:1', 'max:5'], 'title' => ['nullable', 'string', 'max:150'], 'body' => ['nullable', 'string', 'max:2000']]);
        $review = $reviewService->createReview($request->user(), $event, $validated);
        $notificationService->notifyReviewSubmitted($review);
        return redirect()->route('user.reviews.index')->with('success', 'Your review was submitted and is waiting for admin approval.');
    }
}
