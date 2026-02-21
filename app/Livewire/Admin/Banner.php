<?php

namespace App\Livewire\Admin;

use App\Models\Banner as BannerModel;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Banner extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $isOpen = false;

    public $confirmingDeletion = false;

    // Form fields
    public $bannerId;

    public $title;

    public $image;

    public $existingImage;

    public $link_url;

    public $cta_text;

    public $start_date;

    public $end_date;

    public $priority = 0;

    public $is_active = true;

    public $description;

    public $placement = 'home';

    protected $rules = [
        'title' => 'required|min:3',
        'image' => 'nullable|image|max:2048', // 2MB Max
        'link_url' => 'nullable|url',
        'cta_text' => 'nullable|string|max:255',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'priority' => 'integer|min:0',
        'is_active' => 'boolean',
        'description' => 'nullable|string',
        'placement' => 'required|in:home,qurban,qurban_tabungan',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = BannerModel::query();

        if (! empty($this->search)) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        $banners = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.banner', [
            'banners' => $banners,
        ])->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->bannerId = null;
        $this->title = '';
        $this->image = null;
        $this->existingImage = null;
        $this->link_url = '';
        $this->cta_text = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->priority = 0;
        $this->is_active = true;
        $this->description = '';
        $this->placement = 'home';
        $this->resetValidation();
    }

    public function store()
    {
        $rules = $this->rules;

        // Image required only on create
        if (! $this->bannerId) {
            $rules['image'] = 'required|image|max:2048';
        }

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'link_url' => $this->link_url,
            'cta_text' => $this->cta_text,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'priority' => $this->priority,
            'is_active' => $this->is_active,
            'description' => $this->description,
            'placement' => $this->placement,
        ];

        if ($this->image) {
            // Delete old image if updating
            if ($this->bannerId && $this->existingImage) {
                // Check if it's not a URL
                if (! str_starts_with($this->existingImage, 'http')) {
                    Storage::disk('public')->delete($this->existingImage);
                }
            }

            $imageName = $this->image->store('banners', 'public');
            $data['image'] = $imageName;
        } elseif (! $this->bannerId) {
            // If creating new and no image (should be caught by validation, but just in case)
            // Use a placeholder or fail gracefully
            $data['image'] = 'banners/default.jpg';
        }

        try {
            if ($this->bannerId) {
                BannerModel::where('id', $this->bannerId)->update($data);
                session()->flash('success', 'Banner updated successfully.');
            } else {
                BannerModel::create($data);
                session()->flash('success', 'Banner created successfully.');
            }
            $this->closeModal(); // Close modal only on success
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating banner: '.$e->getMessage());
            \Illuminate\Support\Facades\Log::error('Banner create error: '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        $banner = BannerModel::findOrFail($id);
        $this->bannerId = $id;
        $this->title = $banner->title;
        $this->existingImage = $banner->getRawOriginal('image');
        $this->link_url = $banner->link_url;
        $this->cta_text = $banner->cta_text;
        $this->start_date = $banner->start_date ? $banner->start_date->format('Y-m-d') : null;
        $this->end_date = $banner->end_date ? $banner->end_date->format('Y-m-d') : null;
        $this->priority = $banner->priority;
        $this->is_active = $banner->is_active;
        $this->description = $banner->description;
        $this->placement = $banner->placement;

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->bannerId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->bannerId) {
            $banner = BannerModel::find($this->bannerId);
            if ($banner) {
                $rawImage = $banner->getRawOriginal('image');
                if ($rawImage) {
                    Storage::disk('public')->delete($rawImage);
                }
                $banner->delete();
                session()->flash('success', 'Banner deleted successfully.');
            }
        }
        $this->confirmingDeletion = false;
        $this->bannerId = null;
    }

    public function toggleStatus($id)
    {
        $banner = BannerModel::findOrFail($id);
        $banner->is_active = ! $banner->is_active;
        $banner->save();
        session()->flash('success', 'Banner status updated.');
    }
}
