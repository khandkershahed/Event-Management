<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventCancellationStoreRequest;
use App\Models\Event;
use App\Services\AuditLogService;
use App\Services\EventCancellationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class EventCancellationController extends Controller
{
    public function create(Request $request, Event $event, EventCancellationService $service): View
    {
        $organizer = $request->user()->organizerProfile;
        abort_unless($organizer && (int) $event->organizer_profile_id === (int) $organizer->id, 404);
        $event->load(['cancellationRequests' => fn ($query) => $query->latest()]);
        $hasOpenRequest = $service->hasOpenRequest($event);
        return view('organizer.event-cancellations.create', compact('event', 'hasOpenRequest'));
    }

    public function store(EventCancellationStoreRequest $request, Event $event, EventCancellationService $service, AuditLogService $auditLogService): RedirectResponse
    {
        $organizer = $request->user()->organizerProfile;
        abort_unless($organizer && (int) $event->organizer_profile_id === (int) $organizer->id, 404);
        try {
            $cancellation = $service->requestCancellation($event, $organizer, (int) $request->user()->id, $request->input('reason'));
            $auditLogService->record(
                'organizer.event_cancellation.requested',
                $cancellation,
                [],
                $cancellation->only(['event_id', 'organizer_profile_id', 'requested_by', 'status']),
                'Organizer requested event cancellation for: ' . $event->name,
                $request->user(),
                'web',
                $request
            );

            return redirect()->route('organizer.events.show', $event)->with('success', 'Event cancellation request submitted for admin review.');
        } catch (RuntimeException $exception) {
            return back()->withInput()->withErrors(['reason' => $exception->getMessage()]);
        }
    }
}
