<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizerApprovalController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.organizers.index', [
            'organizers' => OrganizerProfile::with(['user', 'approvedBy'])->latest()->paginate(15),
        ]);
    }

    public function pending(): View
    {
        return view('admin.pages.organizers.index', [
            'organizers' => OrganizerProfile::with(['user', 'approvedBy'])->where('status', OrganizerProfile::STATUS_PENDING)->latest()->paginate(15),
        ]);
    }

    public function show(OrganizerProfile $organizer): View
    {
        return view('admin.pages.organizers.show', compact('organizer'));
    }

    public function approve(Request $request, OrganizerProfile $organizer): RedirectResponse
    {
        $organizer->update([
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => auth('admin')->id(),
            'rejection_reason' => null,
        ]);

        return redirect()->route('admin.organizers.show', $organizer)->with('success', 'Organizer approved successfully.');
    }

    public function reject(Request $request, OrganizerProfile $organizer): RedirectResponse
    {
        $request->validate(['rejection_reason' => ['required', 'string', 'max:2000']]);

        $organizer->update([
            'status' => OrganizerProfile::STATUS_REJECTED,
            'approved_at' => null,
            'approved_by' => null,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('admin.organizers.show', $organizer)->with('success', 'Organizer rejected successfully.');
    }

    public function suspend(OrganizerProfile $organizer): RedirectResponse
    {
        $organizer->update(['status' => OrganizerProfile::STATUS_SUSPENDED]);

        return redirect()->route('admin.organizers.show', $organizer)->with('success', 'Organizer suspended successfully.');
    }
}
