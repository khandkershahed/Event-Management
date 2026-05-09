<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RefundRequestStoreRequest;
use App\Models\Order;
use App\Models\RefundRequest;
use App\Services\RefundService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class RefundRequestController extends Controller
{
    public function index(Request $request): View
    {
        $refunds = RefundRequest::query()->with(['order.event'])->where('user_id', $request->user()->id)->latest()->paginate(10);
        return view('user.pages.refunds.index', compact('refunds'));
    }

    public function create(Request $request, Order $order, RefundService $refundService): View
    {
        abort_unless((int) $order->user_id === (int) $request->user()->id, 404);
        $order->load(['event', 'tickets']);
        $hasOpenRefund = $refundService->hasOpenRequest($order);
        return view('user.pages.refunds.create', compact('order', 'hasOpenRefund'));
    }

    public function store(RefundRequestStoreRequest $request, Order $order, RefundService $refundService): RedirectResponse
    {
        abort_unless((int) $order->user_id === (int) $request->user()->id, 404);

        try {
            $refund = $refundService->createCustomerRequest($order, $request->user(), $request->input('reason'));
            return redirect()->route('user.refunds.index')->with('success', 'Refund request submitted successfully. Reference #' . $refund->id);
        } catch (RuntimeException $exception) {
            return back()->withInput()->withErrors(['reason' => $exception->getMessage()]);
        }
    }
}
