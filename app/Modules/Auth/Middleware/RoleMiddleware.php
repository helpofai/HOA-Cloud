<?php

namespace App\Modules\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Core\Enums\UserRole;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role;
        $requiredRole = UserRole::from($role);

        if ($request->user()->role->level() < $requiredRole->level()) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
