<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        abort_unless($user && $user->is_active && $user->canAccessAdmin($permission), 403, 'لا تملك صلاحية الوصول لهذه الصفحة.');

        return $next($request);
    }
}
