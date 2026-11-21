<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AuditLog
{
    public static function write(string $action, ?string $subjectType = null, ?int $subjectId = null, array $context = []): void
    {
        if (! Schema::hasTable('audit_logs')) {
            return;
        }

        $actorType = null;
        $actorId = null;
        if (auth()->check()) {
            $actorType = 'user';
            $actorId = (int) auth()->id();
        } elseif (auth('admin')->check()) {
            $actorType = 'admin';
            $actorId = (int) auth('admin')->id();
        }

        DB::table('audit_logs')->insert([
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'ip_address' => request()?->ip(),
            'user_agent' => substr((string) request()?->userAgent(), 0, 2000),
            'context' => $context === [] ? null : json_encode($context),
            'created_at' => now(),
        ]);
    }
}
