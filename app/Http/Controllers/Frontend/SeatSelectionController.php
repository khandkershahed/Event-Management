<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeatLockRequest;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\SeatingSeat;
use App\Services\CartService;
use App\Services\SeatLockService;
use App\Services\Seating\PublicSeatMapService;
use Illuminate\Http\Request;
use RuntimeException;

class SeatSelectionController extends Controller
{
    public function __construct(
        protected SeatLockService $seatLockService,
        protected CartService $cartService,
        protected PublicSeatMapService $publicSeatMapService
    ) {
    }

    public function index(Event $event, Request $request)
    {
        abort_unless($event->status === Event::STATUS_PUBLISHED, 404);

        $event->loadMissing(['seatingPlan.sections.seats', 'publicTickets']);

        if (! $event->seatingPlan) {
            abort(404, 'No seating plan is assigned to this event.');
        }

        $ticket = null;
        if ($request->filled('ticket')) {
            $ticket = EventTicket::where('event_id', $event->id)->publiclyAvailable()->findOrFail($request->integer('ticket'));
        }

        $seatStatuses = $this->seatLockService->seatStatusMap($event, $request->session()->getId(), auth()->id());
        $seatMap = $this->publicSeatMapService->build($event, $seatStatuses);

        return view('frontend.pages.tickets.select_seats', [
            'event' => $event,
            'ticket' => $ticket,
            'tickets' => $event->publicTickets,
            'sections' => $event->seatingPlan->sections,
            'seatStatuses' => $seatStatuses,
            'seatMap' => $seatMap,
        ]);
    }

    public function lock(SeatLockRequest $request, Event $event)
    {
        abort_unless($event->status === Event::STATUS_PUBLISHED, 404);

        $ticket = EventTicket::where('event_id', $event->id)->publiclyAvailable()->findOrFail($request->integer('event_ticket_id'));
        $seat = SeatingSeat::with('section')->findOrFail($request->integer('seat_id'));

        try {
            $lock = $this->seatLockService->lockSeat($event, $ticket, $seat, $request->session()->getId(), auth()->id());
            $this->cartService->addLockedSeat($lock, $request->session()->getId(), auth()->id());
        } catch (RuntimeException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Seat locked and added to cart.',
            'redirect' => route('frontend.cart'),
        ]);
    }

    public function unlock(Request $request, Event $event)
    {
        $request->validate([
            'seat_id' => ['required', 'integer', 'exists:seating_seats,id'],
        ]);

        $seat = SeatingSeat::findOrFail($request->integer('seat_id'));
        $unlocked = $this->seatLockService->unlockSeat($event, $seat, $request->session()->getId(), auth()->id());

        if (! $unlocked) {
            return response()->json([
                'status' => 'error',
                'message' => 'This seat lock does not belong to your current cart.',
            ], 403);
        }

        $this->cartService->removeLockedSeat($event, $seat, $request->session()->getId(), auth()->id());

        return response()->json([
            'status' => 'success',
            'message' => 'Seat lock cancelled and removed from cart.',
        ]);
    }
}
