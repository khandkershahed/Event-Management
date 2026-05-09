<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCancellationDecisionRequest;
use App\Models\EventCancellationRequest;
use App\Services\AuditLogService;
use App\Services\EventCancellationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class EventCancellationController extends Controller
{
    public function index(Request $request): View
    {
        $requests = EventCancellationRequest::query()->with(['event', 'organizerProfile', 'requester'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()->paginate(15);
        $statuses = EventCancellationRequest::statuses();
        return view('admin.pages.event-cancellations.index', compact('requests', 'statuses'));
    }

    public function show(EventCancellationRequest $cancellation): View
    {
        $cancellation->load(['event.orders.tickets', 'event.orders.refundTransactions', 'organizerProfile', 'requester']);
        return view('admin.pages.event-cancellations.show', ['cancellationRequest' => $cancellation]);
    }

    public function approve(AdminCancellationDecisionRequest $request, EventCancellationRequest $cancellation, EventCancellationService $service, AuditLogService $auditLogService): RedirectResponse
    {
        try {
            $oldValues = $cancellation->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']);
            $approvedCancellation = $service->approve($cancellation, (int) auth('admin')->id(), $request->input('admin_note'));
            $auditLogService->record(
                'admin.event_cancellation.approved',
                $approvedCancellation,
                $oldValues,
                $approvedCancellation->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']),
                'Admin approved event cancellation request #' . $approvedCancellation->id,
                $request->user('admin'),
                'admin',
                $request
            );

            return redirect()->route('admin.event-cancellations.show', $cancellation)->with('success', 'Event cancellation approved safely.');
        } catch (RuntimeException $exception) {
            return back()->withErrors(['admin_note' => $exception->getMessage()]);
        }
    }

    public function reject(AdminCancellationDecisionRequest $request, EventCancellationRequest $cancellation, EventCancellationService $service, AuditLogService $auditLogService): RedirectResponse
    {
        try {
            $oldValues = $cancellation->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']);
            $rejectedCancellation = $service->reject($cancellation, (int) auth('admin')->id(), $request->input('admin_note'));
            $auditLogService->record(
                'admin.event_cancellation.rejected',
                $rejectedCancellation,
                $oldValues,
                $rejectedCancellation->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']),
                'Admin rejected event cancellation request #' . $rejectedCancellation->id,
                $request->user('admin'),
                'admin',
                $request
            );

            return redirect()->route('admin.event-cancellations.show', $cancellation)->with('success', 'Event cancellation rejected.');
        } catch (RuntimeException $exception) {
            return back()->withErrors(['admin_note' => $exception->getMessage()]);
        }
    }
}
