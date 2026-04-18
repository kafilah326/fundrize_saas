<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Plan;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.superadmin')]
class PlanManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $planId;

    public $name, $slug, $description, $price = 0, $system_fee_percentage = 5.00, $is_active = true, $sort_order = 0;
    
    // Features array mapping
    public $features = [
        'custom_domain' => false,
        'whatsapp' => false,
        'fundraiser' => false,
        'qurban' => false,
        'zakat' => false,
        'dynamic_program' => false,
    ];

    // Limits array
    public $limits = [
        'max_users' => 5,
        'max_programs' => 10,
        'storage_mb' => 500,
    ];

    public function updatedName($value)
    {
        if (!$this->planId) {
            $this->slug = Str::slug($value);
        }
    }

    public function createPlan()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function editPlan($id)
    {
        $plan = Plan::findOrFail($id);
        $this->planId = $id;
        $this->name = $plan->name;
        $this->slug = $plan->slug;
        $this->description = $plan->description;
        $this->price = $plan->price;
        $this->system_fee_percentage = $plan->system_fee_percentage;
        $this->is_active = $plan->is_active;
        $this->sort_order = $plan->sort_order;
        
        $this->features = array_merge([
            'custom_domain' => false,
            'whatsapp' => false,
            'fundraiser' => false,
            'qurban' => false,
            'zakat' => false,
            'dynamic_program' => false,
        ], $plan->features ?? []);

        $this->limits = array_merge([
            'max_users' => 5,
            'max_programs' => 10,
            'storage_mb' => 500,
        ], $plan->limits ?? []);

        $this->isModalOpen = true;
    }

    public function savePlan()
    {
        $this->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:plans,slug,' . $this->planId,
            'price' => 'required|numeric|min:0',
            'system_fee_percentage' => 'required|numeric|min:0|max:100',
            'features.custom_domain' => 'boolean',
            'limits.max_users' => 'numeric|min:1',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'system_fee_percentage' => $this->system_fee_percentage,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'features' => $this->features,
            'limits' => $this->limits,
        ];

        if ($this->planId) {
            Plan::where('id', $this->planId)->update($data);
            session()->flash('success', 'Paket berhasil diperbarui.');
        } else {
            Plan::create($data);
            session()->flash('success', 'Paket baru berhasil ditambahkan.');
        }

        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function deletePlan($id)
    {
        $plan = Plan::findOrFail($id);
        if ($plan->tenants()->count() > 0) {
            session()->flash('error', 'Tidak dapat menghapus paket yang sedang digunakan oleh tenant.');
            return;
        }
        $plan->delete();
        session()->flash('success', 'Paket berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->is_active = !$plan->is_active;
        $plan->save();
    }

    public function resetForm()
    {
        $this->planId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->price = 0;
        $this->system_fee_percentage = 5.00;
        $this->is_active = true;
        $this->sort_order = 0;
        
        $this->features = [
            'custom_domain' => false,
            'whatsapp' => false,
            'fundraiser' => false,
            'qurban' => false,
            'zakat' => false,
            'dynamic_program' => false,
        ];
        
        $this->limits = [
            'max_users' => 5,
            'max_programs' => 10,
            'storage_mb' => 500,
        ];
    }

    public function render()
    {
        $plans = Plan::withCount('tenants')->orderBy('sort_order')->get();
        return view('livewire.super-admin.plan-manager', compact('plans'))->title('Paket & Harga');
    }
}
