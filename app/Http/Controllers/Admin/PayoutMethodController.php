<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPayoutMethodDecisionRequest;
use App\Models\OrganizerPayoutMethod;
use App\Services\AuditLogService;
use App\Services\PayoutMethodService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class PayoutMethodController extends Controller
{
    public function __construct(
        protected PayoutMethodService $payoutMethodService,
        protected AuditLogService $auditLogService
    )
    {
    }

    public function index(Request $request): View
    {
        $methods = OrganizerPayoutMethod::query()
            ->with('organizerProfile.user')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('method_type'), fn ($query) => $query->where('method_type', $request->method_type))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.payout-methods.index', [
            'methods' => $methods,
            'statuses' => OrganizerPayoutMethod::statuses(),
            'methodTypes' => OrganizerPayoutMethod::methodTypes(),
        ]);
    }

    public function show(OrganizerPayoutMethod $payoutMethod): View
    {
        $payoutMethod->load(['organizerProfile.user', 'reviewedBy']);

        return view('admin.pages.payout-methods.show', compact('payoutMethod'));
    }

    public function verify(AdminPayoutMethodDecisionRequest $request, OrganizerPayoutMethod $payoutMethod)
    {
        try {
            $oldValues = $payoutMethod->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']);
            $verifiedMethod = $this->payoutMethodService->verify($payoutMethod, auth('admin')->id(), $request->validated()['admin_note'] ?? null);
            $this->auditLogService->record(
                'admin.payout_method.verified',
                $verifiedMethod,
                $oldValues,
                $verifiedMethod->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']),
                'Admin verified payout method #' . $verifiedMethod->id,
                $request->user('admin'),
                'admin',
                $request
            );
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.payout-methods.show', $payoutMethod)->with('success', 'Payout method verified successfully.');
    }

    public function reject(AdminPayoutMethodDecisionRequest $request, OrganizerPayoutMethod $payoutMethod)
    {
        try {
            $oldValues = $payoutMethod->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']);
            $rejectedMethod = $this->payoutMethodService->reject($payoutMethod, auth('admin')->id(), $request->validated()['admin_note'] ?? null);
            $this->auditLogService->record(
                'admin.payout_method.rejected',
                $rejectedMethod,
                $oldValues,
                $rejectedMethod->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']),
                'Admin rejected payout method #' . $rejectedMethod->id,
                $request->user('admin'),
                'admin',
                $request
            );
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.payout-methods.show', $payoutMethod)->with('success', 'Payout method rejected.');
    }
}
