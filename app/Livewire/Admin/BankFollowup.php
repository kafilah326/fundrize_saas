<?php

namespace App\Livewire\Admin;

use App\Models\BankFollowup as BankFollowupModel;
use Livewire\Component;
use Livewire\WithPagination;

class BankFollowup extends Component
{
    use WithPagination;

    public $name;
    public $content;
    public $type = 'donasi';
    public $followup_sequence = 'FollowUp1';
    public $is_active = true;
    public $selected_id;
    public $search = '';

    // Modal States
    public $showModal = false;
    public $isEditing = false;
    public $showDeleteModal = false;
    public $deleteId;

    protected $rules = [
        'name' => 'nullable|string|max:255',
        'content' => 'required|string',
        'type' => 'required|in:donasi,qurban,tabungan_qurban',
        'followup_sequence' => 'required|string|in:FollowUp1,FollowUp2,FollowUp3,FollowUp4',
        'is_active' => 'boolean',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $followups = BankFollowupModel::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%')
                      ->orWhere('type', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.bank-followup', [
            'followups' => $followups
        ])->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInput();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function resetInput()
    {
        $this->name = null;
        $this->content = null;
        $this->type = 'donasi';
        $this->followup_sequence = 'FollowUp1';
        $this->is_active = true;
        $this->selected_id = null;
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        BankFollowupModel::create([
            'name' => $this->name,
            'content' => $this->content,
            'type' => $this->type,
            'followup_sequence' => $this->followup_sequence,
            'is_active' => $this->is_active,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Template Followup berhasil dibuat.');
        $this->resetInput();
    }

    public function edit($id)
    {
        $record = BankFollowupModel::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->content = $record->content;
        $this->type = $record->type;
        $this->followup_sequence = $record->followup_sequence ?? 'FollowUp1';
        $this->is_active = (bool) $record->is_active;
        
        $this->isEditing = true;
        $this->showModal = true;
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate();

        if ($this->selected_id) {
            $record = BankFollowupModel::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'content' => $this->content,
                'type' => $this->type,
                'followup_sequence' => $this->followup_sequence,
                'is_active' => $this->is_active,
            ]);
            
            $this->showModal = false;
            session()->flash('success', 'Template Followup berhasil diperbarui.');
            $this->resetInput();
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->deleteId) {
            BankFollowupModel::where('id', $this->deleteId)->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'Template Followup berhasil dihapus.');
            $this->deleteId = null;
        }
    }

    public function insertParameter($param)
    {
        $this->content = $this->content . ' ' . $param;
    }
}
