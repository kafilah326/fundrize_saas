<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Superadmin routes — diakses via domain khusus (misal: superadmin.fundrize.test)
            // Gunakan request()->getHost() agar bisa diakses di local tanpa konfigurasi DNS khusus
            $superadminDomain = config('tenancy.superadmin_domain', 'superadmin.fundrize.id');
            \Illuminate\Support\Facades\Route::middleware('web')
                ->domain($superadminDomain)
                ->group(base_path('routes/superadmin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\TrackReferral::class,
            \App\Http\Middleware\TenantResolver::class,
        ]);

        $middleware->alias([
            'admin'          => \App\Http\Middleware\AdminMiddleware::class,
            'api.auth'       => \App\Http\Middleware\SimpleApiAuth::class,
            'superadmin'     => \App\Http\Middleware\SuperAdminDomain::class,
            'tenant.required' => \App\Http\Middleware\EnsureTenantResolved::class,
            'check.plan'     => \App\Http\Middleware\CheckPlanFeature::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
            'webhooks/xendit/invoice',
            'webhooks/pakasir/invoice',
            'webhooks/duitku/callback',
        ]);

        // Fix #3: Ketika auth:superadmin gagal, redirect ke login superadmin, bukan 'login' user
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->getHost() === config('tenancy.superadmin_domain')) {
                return route('superadmin.login');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Fix #3: Handle 403 exception gracefully for superadmin domain
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 403 && $request->getHost() === config('tenancy.superadmin_domain')) {
                return redirect()->route('superadmin.login');
            }
        });
    })->create();
