<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizerPayout;
use App\Services\AuditLogService;
use App\Services\MarketplaceNotificationService;
use App\Services\OrganizerLedgerService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class PayoutController extends Controller
{
    public function __construct(
        protected OrganizerLedgerService $organizerLedgerService,
        protected MarketplaceNotificationService $notificationService,
        protected AuditLogService $auditLogService
    ) {
    }

    public function index(Request $request): View
    {
        $payouts = OrganizerPayout::query()
            ->with('organizerProfile.user')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.payouts.index', [
            'payouts' => $payouts,
            'statuses' => OrganizerPayout::statuses(),
        ]);
    }

    public function show(OrganizerPayout $payout): View
    {
        $payout->load(['organizerProfile.user', 'ledgers']);

        return view('admin.pages.payouts.show', compact('payout'));
    }

    public function approve(OrganizerPayout $payout)
    {
        try {
            $oldValues = $payout->only(['status', 'reviewed_by', 'approved_at', 'paid_by', 'paid_at', 'rejection_reason']);
            $approvedPayout = $this->organizerLedgerService->approvePayout($payout, auth('admin')->id());
            $approvedPayout = $approvedPayout->fresh(['organizerProfile.user']);
            $this->notificationService->notifyPayoutDecision($approvedPayout, OrganizerPayout::STATUS_APPROVED);
            $this->auditLogService->record(
                'admin.payout.approved',
                $approvedPayout,
                $oldValues,
                $approvedPayout->only(['status', 'reviewed_by', 'approved_at', 'paid_by', 'paid_at', 'rejection_reason']),
                'Admin approved payout ' . $approvedPayout->payout_number,
                request()->user('admin'),
                'admin',
                request()
            );
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.payouts.show', $payout)->with('success', 'Payout approved.');
    }

    public function reject(Request $request, OrganizerPayout $payout)
    {
        $data = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $oldValues = $payout->only(['status', 'reviewed_by', 'approved_at', 'paid_by', 'paid_at', 'rejection_reason']);
            $rejectedPayout = $this->organizerLedgerService->rejectPayout($payout, auth('admin')->id(), $data['rejection_reason'] ?? null);
            $rejectedPayout = $rejectedPayout->fresh(['organizerProfile.user']);
            $this->notificationService->notifyPayoutDecision($rejectedPayout, OrganizerPayout::STATUS_REJECTED);
            $this->auditLogService->record(
                'admin.payout.rejected',
                $rejectedPayout,
                $oldValues,
                $rejectedPayout->only(['status', 'reviewed_by', 'approved_at', 'paid_by', 'paid_at', 'rejection_reason']),
                'Admin rejected payout ' . $rejectedPayout->payout_number,
                $request->user('admin'),
                'admin',
                $request
            );
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.payouts.show', $payout)->with('success', 'Payout rejected.');
    }

    public function markPaid(OrganizerPayout $payout)
    {
        try {
            $oldValues = $payout->only(['status', 'reviewed_by', 'approved_at', 'paid_by', 'paid_at', 'rejection_reason']);
            $paidPayout = $this->organizerLedgerService->markPayoutPaid($payout, auth('admin')->id());
            $paidPayout = $paidPayout->fresh(['organizerProfile.user']);
            $this->notificationService->notifyPayoutDecision($paidPayout, OrganizerPayout::STATUS_PAID);
            $this->auditLogService->record(
                'admin.payout.paid',
                $paidPayout,
                $oldValues,
                $paidPayout->only(['status', 'reviewed_by', 'approved_at', 'paid_by', 'paid_at', 'rejection_reason']),
                'Admin marked payout paid ' . $paidPayout->payout_number,
                request()->user('admin'),
                'admin',
                request()
            );
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.payouts.show', $payout)->with('success', 'Payout marked as paid.');
    }
}
