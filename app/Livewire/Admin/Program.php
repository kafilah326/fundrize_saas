<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Program as ProgramModel;
use App\Models\Category;
use App\Models\AkadType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Program extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $isOpen = false;
    public $confirmingDeletion = false;

    // Form fields
    public $programId;
    public $title;
    public $slug;
    public $description;
    public $target_amount;
    public $end_date;
    public $image;
    public $existingImage;
    public $is_dynamic = false;
    public $package_price;
    public $package_label = 'paket';
    public $is_active = true;
    public $is_featured = false;
    public $is_urgent = false;

    // Relations
    public $selectedCategories = [];
    public $selectedAkadTypes = [];
    public $allCategories = [];
    public $allAkadTypes = [];

    protected $rules = [
        'title' => 'required|min:3',
        'slug' => 'required|unique:programs,slug',
        'description' => 'required',
        'is_dynamic' => 'boolean',
        'package_price' => 'required_if:is_dynamic,true|nullable|numeric|min:0',
        'package_label' => 'required_if:is_dynamic,true|nullable|string|max:50',
        'target_amount' => 'nullable|numeric|min:0',
        'end_date' => 'nullable|date',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB Max
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
        'selectedCategories' => 'array',
        'selectedAkadTypes' => 'array',
    ];

    public function updatedTitle()
    {
        $this->slug = Str::slug($this->title);
    }

    public function updatedSlug($value)
    {
        $this->slug = Str::slug($value);
    }

    public function render()
    {
        $programs = ProgramModel::with(['categories', 'akads'])
            ->where('title', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $tenant = app('current_tenant');
        $canUseDynamic = $tenant && $tenant->plan ? $tenant->plan->hasFeature('dynamic_program') : false;
        $canCreateProgram = $tenant ? $tenant->canCreateMore('programs') : true;
        $programQuota = $tenant ? $tenant->getRemainingQuota('programs') : 99;
        $maxPrograms = $tenant ? $tenant->getLimit('max_programs', 99) : 99;

        return view('livewire.admin.program', [
            'programs' => $programs,
            'canUseDynamic' => $canUseDynamic,
            'canCreateProgram' => $canCreateProgram,
            'programQuota' => $programQuota,
            'maxPrograms' => $maxPrograms,
        ])->layout('layouts.admin');
    }

    public function create()
    {
        $tenant = app('current_tenant');
        if ($tenant && !$tenant->canCreateMore('programs')) {
            session()->flash('error', 'Batas program untuk paket ' . $tenant->getPlanName() . ' telah tercapai (Maks: ' . $tenant->getLimit('max_programs', 3) . '). Silakan upgrade paket Anda.');
            return;
        }

        $this->resetInputFields();
        $this->loadRelationOptions();
        $this->openModal();
    }

    public function loadRelationOptions()
    {
        $this->allCategories = Category::where('is_active', true)->get();
        $this->allAkadTypes = AkadType::where('is_active', true)->get();
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
        $this->programId = null;
        $this->title = '';
        $this->slug = '';
        $this->description = '';
        $this->target_amount = '';
        $this->end_date = '';
        $this->image = null;
        $this->existingImage = null;
        $this->is_dynamic = false;
        $this->package_price = '';
        $this->package_label = 'paket';
        $this->is_active = true;
        $this->is_featured = false;
        $this->is_urgent = false;
        $this->selectedCategories = [];
        $this->selectedAkadTypes = [];
        $this->resetValidation();
    }

    public function store()
    {
        // Adjust rules for update
        $rules = $this->rules;
        if ($this->programId) {
            $rules['slug'] = 'required|unique:programs,slug,' . $this->programId;
        }

        $this->validate($rules);

        $tenant = app('current_tenant');
        $canUseDynamic = $tenant && $tenant->plan ? $tenant->plan->hasFeature('dynamic_program') : false;

        // Enforce max_programs limit on new programs only
        if (!$this->programId && $tenant && !$tenant->canCreateMore('programs')) {
            session()->flash('error', 'Batas program untuk paket ' . $tenant->getPlanName() . ' telah tercapai.');
            $this->closeModal();
            return;
        }

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_dynamic' => $canUseDynamic ? $this->is_dynamic : false,
            'package_price' => ($canUseDynamic && $this->is_dynamic) ? $this->package_price : null,
            'package_label' => ($canUseDynamic && $this->is_dynamic) ? $this->package_label : null,
            'target_amount' => $this->target_amount !== '' ? $this->target_amount : null,
            'end_date' => $this->end_date ?: null,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'is_urgent' => $this->is_urgent,
        ];

        if ($this->image) {
            $manager = new \Intervention\Image\ImageManager(\Intervention\Image\Drivers\Gd\Driver::class);
            $image = $manager->decode($this->image->getRealPath());
            $processed = $image->cover(1200, 630)->encode(new \Intervention\Image\Encoders\JpegEncoder(quality: 85));
            $filename = 'programs/' . uniqid() . '.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, (string) $processed);

            if ($this->programId) {
                $oldProgram = \App\Models\Program::find($this->programId);
                if ($oldProgram) {
                    $oldPath = $oldProgram->getRawOriginal('image');
                    if ($oldPath && !str_starts_with($oldPath, 'http')) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                    }
                }
            }
            $data['image'] = $filename;
        }

        $program = ProgramModel::updateOrCreate(['id' => $this->programId], $data);
        
        // Sync relationships
        $program->categories()->sync($this->selectedCategories);
        $program->akads()->sync($this->selectedAkadTypes);

        session()->flash('success', $this->programId ? 'Program updated successfully.' : 'Program created successfully.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $program = ProgramModel::with(['categories', 'akads'])->findOrFail($id);
        $this->programId = $id;
        $this->title = $program->title;
        $this->slug = $program->slug;
        $this->description = $program->description;
        $this->is_dynamic = (bool) $program->is_dynamic;
        $this->package_price = $program->package_price;
        $this->package_label = $program->package_label ?? 'paket';
        $this->target_amount = $program->target_amount;
        $this->end_date = $program->end_date ? $program->end_date->format('Y-m-d') : null;
        $this->existingImage = $program->image;
        $this->is_active = (bool) $program->is_active;
        $this->is_featured = (bool) $program->is_featured;
        $this->is_urgent = (bool) $program->is_urgent;

        $this->loadRelationOptions();
        $this->selectedCategories = $program->categories->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->selectedAkadTypes = $program->akads->pluck('id')->map(fn($id) => (string) $id)->toArray();

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->programId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->programId) {
            $program = ProgramModel::find($this->programId);
            if ($program) {
                if ($program->image) {
                    Storage::disk('public')->delete($program->image);
                }
                $program->delete();
                session()->flash('success', 'Program deleted successfully.');
            }
        }
        $this->confirmingDeletion = false;
        $this->programId = null;
    }

    public function toggleStatus($id)
    {
        $program = ProgramModel::findOrFail($id);
        $program->is_active = !$program->is_active;
        $program->save();
        session()->flash('success', 'Program status updated.');
    }
}
