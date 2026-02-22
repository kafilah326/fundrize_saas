<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Program;
use App\Models\ProgramUpdate;
use App\Models\ProgramDistribution;

class ProgramManage extends Component
{
    use WithPagination;

    public $program;
    public $perPage = 10;
    public $activeTab = 'updates'; // updates, distributions

    // Update Form
    public $updateId;
    public $updateTitle;
    public $updateDescription;
    public $updatePublishedAt;
    public $showUpdateModal = false;
    public $confirmingUpdateDeletion = false;

    // Distribution Form
    public $distributionId;
    public $distributionAmount;
    public $distributionDescription;
    public $distributionDate;
    public $showDistributionModal = false;
    public $confirmingDistributionDeletion = false;

    protected $queryString = ['activeTab'];

    public function mount($id)
    {
        $this->program = Program::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.program-manage', [
            'updates' => $this->program->updates()->latest('published_at')->paginate($this->perPage, ['*'], 'updatesPage'),
            'distributions' => $this->program->distributions()->latest('documentation_date')->paginate($this->perPage, ['*'], 'distributionsPage'),
        ])->layout('layouts.admin');
    }

    // ==========================================
    // Updates Management
    // ==========================================

    public function createUpdate()
    {
        $this->resetUpdateForm();
        $this->updatePublishedAt = now()->format('Y-m-d');
        $this->showUpdateModal = true;
    }

    public function editUpdate($id)
    {
        $update = ProgramUpdate::findOrFail($id);
        $this->updateId = $update->id;
        $this->updateTitle = $update->title;
        $this->updateDescription = $update->description;
        $this->updatePublishedAt = $update->published_at->format('Y-m-d');
        $this->showUpdateModal = true;
    }

    public function storeUpdate()
    {
        $this->validate([
            'updateTitle' => 'required|min:3',
            'updateDescription' => 'required',
            'updatePublishedAt' => 'required|date',
        ]);

        ProgramUpdate::updateOrCreate(
            ['id' => $this->updateId],
            [
                'program_id' => $this->program->id,
                'title' => $this->updateTitle,
                'description' => $this->updateDescription,
                'published_at' => $this->updatePublishedAt,
            ]
        );

        $this->showUpdateModal = false;
        $this->resetUpdateForm();
        session()->flash('success', 'Kabar terbaru berhasil disimpan.');
    }

    public function confirmDeleteUpdate($id)
    {
        $this->updateId = $id;
        $this->confirmingUpdateDeletion = true;
    }

    public function deleteUpdate()
    {
        ProgramUpdate::findOrFail($this->updateId)->delete();
        $this->confirmingUpdateDeletion = false;
        $this->resetUpdateForm();
        session()->flash('success', 'Kabar terbaru berhasil dihapus.');
    }

    private function resetUpdateForm()
    {
        $this->updateId = null;
        $this->updateTitle = '';
        $this->updateDescription = '';
        $this->updatePublishedAt = '';
    }

    // ==========================================
    // Distributions Management
    // ==========================================

    public function createDistribution()
    {
        $this->resetDistributionForm();
        $this->distributionDate = now()->format('Y-m-d');
        $this->showDistributionModal = true;
    }

    public function editDistribution($id)
    {
        $dist = ProgramDistribution::findOrFail($id);
        $this->distributionId = $dist->id;
        $this->distributionAmount = $dist->amount_distributed;
        $this->distributionDescription = $dist->description;
        $this->distributionDate = $dist->documentation_date->format('Y-m-d');
        $this->showDistributionModal = true;
    }

    public function storeDistribution()
    {
        $this->validate([
            'distributionAmount' => 'required|numeric|min:1',
            'distributionDescription' => 'required',
            'distributionDate' => 'required|date',
        ]);

        ProgramDistribution::updateOrCreate(
            ['id' => $this->distributionId],
            [
                'program_id' => $this->program->id,
                'amount_distributed' => $this->distributionAmount,
                'description' => $this->distributionDescription,
                'documentation_date' => $this->distributionDate,
            ]
        );

        $this->showDistributionModal = false;
        $this->resetDistributionForm();
        session()->flash('success', 'Penyaluran dana berhasil disimpan.');
    }

    public function confirmDeleteDistribution($id)
    {
        $this->distributionId = $id;
        $this->confirmingDistributionDeletion = true;
    }

    public function deleteDistribution()
    {
        ProgramDistribution::findOrFail($this->distributionId)->delete();
        $this->confirmingDistributionDeletion = false;
        $this->resetDistributionForm();
        session()->flash('success', 'Penyaluran dana berhasil dihapus.');
    }

    private function resetDistributionForm()
    {
        $this->distributionId = null;
        $this->distributionAmount = '';
        $this->distributionDescription = '';
        $this->distributionDate = '';
    }
}
