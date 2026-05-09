<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MarketplaceSupportTicket;
use App\Models\Order;
use App\Models\RefundRequest;
use App\Services\MarketplaceSupportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct(protected MarketplaceSupportService $supportService)
    {
    }

    public function index(Request $request): View
    {
        $tickets = MarketplaceSupportTicket::query()
            ->with(['event', 'order', 'latestMessage'])
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->paginate(12);

        return view('user.pages.support-tickets.index', compact('tickets'));
    }

    public function create(Request $request): View
    {
        $orders = Order::query()->where('user_id', $request->user()->id)->latest('id')->limit(50)->get();
        $events = Event::query()->publiclyVisible()->latest('id')->limit(50)->get();
        $refunds = RefundRequest::query()->where('user_id', $request->user()->id)->latest('id')->limit(50)->get();

        return view('user.pages.support-tickets.create', [
            'types' => MarketplaceSupportTicket::types(),
            'priorities' => MarketplaceSupportTicket::priorities(),
            'orders' => $orders,
            'events' => $events,
            'refunds' => $refunds,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'string', 'in:' . implode(',', MarketplaceSupportTicket::types())],
            'priority' => ['nullable', 'string', 'in:' . implode(',', MarketplaceSupportTicket::priorities())],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
            'refund_request_id' => ['nullable', 'integer', 'exists:refund_requests,id'],
        ]);

        if (! empty($data['order_id'])) {
            abort_unless(Order::where('id', $data['order_id'])->where('user_id', $request->user()->id)->exists(), 403);
        }

        if (! empty($data['refund_request_id'])) {
            abort_unless(RefundRequest::where('id', $data['refund_request_id'])->where('user_id', $request->user()->id)->exists(), 403);
        }

        if (! empty($data['order_id'])) {
            $order = Order::find($data['order_id']);
            $data['event_id'] = $data['event_id'] ?? $order?->event_id;
        }

        $ticket = $this->supportService->createTicket([
            ...$data,
            'user_id' => $request->user()->id,
        ], $request->user(), 'web');

        return redirect()->route('user.support-tickets.show', $ticket)->with('success', 'Support ticket created successfully.');
    }

    public function show(Request $request, MarketplaceSupportTicket $supportTicket): View
    {
        abort_unless($this->supportService->userCanView($request->user(), $supportTicket), 404);
        $supportTicket->load(['messages.sender', 'event', 'order', 'refundRequest']);

        return view('user.pages.support-tickets.show', compact('supportTicket'));
    }

    public function reply(Request $request, MarketplaceSupportTicket $supportTicket): RedirectResponse
    {
        abort_unless($this->supportService->userCanView($request->user(), $supportTicket), 404);
        abort_unless($supportTicket->isOpenForReplies(), 422, 'This ticket is already resolved or closed.');

        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $this->supportService->addMessage($supportTicket, $request->user(), 'web', $data['body']);

        if ($supportTicket->status !== MarketplaceSupportTicket::STATUS_OPEN) {
            $supportTicket->forceFill(['status' => MarketplaceSupportTicket::STATUS_PENDING])->save();
        }

        return redirect()->route('user.support-tickets.show', $supportTicket)->with('success', 'Reply added successfully.');
    }
}
