<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.superadmin')]
class TenantList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function suspendTenant($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update(['status' => 'suspended']);
        session()->flash('success', "Tenant {$tenant->name} berhasil ditangguhkan.");
    }

    public function activateTenant($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update(['status' => 'active']);
        session()->flash('success', "Tenant {$tenant->name} berhasil diaktifkan.");
    }

    public function render()
    {
        $query = Tenant::query()->with(['domains', 'plan'])->withCount('users');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return view('livewire.super-admin.tenant-list', [
            'tenants' => $query->latest()->paginate(10)
        ])->title('Manajemen Tenant');
    }
}
