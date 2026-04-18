<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanFeature
{
    /**
     * Handle an incoming request.
     * Usage: middleware('check.plan:qurban')
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $tenant = app('current_tenant');

        if (!$tenant || !$tenant->plan) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Paket langganan tidak ditemukan.');
        }

        if (!$tenant->hasFeature($feature)) {
            $featureLabels = [
                'qurban' => 'Qurban',
                'whatsapp' => 'WhatsApp',
                'fundraiser' => 'Fundraiser',
                'custom_domain' => 'Custom Domain',
                'dynamic_program' => 'Program Dinamis',
            ];

            $label = $featureLabels[$feature] ?? $feature;

            return redirect()->route('admin.dashboard')
                ->with('error', "Fitur \"{$label}\" tidak tersedia di paket {$tenant->plan->name} Anda. Silakan upgrade paket untuk mengakses fitur ini.");
        }

        return $next($request);
    }
}
