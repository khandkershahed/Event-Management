<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OrganizerFollower;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FollowedOrganizerController extends Controller
{
    public function index(Request $request): View
    {
        $followedOrganizers = OrganizerFollower::query()
            ->with(['organizerProfile.ratingSummary', 'organizerProfile.publicTrustBadges'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(12);

        return view('user.pages.followed-organizers.index', compact('followedOrganizers'));
    }
}
