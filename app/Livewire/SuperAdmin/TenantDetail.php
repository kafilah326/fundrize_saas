<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use App\Models\Plan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.superadmin')]
class TenantDetail extends Component
{
    public Tenant $tenant;
    public $isPlanModalOpen = false;
    public $selectedPlanId;

    public function mount($id)
    {
        $this->tenant = Tenant::with(['domains', 'users', 'plan'])->findOrFail($id);
        $this->selectedPlanId = $this->tenant->plan_id;
    }

    public function suspendTenant()
    {
        $this->tenant->update(['status' => 'suspended']);
        session()->flash('success', "Tenant berhasil ditangguhkan.");
        $this->tenant->refresh();
    }

    public function activateTenant()
    {
        $this->tenant->update(['status' => 'active']);
        session()->flash('success', "Tenant berhasil diaktifkan.");
        $this->tenant->refresh();
    }

    public function openPlanModal()
    {
        $this->selectedPlanId = $this->tenant->plan_id;
        $this->isPlanModalOpen = true;
    }

    public function updatePlan()
    {
        $this->tenant->update(['plan_id' => $this->selectedPlanId]);
        session()->flash('success', "Paket berhasil diubah.");
        $this->tenant->refresh();
        $this->isPlanModalOpen = false;
    }

    public function render()
    {
        $plans = Plan::active()->orderBy('sort_order')->get();
        return view('livewire.super-admin.tenant-detail', compact('plans'))->title('Detail Tenant: ' . $this->tenant->name);
    }
}
