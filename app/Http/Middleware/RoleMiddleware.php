<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response|RedirectResponse
    {
        if (!$user = $request->user()) {
            if ($request->expectsJson()) {
                abort(401);
            }
            return redirect()->route('login');
        }

        $roles = array_map('trim', $roles);
        if ($roles && !in_array($user->role, $roles, true)) {
            abort(403, 'Unauthorized for this role.');
        }

        return $next($request);
    }
}