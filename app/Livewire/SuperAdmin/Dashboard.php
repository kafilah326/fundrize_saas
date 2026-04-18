<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use App\Models\TenantDomain;
use App\Models\Plan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.superadmin')]
class Dashboard extends Component
{
    public function render()
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', 'active')->count();
        $trialTenants = Tenant::where('status', 'trial')->count();
        $suspendedTenants = Tenant::where('status', 'suspended')->count();
        $totalDomains = TenantDomain::count();
        
        // Plan Distribution
        $planDistribution = Plan::withCount('tenants')->get();

        // Recent Tenants
        $recentTenants = Tenant::with(['domains', 'plan'])->latest()->take(5)->get();

        return view('livewire.super-admin.dashboard', [
            'totalTenants' => $totalTenants,
            'activeTenants' => $activeTenants,
            'trialTenants' => $trialTenants,
            'suspendedTenants' => $suspendedTenants,
            'totalDomains' => $totalDomains,
            'planDistribution' => $planDistribution,
            'recentTenants' => $recentTenants,
        ])->title('Dashboard Overview');
    }
}
