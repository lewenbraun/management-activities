<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EnsureUserHasAdminAccess
{
    /**
     * @param  Closure(Request):Response  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        $allowedRoles = [
            'super_admin',
            'full_management',
            'limited_management',
            'read_only',
        ];

        if (! $user || ! $user->hasAnyRole($allowedRoles)) {
            auth()->logout();
            throw new HttpException(403, 'You do not have permission to access this panel.');
        }

        return $next($request);
    }
}
