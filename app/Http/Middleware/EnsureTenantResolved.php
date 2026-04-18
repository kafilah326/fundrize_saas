<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantResolved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        // Fix #2: Jika tenant middleware ter-trigger oleh superadmin domain,
        // itu adalah akses invalid, jadi kita batalkan dengan 403.
        if ($host === config('tenancy.superadmin_domain')) {
            abort(403, 'Akses tidak valid untuk domain superadmin.');
        }

        if (!app()->bound('current_tenant')) {
            // Jika access via base domain atau app domain — biarkan mengakses landing/onboarding routes
            if ($host === config('tenancy.app_domain') || $host === config('tenancy.base_domain')) {
                // Biarkan request lewat jika host adalah base domain (landing page SaaS)
                return $next($request);
            } else {
                // Subdomain tidak dikenal → Tenant not found
                abort(404, 'Tenant not found');
            }
        }

        return $next($request);
    }
}
