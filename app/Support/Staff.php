<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class Staff
{
    public static function allows(string $ability): bool
    {
        $admin = Auth::guard('admin')->user();
        if (! $admin || (int) ($admin->status ?? 1) !== 1) {
            return false;
        }

        $role = (int) ($admin->role ?? 1);
        if (in_array($role, [1, 2], true) || $ability === 'any') {
            return true;
        }

        $map = [
            3 => ['orders'],
            4 => ['catalogue'],
            5 => ['stock', 'reports'],
            6 => ['support', 'orders'],
            7 => ['marketing'],
            8 => ['reports'],
        ];

        return in_array($ability, $map[$role] ?? [], true);
    }
}
