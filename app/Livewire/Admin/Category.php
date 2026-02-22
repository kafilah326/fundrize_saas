<?php

namespace App\Livewire\Admin;

use App\Models\Category as CategoryModel;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Category extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    
    // Form properties
    public $categoryId;
    public $name;
    public $slug;
    public $icon;
    public $is_active = true;

    // Modal state
    public $showModal = false;
    public $isEditing = false;
    public $showDeleteModal = false;
    public $categoryToDeleteId;

    protected $rules = [
        'name' => 'required|min:3',
        'slug' => 'required|unique:categories,slug',
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
        $this->reset(['categoryId', 'name', 'slug', 'icon', 'is_active']);
        $this->is_active = true;
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $category = CategoryModel::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->icon = $category->icon;
        $this->is_active = (bool) $category->is_active;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        CategoryModel::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'is_active' => $this->is_active,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Kategori berhasil ditambahkan.');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'slug' => 'required|unique:categories,slug,' . $this->categoryId,
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category = CategoryModel::findOrFail($this->categoryId);
        $category->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'is_active' => $this->is_active,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Kategori berhasil diperbarui.');
    }

    public function confirmDelete($id)
    {
        $this->categoryToDeleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $category = CategoryModel::findOrFail($this->categoryToDeleteId);
        $category->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Kategori berhasil dihapus.');
    }

    public function render()
    {
        $categories = CategoryModel::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.category', [
            'categories' => $categories
        ])->layout('layouts.admin');
    }
}
