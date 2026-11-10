<?php

namespace App\Http\Middleware;

use App\Support\Staff;
use Closure;
use Illuminate\Http\Request;

class EnsureStaff
{
    public function handle(Request $request, Closure $next, string $ability = 'any')
    {
        if (! Staff::allows($ability)) {
            abort(403);
        }

        return $next($request);
    }
}
