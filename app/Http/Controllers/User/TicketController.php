<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OrderTicket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $tickets = OrderTicket::query()
            ->with(['order', 'event', 'eventTicket', 'seat.section'])
            ->whereHas('order', fn ($query) => $query->where('user_id', $request->user()->id))
            ->latest()
            ->paginate(12);

        return view('user.pages.tickets.index', compact('tickets'));
    }

    public function show(Request $request, OrderTicket $ticket): View
    {
        $ticket = $this->ownedTicket($request, $ticket);
        $isPrint = false;

        return view('user.pages.tickets.show', compact('ticket', 'isPrint'));
    }

    public function print(Request $request, OrderTicket $ticket): View
    {
        $ticket = $this->ownedTicket($request, $ticket);
        $isPrint = true;

        return view('user.pages.tickets.show', compact('ticket', 'isPrint'));
    }

    private function ownedTicket(Request $request, OrderTicket $ticket): OrderTicket
    {
        $ticket->load(['order', 'event.venueRecord', 'eventTicket', 'seat.section']);

        abort_unless($ticket->order && (int) $ticket->order->user_id === (int) $request->user()->id, 404);

        return $ticket;
    }
}
