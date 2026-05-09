<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MarketplaceSupportTicket;
use App\Models\OrganizerPayout;
use App\Services\MarketplaceSupportService;
use App\Services\OrganizerTeamAccessService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct(
        protected MarketplaceSupportService $supportService,
        protected OrganizerTeamAccessService $teamAccessService
    ) {
    }

    public function index(Request $request): View
    {
        $profile = $this->profile($request);

        $tickets = MarketplaceSupportTicket::query()
            ->with(['event', 'organizerPayout', 'latestMessage'])
            ->where('organizer_profile_id', $profile->id)
            ->latest('id')
            ->paginate(12);

        return view('organizer.support-tickets.index', compact('tickets', 'profile'));
    }

    public function create(Request $request): View
    {
        $profile = $this->profile($request);

        return view('organizer.support-tickets.create', [
            'profile' => $profile,
            'types' => MarketplaceSupportTicket::types(),
            'priorities' => MarketplaceSupportTicket::priorities(),
            'events' => Event::query()->where('organizer_profile_id', $profile->id)->latest('id')->limit(50)->get(),
            'payouts' => OrganizerPayout::query()->where('organizer_profile_id', $profile->id)->latest('id')->limit(50)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $profile = $this->profile($request);

        $data = $request->validate([
            'type' => ['required', 'string', 'in:' . implode(',', MarketplaceSupportTicket::types())],
            'priority' => ['nullable', 'string', 'in:' . implode(',', MarketplaceSupportTicket::priorities())],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
            'organizer_payout_id' => ['nullable', 'integer', 'exists:organizer_payouts,id'],
        ]);

        if (! empty($data['event_id'])) {
            abort_unless(Event::where('id', $data['event_id'])->where('organizer_profile_id', $profile->id)->exists(), 403);
        }

        if (! empty($data['organizer_payout_id'])) {
            abort_unless(OrganizerPayout::where('id', $data['organizer_payout_id'])->where('organizer_profile_id', $profile->id)->exists(), 403);
        }

        $ticket = $this->supportService->createTicket([
            ...$data,
            'organizer_profile_id' => $profile->id,
        ], $request->user(), 'web');

        return redirect()->route('organizer.support-tickets.show', $ticket)->with('success', 'Support ticket created successfully.');
    }

    public function show(Request $request, MarketplaceSupportTicket $supportTicket): View
    {
        $profile = $this->profile($request);
        abort_unless($this->supportService->organizerCanView($profile, $supportTicket), 404);
        $supportTicket->load(['messages.sender', 'event', 'organizerPayout']);

        return view('organizer.support-tickets.show', compact('supportTicket', 'profile'));
    }

    public function reply(Request $request, MarketplaceSupportTicket $supportTicket): RedirectResponse
    {
        $profile = $this->profile($request);
        abort_unless($this->supportService->organizerCanView($profile, $supportTicket), 404);
        abort_unless($supportTicket->isOpenForReplies(), 422, 'This ticket is already resolved or closed.');

        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $this->supportService->addMessage($supportTicket, $request->user(), 'web', $data['body']);

        if ($supportTicket->status !== MarketplaceSupportTicket::STATUS_OPEN) {
            $supportTicket->forceFill(['status' => MarketplaceSupportTicket::STATUS_PENDING])->save();
        }

        return redirect()->route('organizer.support-tickets.show', $supportTicket)->with('success', 'Reply added successfully.');
    }

    protected function profile(Request $request)
    {
        $profile = $request->attributes->get('organizer_profile') ?: $this->teamAccessService->resolveProfileFor($request->user());
        abort_unless($profile, 403);

        return $profile;
    }
}
