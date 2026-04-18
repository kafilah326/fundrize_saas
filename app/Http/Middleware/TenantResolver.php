<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;
use App\Models\TenantDomain;

class TenantResolver
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $tenant = null;

        // 1. Cek apakah ini superadmin domain — skip
        if ($host === config('tenancy.superadmin_domain')) {
            return $next($request);
        }

        // 2. Cek custom domain (hanya yang sudah terverifikasi CNAME/A recordnya, atau local environment jika disetting skip check)
        $tenantDomain = TenantDomain::where('domain', $host)->first();
        if ($tenantDomain) {
            // Untuk memastikan kita tidak me-resolve custom domain yang belum disetup DNS-nya
            // secara teori kalau belum disetup DNS-nya, traffic tidak akan masuk ke server ini, 
            // TAPI bisa saja ini request Spoofing Host header.
            // Kita harus pastikan domain verified sebelum me-resolve
            if ($tenantDomain->isVerified() || app()->environment('local', 'testing')) {
                $tenant = $tenantDomain->tenant;
            } else {
                abort(404, 'Domain belum terverifikasi.');
            }
        }

        // 3. Cek subdomain
        if (!$tenant) {
            $baseDomain = config('tenancy.base_domain'); // fundrize.test
            // Ensure base domain is not empty
            if (!empty($baseDomain) && str_ends_with($host, '.' . $baseDomain)) {
                $slug = str_replace('.' . $baseDomain, '', $host);
                if ($slug !== 'app' && $slug !== 'superadmin') {
                    $tenant = Tenant::where('slug', $slug)->first();
                }
            }
        }

        // 4. Cek session (untuk app.fundrize.com onboarding atau redirect)
        if (!$tenant && session()->has('current_tenant_id')) {
            $tenant = Tenant::find(session('current_tenant_id'));
        }

        if ($tenant) {
            if ($tenant->status === 'suspended') {
                abort(403, 'Akun tenant ini telah ditangguhkan.');
            }
            app()->instance('current_tenant', $tenant);
            \Illuminate\Support\Facades\View::share('currentTenant', $tenant);
        }

        return $next($request);
    }
}
