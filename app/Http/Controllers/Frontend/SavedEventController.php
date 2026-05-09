<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CustomerSavedEvent;
use App\Models\Event;
use App\Services\CustomerInterestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SavedEventController extends Controller
{
    public function store(Event $event, Request $request, CustomerInterestService $interestService): RedirectResponse
    {
        abort_unless($event->status === Event::STATUS_PUBLISHED, 404);

        CustomerSavedEvent::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'event_id' => $event->id,
        ], [
            'source' => $request->input('source', 'public'),
        ]);

        $interestService->recordFromEvent($request->user(), $event->loadMissing('venueRecord'));

        return back()->with('success', 'Event saved to your wishlist.');
    }

    public function destroy(Event $event, Request $request): RedirectResponse
    {
        CustomerSavedEvent::query()
            ->where('user_id', $request->user()->id)
            ->where('event_id', $event->id)
            ->delete();

        return back()->with('success', 'Event removed from your wishlist.');
    }
}
