<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Addon;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.superadmin')]
class AddonManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $addonId;

    public $name, $slug, $description, $price = 0, $type = 'limit', $target, $value = 0, $duration = 'one_time', $is_active = true, $sort_order = 0;

    // Available targets for limits and features
    public $limitTargets = [
        'max_users' => 'Maksimal User/Tim',
        'max_programs' => 'Maksimal Program Donasi',
        'storage_mb' => 'Kapasitas Penyimpanan (MB)',
    ];

    public $featureTargets = [
        'whatsapp' => 'WhatsApp Notification',
        'fundraiser' => 'Manajemen Fundraiser',
        'qurban' => 'Modul Qurban',
        'zakat' => 'Modul Zakat',
        'custom_domain' => 'Custom Domain Support',
    ];

    public function updatedName($value)
    {
        if (!$this->addonId) {
            $this->slug = Str::slug($value);
        }
    }

    public function createAddon()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function editAddon($id)
    {
        $addon = Addon::findOrFail($id);
        $this->addonId = $id;
        $this->name = $addon->name;
        $this->slug = $addon->slug;
        $this->description = $addon->description;
        $this->price = $addon->price;
        $this->type = $addon->type;
        $this->target = $addon->target;
        $this->value = $addon->value;
        $this->duration = $addon->duration;
        $this->is_active = $addon->is_active;
        $this->sort_order = $addon->sort_order;

        $this->isModalOpen = true;
    }

    public function saveAddon()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:addons,slug,' . $this->addonId,
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:feature,limit',
            'target' => 'required|string',
            'value' => 'required_if:type,limit|numeric|min:0',
            'duration' => 'required|in:one_time,monthly',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'type' => $this->type,
            'target' => $this->target,
            'value' => $this->type === 'limit' ? $this->value : 0,
            'duration' => $this->duration,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->addonId) {
            Addon::where('id', $this->addonId)->update($data);
            session()->flash('success', 'Add-on berhasil diperbarui.');
        } else {
            Addon::create($data);
            session()->flash('success', 'Add-on baru berhasil ditambahkan.');
        }

        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function deleteAddon($id)
    {
        $addon = Addon::findOrFail($id);
        // Check if anyone has purchased this? Optional: soft delete or prevent delete if in use.
        $addon->delete();
        session()->flash('success', 'Add-on berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $addon = Addon::findOrFail($id);
        $addon->is_active = !$addon->is_active;
        $addon->save();
    }

    public function resetForm()
    {
        $this->addonId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->price = 0;
        $this->type = 'limit';
        $this->target = '';
        $this->value = 0;
        $this->duration = 'one_time';
        $this->is_active = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        $addons = Addon::orderBy('sort_order')->paginate(10);
        return view('livewire.super-admin.addon-manager', compact('addons'))->title('Manajemen Add-on');
    }
}
