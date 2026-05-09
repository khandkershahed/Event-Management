<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::query()
            ->with('actor')
            ->latest('id');

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->string('action')->trim() . '%');
        }

        if ($request->filled('actor_guard')) {
            $query->where('actor_guard', $request->string('actor_guard')->trim());
        }

        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', 'like', '%' . $request->string('auditable_type')->trim() . '%');
        }

        $this->applyDateFilter($query, $request, 'date_from', '>=');
        $this->applyDateFilter($query, $request, 'date_to', '<=');

        return view('admin.pages.audit-logs.index', [
            'logs' => $query->paginate(20)->withQueryString(),
            'guards' => AuditLog::query()->whereNotNull('actor_guard')->distinct()->orderBy('actor_guard')->pluck('actor_guard'),
        ]);
    }

    protected function applyDateFilter($query, Request $request, string $field, string $operator): void
    {
        if (! $request->filled($field)) {
            return;
        }

        try {
            $date = Carbon::parse($request->input($field));
            $operator === '<='
                ? $query->where('created_at', '<=', $date->endOfDay())
                : $query->where('created_at', '>=', $date->startOfDay());
        } catch (\Throwable) {
            // Ignore invalid dates safely.
        }
    }
}
