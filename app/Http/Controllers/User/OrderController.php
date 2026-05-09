<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->with(['event', 'tickets'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('user.pages.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless((int) $order->user_id === (int) $request->user()->id, 404);

        $order->load(['event.venueRecord', 'items.eventTicket', 'tickets.event', 'tickets.eventTicket', 'tickets.seat']);

        return view('user.pages.orders.show', compact('order'));
    }
}
