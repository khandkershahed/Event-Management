<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\EventManagement\EventControlPanelService;
use App\Services\OrganizerTeamAccessService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventControlPanelController extends Controller
{
    public function show(Request $request, Event $event, EventControlPanelService $service, OrganizerTeamAccessService $access): View
    {
        $profile = $request->attributes->get('organizer_profile') ?: $access->resolveProfileFor($request->user());

        abort_unless($profile && (int) $event->organizer_profile_id === (int) $profile->id, 403);
        abort_unless($access->userCan($request->user(), 'operations.manage', $profile), 403);

        $panel = $service->build($event, 'organizer');

        return view('organizer.events.control', compact('panel', 'event'));
    }
}
