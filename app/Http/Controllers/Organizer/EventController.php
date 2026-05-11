<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Models\Event;
use App\Models\EventType;
use App\Models\SeatingPlan;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\AuditLogService;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->organizerProfile;
        $events = Event::with(['eventType', 'venueModel', 'seatingPlan'])
            ->where('organizer_profile_id', $profile->id)
            ->latest()
            ->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $profile = $request->user()->organizerProfile;
        $venues = $profile->venues()->orderBy('name')->get();

        if ($venues->isEmpty()) {
            return redirect()->route('organizer.venues.create')->with('error', 'Please create a venue before creating an event.');
        }

        $event = new Event(['status' => Event::STATUS_DRAFT]);
        $eventTypes = EventType::orderBy('name')->get();
        $seatingPlans = $profile->seatingPlans()->whereIn('status', [SeatingPlan::STATUS_ACTIVE, SeatingPlan::STATUS_LOCKED])->orderBy('name')->get();

        return view('organizer.events.create', compact('event', 'eventTypes', 'venues', 'seatingPlans'));
    }

    public function store(EventStoreRequest $request): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;
        $venue = Venue::where('organizer_profile_id', $profile->id)->findOrFail($request->integer('venue_id'));
        $seatingPlan = null;

        if ($request->filled('seating_plan_id')) {
            $seatingPlan = SeatingPlan::where('organizer_profile_id', $profile->id)
                ->where('venue_id', $venue->id)
                ->findOrFail($request->integer('seating_plan_id'));
        }

        $event = Event::create(array_merge($this->payload($request), [
            'organizer_profile_id' => $profile->id,
            'venue_id' => $venue->id,
            'seating_plan_id' => $seatingPlan?->id,
            'venue' => $venue->name,
            'organizer_name' => $profile->organization_name,
            'organizer_brand' => $profile->organization_name,
            'status' => Event::STATUS_DRAFT,
            'added_by' => $request->user()->name,
        ]));

        return redirect()->route('organizer.events.control', $event)->with('success', 'Draft event created successfully. Continue setup from this control panel.');
    }

    public function show(Request $request, Event $event): View
    {
        $this->ensureOwnEvent($request, $event);
        $event->load(['eventType', 'venueModel', 'seatingPlan']);

        return view('organizer.events.show', compact('event'));
    }

    public function edit(Request $request, Event $event): View
    {
        $this->ensureOwnEvent($request, $event);
        abort_unless($event->canBeEditedByOrganizer(), 403, 'Only draft or rejected events can be edited.');

        $profile = $request->user()->organizerProfile;
        $eventTypes = EventType::orderBy('name')->get();
        $venues = $profile->venues()->orderBy('name')->get();
        $seatingPlans = $profile->seatingPlans()->whereIn('status', [SeatingPlan::STATUS_ACTIVE, SeatingPlan::STATUS_LOCKED])->orderBy('name')->get();

        return view('organizer.events.edit', compact('event', 'eventTypes', 'venues', 'seatingPlans'));
    }

    public function update(EventUpdateRequest $request, Event $event): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;
        $venue = Venue::where('organizer_profile_id', $profile->id)->findOrFail($request->integer('venue_id'));
        $seatingPlan = null;

        if ($request->filled('seating_plan_id')) {
            $seatingPlan = SeatingPlan::where('organizer_profile_id', $profile->id)
                ->where('venue_id', $venue->id)
                ->findOrFail($request->integer('seating_plan_id'));
        }

        $event->update(array_merge($this->payload($request), [
            'venue_id' => $venue->id,
            'seating_plan_id' => $seatingPlan?->id,
            'venue' => $venue->name,
            'rejection_reason' => null,
            'updated_by' => $request->user()->name,
        ]));

        return redirect()->route('organizer.events.control', $event)->with('success', 'Event updated successfully.');
    }

    public function destroy(Request $request, Event $event): RedirectResponse
    {
        $this->ensureOwnEvent($request, $event);
        abort_unless($event->status === Event::STATUS_DRAFT, 403, 'Only draft events can be deleted safely.');

        $event->delete();

        return redirect()->route('organizer.events.index')->with('success', 'Draft event deleted successfully.');
    }

    public function submit(Request $request, Event $event, AuditLogService $auditLogService): RedirectResponse
    {
        $this->ensureOwnEvent($request, $event);
        abort_unless($event->canBeSubmittedByOrganizer(), 403, 'Only draft or rejected events can be submitted.');

        $oldValues = $event->only(['status', 'submitted_at', 'rejection_reason']);

        $event->update([
            'status' => Event::STATUS_SUBMITTED,
            'submitted_at' => now(),
            'rejection_reason' => null,
        ]);

        $submittedEvent = $event->fresh();
        $auditLogService->record(
            'organizer.event.submitted',
            $submittedEvent,
            $oldValues,
            $submittedEvent->only(['status', 'submitted_at', 'rejection_reason']),
            'Organizer submitted event: ' . $submittedEvent->name,
            $request->user(),
            'web',
            $request
        );

        return redirect()->route('organizer.events.show', $event)->with('success', 'Event submitted for admin review.');
    }

    public function publish(Request $request, Event $event, AuditLogService $auditLogService): RedirectResponse
    {
        $this->ensureOwnEvent($request, $event);
        abort_unless($event->canBePublishedByOrganizer(), 403, 'Only approved events can be published.');

        $oldValues = $event->only(['status']);

        $event->update(['status' => Event::STATUS_PUBLISHED]);

        $publishedEvent = $event->fresh();
        $auditLogService->record(
            'organizer.event.published',
            $publishedEvent,
            $oldValues,
            $publishedEvent->only(['status']),
            'Organizer published event: ' . $publishedEvent->name,
            $request->user(),
            'web',
            $request
        );

        return redirect()->route('organizer.events.show', $event)->with('success', 'Event published successfully.');
    }

    private function payload(Request $request): array
    {
        $payload = [
            'event_type_id' => $request->input('event_type_id'),
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'tagline' => $request->input('tagline'),
            'description' => $request->input('description'),
            'video_teaser_url' => $request->input('video_teaser_url'),
            'location_map_url' => $request->input('location_map_url'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'purchase_deadline' => $request->input('purchase_deadline'),
            'total_capacity' => $request->input('total_capacity'),
            'age_restriction' => $request->input('age_restriction'),
            'organizer_name' => $request->input('organizer_name'),
            'organizer_brand' => $request->input('organizer_brand'),
            'terms_and_conditions' => $request->input('terms_and_conditions'),
        ];

        foreach (['logo', 'image', 'banner_image', 'organizer_logo', 'venue_image'] as $field) {
            if ($request->hasFile($field)) {
                $upload = customUpload($request->file($field), 'events/' . $field);

                if (($upload['status'] ?? 0) === 0) {
                    abort(422, $upload['error_message'] ?? 'File upload failed.');
                }

                $payload[$field] = $upload['file_path'];
            }
        }

        return $payload;
    }

    private function ensureOwnEvent(Request $request, Event $event): void
    {
        $profile = $request->user()->organizerProfile;

        abort_unless($profile && (int) $event->organizer_profile_id === (int) $profile->id, 403);
    }
}
