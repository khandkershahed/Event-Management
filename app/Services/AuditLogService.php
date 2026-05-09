<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AuditLogService
{
    public function record(
        string $action,
        ?Model $auditable = null,
        array $oldValues = [],
        array $newValues = [],
        ?string $description = null,
        ?Model $actor = null,
        ?string $actorGuard = null,
        ?Request $request = null
    ): ?AuditLog {
        if (! $this->tableReady()) {
            return null;
        }

        $request = $request ?: request();
        [$resolvedActor, $resolvedGuard] = $this->resolveActor($actor, $actorGuard);

        try {
            return AuditLog::create([
                'actor_type' => $resolvedActor ? $resolvedActor::class : null,
                'actor_id' => $resolvedActor?->getKey(),
                'actor_guard' => $resolvedGuard,
                'action' => $action,
                'auditable_type' => $auditable ? $auditable::class : null,
                'auditable_id' => $auditable?->getKey(),
                'old_values' => $oldValues ?: null,
                'new_values' => $newValues ?: null,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'url' => $request?->fullUrl(),
                'method' => $request?->method(),
                'description' => $description,
            ]);
        } catch (Throwable $exception) {
            Log::warning('Marketplace audit log write failed.', [
                'action' => $action,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    protected function resolveActor(?Model $actor, ?string $guard): array
    {
        if ($actor) {
            return [$actor, $guard ?: $this->guardForActor($actor)];
        }

        if (Auth::guard('admin')->check()) {
            return [Auth::guard('admin')->user(), 'admin'];
        }

        if (Auth::guard('web')->check()) {
            return [Auth::guard('web')->user(), 'web'];
        }

        return [null, $guard];
    }

    protected function guardForActor(Model $actor): ?string
    {
        return match (true) {
            $actor instanceof Admin => 'admin',
            $actor instanceof User => 'web',
            default => null,
        };
    }

    protected function tableReady(): bool
    {
        try {
            return Schema::hasTable('audit_logs');
        } catch (Throwable) {
            return false;
        }
    }
}
