<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTrustBadge;
use App\Services\AuditLogService;
use App\Services\MarketplaceNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizerTrustBadgeController extends Controller
{
    public function index(Request $request): View
    {
        $organizers = OrganizerProfile::query()
            ->with(['trustBadges', 'ratingSummary', 'payoutMethod'])
            ->withCount('followers')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%' . $request->search . '%';
                $query->where(function ($inner) use ($search) {
                    $inner->where('organization_name', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('slug', 'like', $search);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $availableBadges = OrganizerTrustBadge::availableBadges();
        $statuses = OrganizerTrustBadge::statuses();

        return view('admin.pages.organizer-trust-badges.index', compact('organizers', 'availableBadges', 'statuses'));
    }

    public function store(OrganizerProfile $organizer, Request $request, AuditLogService $auditLogService, MarketplaceNotificationService $notificationService): RedirectResponse
    {
        $validated = $request->validate([
            'badge_key' => ['required', 'string', 'in:' . implode(',', array_keys(OrganizerTrustBadge::availableBadges()))],
            'label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'string', 'in:' . implode(',', OrganizerTrustBadge::statuses())],
            'is_public' => ['nullable', 'boolean'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $badge = OrganizerTrustBadge::query()->updateOrCreate(
            [
                'organizer_profile_id' => $organizer->id,
                'badge_key' => $validated['badge_key'],
            ],
            [
                'label' => $validated['label'] ?: OrganizerTrustBadge::availableBadges()[$validated['badge_key']],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
                'is_public' => (bool) ($validated['is_public'] ?? false),
                'created_by' => auth('admin')->id(),
                'reviewed_by' => auth('admin')->id(),
                'reviewed_at' => now(),
                'admin_note' => $validated['admin_note'] ?? null,
            ]
        );

        $auditLogService->record('admin.organizer_trust_badge.saved', $badge, [], $badge->toArray(), 'Admin saved organizer trust badge.');
        $notificationService->notifyTrustBadgeUpdated($badge, 'saved');

        return back()->with('success', 'Trust badge saved successfully.');
    }

    public function destroy(OrganizerTrustBadge $badge, AuditLogService $auditLogService, MarketplaceNotificationService $notificationService): RedirectResponse
    {
        $oldValues = $badge->toArray();
        $badge->delete();

        $auditLogService->record('admin.organizer_trust_badge.removed', null, $oldValues, [], 'Admin removed organizer trust badge.');
        $notificationService->notifyTrustBadgeUpdated($badge, 'removed');

        return back()->with('success', 'Trust badge removed successfully.');
    }
}
