<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LoginTracker
{
    public static function locked(Model $account): bool
    {
        if (! Schema::hasColumn($account->getTable(), 'locked_until') || ! $account->locked_until) {
            return false;
        }

        return $account->locked_until->isFuture();
    }

    public static function failed(Model $account): void
    {
        if (! Schema::hasColumn($account->getTable(), 'failed_login_attempts')) {
            return;
        }
        $attempts = (int) $account->failed_login_attempts + 1;
        $account->forceFill([
            'failed_login_attempts' => $attempts,
            'locked_until' => $attempts >= 5 ? now()->addMinutes(15) : null,
        ])->save();
        AuditLog::write('login.failed', $account->getTable(), (int) $account->getKey());
    }

    public static function succeeded(Model $account, Request $request): void
    {
        $values = [];
        if (Schema::hasColumn($account->getTable(), 'failed_login_attempts')) {
            $values['failed_login_attempts'] = 0;
            $values['locked_until'] = null;
        }
        if (Schema::hasColumn($account->getTable(), 'last_login_at')) {
            $values['last_login_at'] = now();
            $values['last_login_ip'] = $request->ip();
        }
        if ($values !== []) {
            $account->forceFill($values)->save();
        }
        AuditLog::write('login.succeeded', $account->getTable(), (int) $account->getKey());
    }
}
