<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Services\PaymentWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function __construct(protected PaymentWebhookService $paymentWebhookService)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->paymentWebhookService->handleStripeRequest($request);

        return response()->json($result);
    }
}
