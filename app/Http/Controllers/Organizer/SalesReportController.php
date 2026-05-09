<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SalesReportController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->organizerProfile;
        $events = $profile->events()
            ->with(['eventType', 'venueModel'])
            ->orderByDesc('start_date')
            ->paginate(12);

        $eventIds = $profile->events()->pluck('id');

        return view('organizer.reports.sales', [
            'events' => $events,
            'summary' => $this->summaryForEvents($eventIds),
        ]);
    }

    public function event(Request $request, Event $event): View
    {
        $profile = $request->user()->organizerProfile;
        abort_if(! $profile || (int) $event->organizer_profile_id !== (int) $profile->id, Response::HTTP_NOT_FOUND);

        $event->load(['eventType', 'venueModel', 'tickets']);

        return view('organizer.reports.event', [
            'event' => $event,
            'summary' => $this->summaryForEvents(collect([$event->id])),
            'ticketRows' => $this->ticketTypeRows($event),
            'orders' => Order::query()
                ->with(['user', 'items'])
                ->where('event_id', $event->id)
                ->latest('id')
                ->paginate(20),
        ]);
    }

    private function summaryForEvents(Collection $eventIds): array
    {
        $ids = $eventIds->filter()->values();

        if ($ids->isEmpty()) {
            return [
                'total_orders' => 0,
                'paid_orders' => 0,
                'gross_revenue' => 0,
                'issued_tickets' => 0,
                'checked_in' => 0,
                'pending_payment_amount' => 0,
            ];
        }

        $orders = Order::query()->whereIn('event_id', $ids);
        $paidOrders = (clone $orders)->where('payment_status', Order::PAYMENT_PAID);
        $pendingOrders = (clone $orders)->whereIn('payment_status', [Order::PAYMENT_UNPAID, Order::PAYMENT_PENDING, Order::PAYMENT_FAILED]);
        $tickets = OrderTicket::query()->whereIn('event_id', $ids);

        return [
            'total_orders' => (clone $orders)->count(),
            'paid_orders' => (clone $paidOrders)->count(),
            'gross_revenue' => (float) (clone $paidOrders)->sum('total'),
            'issued_tickets' => (clone $tickets)->count(),
            'checked_in' => (clone $tickets)->where('is_checked_in', true)->count(),
            'pending_payment_amount' => (float) (clone $pendingOrders)->sum('total'),
        ];
    }

    private function ticketTypeRows(Event $event): Collection
    {
        return OrderItem::query()
            ->selectRaw('event_ticket_id, ticket_name, SUM(quantity) as tickets_sold, SUM(subtotal) as gross_amount')
            ->whereHas('order', function ($query) use ($event) {
                $query->where('event_id', $event->id)
                    ->where('payment_status', Order::PAYMENT_PAID);
            })
            ->groupBy('event_ticket_id', 'ticket_name')
            ->orderBy('ticket_name')
            ->get();
    }
}
