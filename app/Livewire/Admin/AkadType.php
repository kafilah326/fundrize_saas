<?php

namespace App\Livewire\Admin;

use App\Models\AkadType as AkadTypeModel;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AkadType extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    
    // Form properties
    public $akadTypeId;
    public $name;
    public $slug;
    public $icon;
    public $is_active = true;

    // Modal state
    public $showModal = false;
    public $isEditing = false;
    public $showDeleteModal = false;
    public $akadTypeToDeleteId;

    protected $rules = [
        'name' => 'required|min:3',
        'slug' => 'required|unique:akad_types,slug',
        'icon' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedName()
    {
        if (!$this->isEditing) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function create()
    {
        $this->reset(['akadTypeId', 'name', 'slug', 'icon', 'is_active']);
        $this->is_active = true;
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $akadType = AkadTypeModel::findOrFail($id);
        $this->akadTypeId = $akadType->id;
        $this->name = $akadType->name;
        $this->slug = $akadType->slug;
        $this->icon = $akadType->icon;
        $this->is_active = (bool) $akadType->is_active;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        AkadTypeModel::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'is_active' => $this->is_active,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Tipe Akad berhasil ditambahkan.');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'slug' => 'required|unique:akad_types,slug,' . $this->akadTypeId,
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $akadType = AkadTypeModel::findOrFail($this->akadTypeId);
        $akadType->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'is_active' => $this->is_active,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Tipe Akad berhasil diperbarui.');
    }

    public function confirmDelete($id)
    {
        $this->akadTypeToDeleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $akadType = AkadTypeModel::findOrFail($this->akadTypeToDeleteId);
        $akadType->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Tipe Akad berhasil dihapus.');
    }

    public function render()
    {
        $akadTypes = AkadTypeModel::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.akad-type', [
            'akadTypes' => $akadTypes
        ])->layout('layouts.admin');
    }
}
