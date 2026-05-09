<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRefundDecisionRequest;
use App\Models\RefundRequest;
use App\Services\AuditLogService;
use App\Services\MarketplaceNotificationService;
use App\Services\RefundService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class RefundController extends Controller
{
    public function index(Request $request): View
    {
        $refunds = RefundRequest::query()->with(['order.event', 'user', 'organizerProfile'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()->paginate(15);
        $statuses = RefundRequest::statuses();
        return view('admin.pages.refunds.index', compact('refunds', 'statuses'));
    }

    public function show(RefundRequest $refund): View
    {
        $refund->load(['order.tickets', 'order.items.eventTicket', 'order.latestPaymentTransaction', 'user', 'event', 'organizerProfile', 'transactions']);
        return view('admin.pages.refunds.show', compact('refund'));
    }

    public function approve(AdminRefundDecisionRequest $request, RefundRequest $refund, RefundService $refundService, MarketplaceNotificationService $notificationService, AuditLogService $auditLogService): RedirectResponse
    {
        try {
            $oldValues = $refund->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']);
            $approvedRefund = $refundService->approve($refund, (int) auth('admin')->id(), $request->input('admin_note'));
            $approvedRefund = $approvedRefund->fresh(['user', 'order', 'event', 'organizerProfile.user']);
            $notificationService->notifyRefundDecision($approvedRefund, RefundRequest::STATUS_APPROVED);
            $auditLogService->record(
                'admin.refund.approved',
                $approvedRefund,
                $oldValues,
                $approvedRefund->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']),
                'Admin approved refund request #' . $approvedRefund->id,
                $request->user('admin'),
                'admin',
                $request
            );
            return redirect()->route('admin.refunds.show', $refund)->with('success', 'Refund approved safely.');
        } catch (RuntimeException $exception) {
            return back()->withErrors(['admin_note' => $exception->getMessage()]);
        }
    }

    public function reject(AdminRefundDecisionRequest $request, RefundRequest $refund, RefundService $refundService, MarketplaceNotificationService $notificationService, AuditLogService $auditLogService): RedirectResponse
    {
        try {
            $oldValues = $refund->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']);
            $rejectedRefund = $refundService->reject($refund, (int) auth('admin')->id(), $request->input('admin_note'));
            $rejectedRefund = $rejectedRefund->fresh(['user', 'order', 'event', 'organizerProfile.user']);
            $notificationService->notifyRefundDecision($rejectedRefund, RefundRequest::STATUS_REJECTED);
            $auditLogService->record(
                'admin.refund.rejected',
                $rejectedRefund,
                $oldValues,
                $rejectedRefund->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']),
                'Admin rejected refund request #' . $rejectedRefund->id,
                $request->user('admin'),
                'admin',
                $request
            );
            return redirect()->route('admin.refunds.show', $refund)->with('success', 'Refund rejected.');
        } catch (RuntimeException $exception) {
            return back()->withErrors(['admin_note' => $exception->getMessage()]);
        }
    }
}
