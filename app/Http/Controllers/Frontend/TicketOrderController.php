<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Order;
use App\Models\SeatLock;
use App\Models\EventTicket;
use App\Models\OrderTicket;
use App\Models\SeatingPlan;
use App\Models\SeatingSeat;
use Illuminate\Http\Request;
use App\Models\SeatingSection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TicketOrderController extends Controller
{
    /**
     * STEP 1 — Show Event Details + Seat Map + Ticket Types
     */

    public function showEvent($slug)
    {
        $event = Event::with(['eventType'])
            ->where('slug', $slug)
            ->firstOrFail();

        // 1. Get Ticket Types
        $ticketTypes = EventTicket::where('event_id', $event->id)
            ->where('is_active', 1)
            ->orderBy('price', 'ASC')
            ->get();

        // 2. Get Seating Data
        $sections = [];
        $seats = [];
        $designJson = [];
        $seatStatuses = [];

        if ($event->seating_plan_id) {
            $plan = SeatingPlan::find($event->seating_plan_id);

            // Pass the raw visual design
            $designJson = $plan->design_json;

            // Get DB Sections to map Names to IDs
            $sections = SeatingSection::where('seating_plan_id', $plan->id)->get();

            // Get DB Seats to map visual seats to DB IDs
            $seats = SeatingSeat::whereIn('section_id', $sections->pluck('id'))->get();

            // Calculate Availability (Sold/Locked)
            $seatStatuses = $this->getSeatStatusMap($event->id);
        }

        $relatedEvents = Event::where('id', '!=', $event->id)->latest()->take(6)->get();

        return view('frontend.pages.eventDetails', [
            'event'         => $event,
            'ticketTypes'   => $ticketTypes,
            'sections'      => $sections,      // Needed for ID mapping
            'seats'         => $seats,         // Needed for ID mapping
            'seatStatuses'  => $seatStatuses,  // Needed for Gray/Yellow coloring
            'designJson'    => $designJson,    // The Visual Layout
            'relatedEvents' => $relatedEvents,
        ]);
    }

    // ... keep your getSeatStatusMap, addToCart, etc functions as they were ...

    private function getSeatStatusMap($eventId)
    {
        $map = [];
        // Get all potential seats
        $seats = SeatingSeat::whereHas('section.seatingPlan.events', function ($q) use ($eventId) {
            $q->where('id', $eventId);
        })->get();

        foreach ($seats as $seat) {
            if ($seat->is_disabled) {
                $map[$seat->id] = 'sold'; // Treat as sold (grayed out/unclickable)
            } else {
                $map[$seat->id] = 'available';
            }
        }

        // Locks
        $locks = SeatLock::where('event_id', $eventId)->where('expires_at', '>', now())->get();
        foreach ($locks as $lock) {
            // If locked by current user, it's 'selected', else 'locked'
            if ($lock->session_id === session()->getId()) {
                $map[$lock->seat_id] = 'selected'; // We treat own locks as selection
            } else {
                $map[$lock->seat_id] = 'locked';
            }
        }

        // Sold
        $sold = OrderTicket::where('event_id', $eventId)->get();
        foreach ($sold as $t) {
            $map[$t->seat_id] = 'sold';
        }

        return $map;
    }

    // public function showEvent($slug)
    // {
    //     $event = Event::with([
    //         'images',
    //         'eventType',
    //         'seatingPlan.sections.seats' // <-- This loads everything
    //     ])
    //         ->where('slug', $slug)
    //         ->where('status', 'active')
    //         ->firstOrFail();

    //     // Load ticket types
    //     $ticketTypes = EventTicket::where('event_id', $event->id)
    //         ->orderBy('price', 'ASC')
    //         ->get();

    //     // Seating plan
    //     $plan = $event->seatingPlan;

    //     // Prepare seat availability map
    //     $seatStatuses = $this->getSeatStatusMap($event->id);

    //     return view('frontend.pages.eventDetails', [
    //         'event'        => $event,
    //         'plan'         => $plan,
    //         'ticketTypes'  => $ticketTypes,
    //         'seatStatuses' => $seatStatuses, // used by JS
    //         'designJson'   => $plan->design_json ?? [],
    //     ]);
    // }

    /**
     * STEP 1b — Seat Availability (AJAX)
     */
    public function fetchSeatAvailability(Request $request, Event $event)
    {
        return response()->json([
            'status' => 'success',
            'seats'  => $this->getSeatStatusMap($event->id),
        ]);
    }

    /**
     * Internal: Build seat availability mapping
     * Output:
     * [
     *   seat_id => 'available' | 'locked' | 'sold'
     * ]
     */


    public function selectSeatsPage(Event $event, Request $request)
    {
        $ticketId = $request->ticket;

        $ticket = EventTicket::where('event_id', $event->id)
            ->where('id', $ticketId)
            ->firstOrFail();

        $plan = $event->seatingPlan;

        if (!$plan) {
            abort(404, 'No seating plan assigned.');
        }

        // Seat availability
        $seatStatuses = $this->getSeatStatusMap($event->id);

        // Prepare arrays for JS
        $soldSeats = array_keys(array_filter($seatStatuses, fn($s) => $s === 'sold'));
        $lockedSeats = array_keys(array_filter($seatStatuses, fn($s) => $s === 'locked'));

        return view('frontend.pages.tickets.select_seats', [
            'event'        => $event,
            'ticketId'     => $ticketId,
            'ticketPrice'  => $ticket->price,
            'designJson'   => $plan->design_json,
            'sections'     => $plan->sections,
            'soldSeats'    => $soldSeats,
            'lockedSeats'  => $lockedSeats,
        ]);
    }




    // private function getSeatStatusMap($eventId)
    // {
    //     $map = [];

    //     // 1. Get all seats in the event’s seating plan
    //     // $seats = SeatingSeat::whereHas('section.plan.event', function ($q) use ($eventId) {
    //     //     $q->where('id', $eventId);
    //     // })->get();

    //     $seats = SeatingSeat::whereHas('section.seatingPlan.events', function ($q) use ($eventId) {
    //         $q->where('id', $eventId);
    //     })->get();


    //     foreach ($seats as $seat) {
    //         $map[$seat->id] = 'available';
    //     }

    //     // 2. Apply LOCKED status
    //     $activeLocks = SeatLock::where('event_id', $eventId)
    //         ->where('expires_at', '>', Carbon::now())
    //         ->get();

    //     foreach ($activeLocks as $lock) {
    //         if (isset($map[$lock->seat_id])) {
    //             $map[$lock->seat_id] = 'locked';
    //         }
    //     }

    //     // 3. Apply SOLD status
    //     $soldTickets = OrderTicket::where('event_id', $eventId)->get();

    //     foreach ($soldTickets as $t) {
    //         if (isset($map[$t->seat_id])) {
    //             $map[$t->seat_id] = 'sold';
    //         }
    //     }

    //     return $map;
    // }

    /**
     * STEP 2 — Lock Selected Seats
     */
    public function lockSeat(Request $request, Event $event)
    {
        $request->validate([
            'seat_id' => 'required|integer|exists:seating_seats,id',
        ]);

        $seatId = $request->seat_id;

        // First: Clear expired locks
        $this->clearExpiredLocks();

        // 1 — Check if seat is already SOLD
        $sold = OrderTicket::where('event_id', $event->id)
            ->where('seat_id', $seatId)
            ->exists();

        if ($sold) {
            return $this->lockError('Seat already sold.');
        }

        // 2 — Check if seat is LOCKED by someone else
        $existingLock = SeatLock::where('event_id', $event->id)
            ->where('seat_id', $seatId)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingLock && $existingLock->session_id !== session()->getId()) {
            return $this->lockError('Seat is currently locked by another user.');
        }

        // 3 — Lock the seat (create or refresh)
        $lock = SeatLock::updateOrCreate(
            [
                'event_id'   => $event->id,
                'seat_id'    => $seatId,
                'session_id' => session()->getId(),
            ],
            [
                'expires_at' => now()->addSeconds($this->getLockDuration()),
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Seat locked successfully.',
            'lock'    => $lock,
        ]);
    }

    /**
     * Translate seat-locking status codes into readable messages.
     */
    private function lockError($status)
    {
        switch ($status) {
            case 'seat_not_found':
                return 'Seat does not exist.';
            case 'invalid_section':
                return 'This seat does not belong to the selected ticket section.';
            case 'locked_by_someone':
                return 'This seat is temporarily reserved by another user.';
            case 'sold':
                return 'This seat has already been purchased.';
            default:
                return 'Unknown seat locking error.';
        }
    }

    /**
     * STEP 2 — Unlock a Seat (User unselects)
     */
    public function unlockSeat(Request $request, Event $event)
    {
        $request->validate([
            'seat_id' => 'required|integer',
        ]);

        SeatLock::where('event_id', $event->id)
            ->where('seat_id', $request->seat_id)
            ->where('session_id', session()->getId())
            ->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Seat unlocked.',
        ]);
    }


    /**
     * STEP 2 — Validate Seat Before Add to Cart
     */
    private function validateBeforeAddToCart($eventId, $seatId)
    {
        // Check SOLD
        if (
            OrderTicket::where('event_id', $eventId)
            ->where('seat_id', $seatId)
            ->exists()
        ) {
            return 'sold';
        }

        // Check LOCKED by someone else
        $lock = SeatLock::where('event_id', $eventId)
            ->where('seat_id', $seatId)
            ->where('expires_at', '>', now())
            ->first();

        if ($lock && $lock->session_id !== session()->getId()) {
            return 'locked';
        }

        return 'ok';
    }


    /**
     * STEP 2 — Add To Cart
     */
    public function addToCart(Request $request, Event $event)
    {
        $request->validate([
            'seat_id'     => 'required|integer|exists:seating_seats,id',
            'ticket_type' => 'required|integer|exists:event_tickets,id',
        ]);

        $seatId = $request->seat_id;

        // 1 — Validate seat
        $status = $this->validateBeforeAddToCart($event->id, $seatId);

        if ($status !== 'ok') {
            return response()->json([
                'status'  => 'error',
                'message' => "Cannot add seat: $status",
            ], 422);
        }

        // 2 — Ensure seat is locked for THIS session
        $lock = SeatLock::where('event_id', $event->id)
            ->where('seat_id', $seatId)
            ->where('session_id', session()->getId())
            ->where('expires_at', '>', now())
            ->first();

        if (!$lock) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Seat must be locked before adding to cart.',
            ], 422);
        }

        // 3 — Add to SESSION CART
        $cart = session()->get('cart', []);

        $cart[$seatId] = [
            'event_id'     => $event->id,
            'seat_id'      => $seatId,
            'ticket_type'  => $request->ticket_type,
            'added_at'     => now()->toDateTimeString(),
        ];

        session(['cart' => $cart]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Seat added to cart.',
            'cart'    => $cart,
        ]);
    }


    /**
     * STEP 2 — Remove seat from cart
     */
    public function removeFromCart(Request $request, Event $event)
    {
        $request->validate([
            'seat_id' => 'required|integer',
        ]);

        $seatId = $request->seat_id;

        // Remove from cart
        $cart = session()->get('cart', []);

        unset($cart[$seatId]);

        session(['cart' => $cart]);

        // Unlock seat
        SeatLock::where('event_id', $event->id)
            ->where('seat_id', $seatId)
            ->where('session_id', session()->getId())
            ->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Seat removed from cart.',
        ]);
    }


    /**
     * STEP 2 — Auto Expire Old Locks
     */
    private function clearExpiredLocks()
    {
        SeatLock::where('expires_at', '<', now())->delete();
    }


    /**
     * Lock timer (seconds)
     */
    private function getLockDuration()
    {
        return 300; // 5 minutes
    }

    /**
     * STEP 3 — Show Cart Page
     */
    public function showCart()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return view('frontend.pages.tickets.cart', [
                'items' => [],
            ]);
        }

        // Group cart data with seat + ticket type info
        $items = [];

        foreach ($cart as $seatId => $item) {

            $seat = SeatingSeat::with('section')->find($seatId);
            $ticketType = EventTicket::find($item['ticket_type']);
            $event = Event::find($item['event_id']);

            if ($seat && $ticketType && $event) {
                $items[] = [
                    'event'        => $event,
                    'seat'         => $seat,
                    'ticket_type'  => $ticketType,
                    'price'        => $ticketType->price,
                ];
            }
        }

        return view('frontend.pages.tickets.cart', [
            'items' => $items,
        ]);
    }


    /**
     * STEP 3 — Checkout Page (Before Payment)
     */
    public function checkout(Event $event)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('frontend.cart')->with('error', 'Your cart is empty!');
        }

        // 1 — Validate all seats again before checkout
        foreach ($cart as $seatId => $item) {

            $status = $this->validateBeforeAddToCart($event->id, $seatId);

            if ($status !== 'ok') {
                return redirect()->route('frontend.cart')
                    ->with('error', "Seat $seatId is no longer available ($status).");
            }
        }

        // 2 — Prepare totals
        $total = 0;
        $summary = [];

        foreach ($cart as $seatId => $item) {

            $ticketType = EventTicket::find($item['ticket_type']);
            $seat       = SeatingSeat::find($seatId);

            $summary[] = [
                'seat'        => $seat,
                'ticket_type' => $ticketType,
                'price'       => $ticketType->price,
            ];

            $total += $ticketType->price;
        }

        return view('frontend.pages.tickets.checkout', [
            'event'   => $event,
            'summary' => $summary,
            'total'   => $total,
        ]);
    }


    /**
     * STEP 3 — Process Order (After Payment)
     * Works for both:
     * → COD
     * → SSLCommerz / Stripe / PayPal (later)
     */
    public function processOrder(Request $request, Event $event)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('frontend.cart')->with('error', 'Your cart is empty!');
        }

        // Validate again before final order
        foreach ($cart as $seatId => $item) {
            $status = $this->validateBeforeAddToCart($event->id, $seatId);

            if ($status !== 'ok') {
                return redirect()->route('frontend.cart')
                    ->with('error', "Seat $seatId is no longer available.");
            }
        }

        DB::beginTransaction();

        try {
            // 1 — Create order
            $order = Order::create([
                'event_id'     => $event->id,
                'user_id'      => auth()->id(), // guest support will be added later
                'total_amount' => 0,
                'payment_type' => $request->payment_type ?? 'cod',
                'payment_status' => 'pending',
                'order_status'   => 'pending',
            ]);

            $orderTotal = 0;

            // 2 — Add order tickets
            foreach ($cart as $seatId => $item) {

                $ticketType = EventTicket::find($item['ticket_type']);
                $seat       = SeatingSeat::find($seatId);

                OrderTicket::create([
                    'order_id'        => $order->id,
                    'event_id'        => $event->id,
                    'seat_id'         => $seatId,
                    'ticket_type_id'  => $ticketType->id,
                    'price'           => $ticketType->price,
                    'section_id'      => $seat->section_id,
                ]);

                $orderTotal += $ticketType->price;

                // Remove lock
                SeatLock::where('event_id', $event->id)
                    ->where('seat_id', $seatId)
                    ->delete();
            }

            // 3 — Update order total
            $order->update([
                'total_amount' => $orderTotal,
                'payment_status' => 'paid',     // For COD/Free events
                'order_status'   => 'confirmed',
            ]);

            DB::commit();

            // 4 — Clear cart
            session()->forget('cart');

            return redirect()
                ->route('frontend.order.success', $order->id);
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->route('frontend.cart')
                ->with('error', 'Order failed: ' . $e->getMessage());
        }
    }


    /**
     * STEP 3 — Order Success Page
     */
    public function orderSuccess($orderId)
    {
        $order = Order::with(['tickets.seat', 'tickets.ticketType'])
            ->findOrFail($orderId);

        return view('frontend.pages.tickets.orderSuccess', compact('order'));
    }


    /**
     * STEP 3 — Ticket PDF Download
     */
    public function downloadTicket($ticketId)
    {
        $ticket = OrderTicket::with(['order', 'event', 'seat'])
            ->findOrFail($ticketId);

        // PDF generation will use dompdf or snappy
        $pdf = \PDF::loadView('pdf.ticketPdf', [
            'ticket' => $ticket,
        ]);

        return $pdf->download('ticket-' . $ticketId . '.pdf');
    }
}
