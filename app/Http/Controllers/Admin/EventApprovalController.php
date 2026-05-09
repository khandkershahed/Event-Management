<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use App\Services\AuditLogService;
use App\Services\MarketplaceNotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventApprovalController extends Controller
{
    public function index(): View
    {
        $events = Event::with(['organizerProfile.user', 'eventType', 'venueModel'])
            ->pendingReview()
            ->latest('submitted_at')
            ->paginate(15);

        return view('admin.pages.event-approvals.index', compact('events'));
    }

    public function show(Event $event): View
    {
        $event->load(['organizerProfile.user', 'eventType', 'venueModel', 'seatingPlan']);

        return view('admin.pages.event-approvals.show', compact('event'));
    }

    public function approve(Request $request, Event $event, MarketplaceNotificationService $notificationService, AuditLogService $auditLogService): RedirectResponse
    {
        abort_unless(in_array($event->status, [Event::STATUS_SUBMITTED, Event::STATUS_UNDER_REVIEW], true), 403);

        $oldValues = $event->only(['status', 'approved_at', 'approved_by', 'rejection_reason']);

        $event->update([
            'status' => Event::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => $request->user('admin')?->id,
            'rejection_reason' => null,
        ]);

        $approvedEvent = $event->fresh(['organizerProfile.user']);
        $notificationService->notifyEventApproved($approvedEvent);
        $auditLogService->record(
            'admin.event.approved',
            $approvedEvent,
            $oldValues,
            $approvedEvent->only(['status', 'approved_at', 'approved_by', 'rejection_reason']),
            'Admin approved event: ' . $approvedEvent->name,
            $request->user('admin'),
            'admin',
            $request
        );

        return redirect()->route('admin.event-approvals.show', $event)->with('success', 'Event approved successfully. Organizer can now publish it.');
    }

    public function reject(Request $request, Event $event, MarketplaceNotificationService $notificationService, AuditLogService $auditLogService): RedirectResponse
    {
        abort_unless(in_array($event->status, [Event::STATUS_SUBMITTED, Event::STATUS_UNDER_REVIEW], true), 403);

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:5000'],
        ]);

        $oldValues = $event->only(['status', 'approved_at', 'approved_by', 'rejection_reason']);

        $event->update([
            'status' => Event::STATUS_REJECTED,
            'approved_at' => null,
            'approved_by' => null,
            'rejection_reason' => $data['rejection_reason'],
        ]);

        $rejectedEvent = $event->fresh(['organizerProfile.user']);
        $notificationService->notifyEventRejected($rejectedEvent, $data['rejection_reason']);
        $auditLogService->record(
            'admin.event.rejected',
            $rejectedEvent,
            $oldValues,
            $rejectedEvent->only(['status', 'approved_at', 'approved_by', 'rejection_reason']),
            'Admin rejected event: ' . $rejectedEvent->name,
            $request->user('admin'),
            'admin',
            $request
        );

        return redirect()->route('admin.event-approvals.show', $event)->with('success', 'Event rejected and returned to organizer.');
    }
}
