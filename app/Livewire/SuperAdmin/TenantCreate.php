<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use App\Models\TenantDomain;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('layouts.superadmin')]
class TenantCreate extends Component
{
    public $name;
    public $slug;
    public $email;
    public $phone;
    public $password;
    public $plan_id;

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug',
            'email' => 'required|email|unique:tenants,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:8',
            'plan_id' => 'required|exists:plans,id',
        ]);

        $service = app(\App\Services\TenantProvisioningService::class);
        $service->provision([
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => $this->password,
            'plan_id' => $this->plan_id,
        ]);

        session()->flash('success', 'Tenant baru berhasil dibuat.');
        return redirect()->route('superadmin.tenants');
    }

    public function render()
    {
        $plans = Plan::active()->orderBy('sort_order')->get();
        return view('livewire.super-admin.tenant-create', compact('plans'))->title('Buat Tenant Baru');
    }
}
