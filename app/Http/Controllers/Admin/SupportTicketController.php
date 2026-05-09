<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceSupportTicket;
use App\Services\AuditLogService;
use App\Services\MarketplaceNotificationService;
use App\Services\MarketplaceSupportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct(
        protected MarketplaceSupportService $supportService,
        protected MarketplaceNotificationService $notificationService,
        protected AuditLogService $auditLogService
    ) {
    }

    public function index(Request $request): View
    {
        $tickets = MarketplaceSupportTicket::query()
            ->with(['user', 'organizerProfile.user', 'event', 'order', 'latestMessage'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->priority))
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->type))
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', (int) $request->user_id))
            ->when($request->filled('organizer_id'), fn ($query) => $query->where('organizer_profile_id', (int) $request->organizer_id))
            ->when($request->filled('event_id'), fn ($query) => $query->where('event_id', (int) $request->event_id))
            ->when($request->filled('order_id'), fn ($query) => $query->where('order_id', (int) $request->order_id))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.support-tickets.index', [
            'tickets' => $tickets,
            'statuses' => MarketplaceSupportTicket::statuses(),
            'priorities' => MarketplaceSupportTicket::priorities(),
            'types' => MarketplaceSupportTicket::types(),
        ]);
    }

    public function show(MarketplaceSupportTicket $supportTicket): View
    {
        $supportTicket->load(['messages.sender', 'user', 'organizerProfile.user', 'event', 'order', 'refundRequest', 'organizerPayout']);

        return view('admin.pages.support-tickets.show', [
            'supportTicket' => $supportTicket,
            'statuses' => MarketplaceSupportTicket::statuses(),
            'priorities' => MarketplaceSupportTicket::priorities(),
        ]);
    }

    public function reply(Request $request, MarketplaceSupportTicket $supportTicket): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
            'status' => ['required', 'string', 'in:' . implode(',', MarketplaceSupportTicket::statuses())],
            'priority' => ['required', 'string', 'in:' . implode(',', MarketplaceSupportTicket::priorities())],
            'is_internal' => ['nullable', 'boolean'],
        ]);

        $oldValues = $supportTicket->only(['status', 'priority', 'assigned_admin_id', 'resolved_at', 'closed_at']);

        $this->supportService->addMessage($supportTicket, $request->user('admin'), 'admin', $data['body'], (bool) ($data['is_internal'] ?? false));
        $updatedTicket = $this->supportService->updateStatus($supportTicket, $data['status'], $data['priority'], $request->user('admin'));

        $this->auditLogService->record(
            'admin.support_ticket.replied',
            $updatedTicket,
            $oldValues,
            $updatedTicket->only(['status', 'priority', 'assigned_admin_id', 'resolved_at', 'closed_at']),
            'Admin replied to support ticket ' . $updatedTicket->ticket_number,
            $request->user('admin'),
            'admin',
            $request
        );

        if (! ($data['is_internal'] ?? false)) {
            $this->notifyTicketOwner($updatedTicket, 'Support ticket updated', 'Admin replied to support ticket ' . $updatedTicket->ticket_number . '.');
        }

        return redirect()->route('admin.support-tickets.show', $supportTicket)->with('success', 'Support ticket updated successfully.');
    }

    public function updateStatus(Request $request, MarketplaceSupportTicket $supportTicket): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', MarketplaceSupportTicket::statuses())],
            'priority' => ['required', 'string', 'in:' . implode(',', MarketplaceSupportTicket::priorities())],
        ]);

        $oldValues = $supportTicket->only(['status', 'priority', 'assigned_admin_id', 'resolved_at', 'closed_at']);
        $updatedTicket = $this->supportService->updateStatus($supportTicket, $data['status'], $data['priority'], $request->user('admin'));

        $this->auditLogService->record(
            'admin.support_ticket.status_changed',
            $updatedTicket,
            $oldValues,
            $updatedTicket->only(['status', 'priority', 'assigned_admin_id', 'resolved_at', 'closed_at']),
            'Admin changed support ticket status ' . $updatedTicket->ticket_number,
            $request->user('admin'),
            'admin',
            $request
        );

        $this->notifyTicketOwner($updatedTicket, 'Support ticket status changed', 'Support ticket ' . $updatedTicket->ticket_number . ' is now ' . str_replace('_', ' ', $updatedTicket->status) . '.');

        return redirect()->route('admin.support-tickets.show', $supportTicket)->with('success', 'Support ticket status updated successfully.');
    }

    protected function notifyTicketOwner(MarketplaceSupportTicket $ticket, string $title, string $message): void
    {
        $ticket->loadMissing('user', 'organizerProfile.user');

        if ($ticket->user) {
            $this->notificationService->notifySupportTicketUpdate($ticket->user, $ticket, $title, $message, route('user.support-tickets.show', $ticket));
        }

        $organizerOwner = $ticket->organizerProfile?->user;
        if ($organizerOwner) {
            $this->notificationService->notifySupportTicketUpdate($organizerOwner, $ticket, $title, $message, route('organizer.support-tickets.show', $ticket));
        }
    }
}
