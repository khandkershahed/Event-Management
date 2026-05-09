<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MarketplaceModerationFlag;
use App\Models\OrganizerProfile;
use App\Services\AuditLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ModerationFlagController extends Controller
{
    public function __construct(protected AuditLogService $auditLogService)
    {
    }

    public function index(Request $request): View
    {
        $flags = MarketplaceModerationFlag::query()
            ->with(['flaggable', 'flaggedBy', 'reviewedBy'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('flaggable_type'), fn ($query) => $query->where('flaggable_type', $request->flaggable_type))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.moderation-flags.index', [
            'flags' => $flags,
            'statuses' => MarketplaceModerationFlag::statuses(),
        ]);
    }

    public function flagEvent(Request $request, Event $event): RedirectResponse
    {
        return $this->flag($request, $event, 'admin.moderation.event.flagged', 'Event flagged for moderation.');
    }

    public function flagOrganizer(Request $request, OrganizerProfile $organizer): RedirectResponse
    {
        return $this->flag($request, $organizer, 'admin.moderation.organizer.flagged', 'Organizer flagged for moderation.');
    }

    public function resolve(Request $request, MarketplaceModerationFlag $flag): RedirectResponse
    {
        return $this->review($request, $flag, MarketplaceModerationFlag::STATUS_RESOLVED, 'admin.moderation_flag.resolved', 'Moderation flag resolved.');
    }

    public function unflag(Request $request, MarketplaceModerationFlag $flag): RedirectResponse
    {
        return $this->review($request, $flag, MarketplaceModerationFlag::STATUS_DISMISSED, 'admin.moderation_flag.dismissed', 'Moderation flag dismissed.');
    }

    protected function flag(Request $request, object $flaggable, string $action, string $message): RedirectResponse
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:5000'],
            'admin_note' => ['nullable', 'string', 'max:5000'],
        ]);

        $flag = MarketplaceModerationFlag::create([
            'flaggable_type' => $flaggable::class,
            'flaggable_id' => $flaggable->id,
            'status' => MarketplaceModerationFlag::STATUS_ACTIVE,
            'reason' => $data['reason'],
            'admin_note' => $data['admin_note'] ?? null,
            'flagged_by' => $request->user('admin')->id,
        ]);

        $this->auditLogService->record(
            $action,
            $flag,
            [],
            $flag->only(['flaggable_type', 'flaggable_id', 'status', 'reason', 'admin_note', 'flagged_by']),
            $message,
            $request->user('admin'),
            'admin',
            $request
        );

        return redirect()->route('admin.moderation-flags.index')->with('success', $message);
    }

    protected function review(Request $request, MarketplaceModerationFlag $flag, string $status, string $action, string $message): RedirectResponse
    {
        $data = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:5000'],
        ]);

        $oldValues = $flag->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']);
        $flag->forceFill([
            'status' => $status,
            'admin_note' => $data['admin_note'] ?? $flag->admin_note,
            'reviewed_by' => $request->user('admin')->id,
            'reviewed_at' => now(),
        ])->save();

        $this->auditLogService->record(
            $action,
            $flag,
            $oldValues,
            $flag->only(['status', 'admin_note', 'reviewed_by', 'reviewed_at']),
            $message,
            $request->user('admin'),
            'admin',
            $request
        );

        return redirect()->route('admin.moderation-flags.index')->with('success', $message);
    }
}
