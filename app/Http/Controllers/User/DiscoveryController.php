<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CustomerSavedEvent;
use App\Services\PersonalizedDiscoveryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscoveryController extends Controller
{
    public function index(Request $request, PersonalizedDiscoveryService $discoveryService): View
    {
        $recommendedEvents = $discoveryService->recommendedEventsFor($request->user(), 12);
        $savedEventIds = CustomerSavedEvent::query()
            ->where('user_id', $request->user()->id)
            ->pluck('event_id')
            ->all();

        return view('user.pages.discovery.index', compact('recommendedEvents', 'savedEventIds'));
    }
}
