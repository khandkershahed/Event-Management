<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\EventManagement\EventControlPanelService;
use Illuminate\View\View;

class EventControlPanelController extends Controller
{
    public function show(Event $event, EventControlPanelService $service): View
    {
        $panel = $service->build($event, 'admin');

        return view('admin.pages.event.control', compact('panel', 'event'));
    }
}
