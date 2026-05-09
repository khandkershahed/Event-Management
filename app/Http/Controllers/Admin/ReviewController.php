<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MarketplaceEventReview;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\MarketplaceNotificationService;
use App\Services\MarketplaceReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketplaceEventReview::with(['event', 'user', 'organizerProfile', 'order', 'reviewedBy'])->latest();
        if ($request->filled('status') && in_array($request->status, MarketplaceEventReview::statuses(), true)) { $query->where('status', $request->status); }
        if ($request->filled('rating') && in_array((int) $request->rating, [1, 2, 3, 4, 5], true)) { $query->where('rating', (int) $request->rating); }
        if ($request->filled('event_id') && Event::whereKey($request->event_id)->exists()) { $query->where('event_id', $request->event_id); }
        if ($request->filled('organizer_id') && OrganizerProfile::whereKey($request->organizer_id)->exists()) { $query->where('organizer_profile_id', $request->organizer_id); }
        if ($request->filled('user_id') && User::whereKey($request->user_id)->exists()) { $query->where('user_id', $request->user_id); }
        $reviews = $query->paginate(20)->withQueryString();
        $events = Event::orderBy('name')->get(['id', 'name']);
        $organizers = OrganizerProfile::orderBy('organization_name')->get(['id', 'organization_name']);
        return view('admin.pages.reviews.index', compact('reviews', 'events', 'organizers'));
    }
    public function approve(MarketplaceEventReview $review, Request $request, MarketplaceReviewService $reviewService, MarketplaceNotificationService $notificationService, AuditLogService $auditLogService)
    {
        $validated = $request->validate(['admin_note' => ['nullable', 'string', 'max:1000']]);
        $old = $review->only(['status', 'reviewed_by', 'reviewed_at', 'admin_note']);
        $review->approve($request->user('admin'), $validated['admin_note'] ?? null);
        $reviewService->refreshOrganizerRating($review->organizer_profile_id);
        $notificationService->notifyReviewApproved($review);
        $auditLogService->record('admin.review.approved', $review, $old, $review->fresh()->only(['status', 'reviewed_by', 'reviewed_at', 'admin_note']), 'Admin approved marketplace review.');
        return back()->with('success', 'Review approved.');
    }
    public function reject(MarketplaceEventReview $review, Request $request, MarketplaceReviewService $reviewService, AuditLogService $auditLogService)
    {
        $validated = $request->validate(['admin_note' => ['nullable', 'string', 'max:1000']]);
        $old = $review->only(['status', 'reviewed_by', 'reviewed_at', 'admin_note']);
        $review->reject($request->user('admin'), $validated['admin_note'] ?? null);
        $reviewService->refreshOrganizerRating($review->organizer_profile_id);
        $auditLogService->record('admin.review.rejected', $review, $old, $review->fresh()->only(['status', 'reviewed_by', 'reviewed_at', 'admin_note']), 'Admin rejected marketplace review.');
        return back()->with('success', 'Review rejected.');
    }
    public function hide(MarketplaceEventReview $review, Request $request, MarketplaceReviewService $reviewService, AuditLogService $auditLogService)
    {
        $validated = $request->validate(['admin_note' => ['nullable', 'string', 'max:1000']]);
        $old = $review->only(['status', 'reviewed_by', 'reviewed_at', 'admin_note']);
        $review->hide($request->user('admin'), $validated['admin_note'] ?? null);
        $reviewService->refreshOrganizerRating($review->organizer_profile_id);
        $auditLogService->record('admin.review.hidden', $review, $old, $review->fresh()->only(['status', 'reviewed_by', 'reviewed_at', 'admin_note']), 'Admin hid marketplace review.');
        return back()->with('success', 'Review hidden.');
    }
}
