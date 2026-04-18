<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $superAdminDomain = config('tenancy.superadmin_domain');

        // Check if environment supports a different port or local testing without proper DNS.
        // For production, strict checking:
        if ($host !== $superAdminDomain && env('APP_ENV') === 'production') {
            abort(404);
        }

        return $next($request);
    }
}
