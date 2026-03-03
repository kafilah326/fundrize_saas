<?php

namespace App\Livewire\Admin;

use App\Models\LegalDocument as LegalDocumentModel;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class LegalDocument extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $isOpen = false;
    public $confirmingDeletion = false;

    // Form fields
    public $documentId;
    public $title;
    public $document_number;
    public $issuing_authority;
    public $status = 'Terverifikasi';
    public $expiry_date;
    public $file;
    public $existingFile;
    public $sort_order = 0;

    protected $rules = [
        'title' => 'required|min:3',
        'document_number' => 'required',
        'issuing_authority' => 'nullable|string|max:255',
        'status' => 'required|string|max:255',
        'expiry_date' => 'nullable|date',
        'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'sort_order' => 'integer|min:0',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = LegalDocumentModel::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('document_number', 'like', '%' . $this->search . '%');
            });
        }

        $documents = $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.legal-document', [
            'documents' => $documents,
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
        $this->documentId = null;
        $this->title = '';
        $this->document_number = '';
        $this->issuing_authority = '';
        $this->status = 'Terverifikasi';
        $this->expiry_date = '';
        $this->file = null;
        $this->existingFile = null;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'document_number' => $this->document_number,
            'issuing_authority' => $this->issuing_authority ?: null,
            'status' => $this->status,
            'expiry_date' => $this->expiry_date ?: null,
            'sort_order' => $this->sort_order,
        ];

        if ($this->file) {
            // Delete old file if updating
            if ($this->documentId && $this->existingFile) {
                if (!str_starts_with($this->existingFile, 'http')) {
                    Storage::disk('public')->delete($this->existingFile);
                }
            }

            $data['file_url'] = $this->file->store('legal-documents', 'public');
        }

        try {
            if ($this->documentId) {
                LegalDocumentModel::where('id', $this->documentId)->update($data);
                session()->flash('success', 'Dokumen legalitas berhasil diperbarui.');
            } else {
                LegalDocumentModel::create($data);
                session()->flash('success', 'Dokumen legalitas berhasil ditambahkan.');
            }
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan dokumen: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('LegalDocument save error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $doc = LegalDocumentModel::findOrFail($id);
        $this->documentId = $id;
        $this->title = $doc->title;
        $this->document_number = $doc->document_number;
        $this->issuing_authority = $doc->issuing_authority;
        $this->status = $doc->status;
        $this->expiry_date = $doc->expiry_date ? $doc->expiry_date->format('Y-m-d') : null;
        $this->existingFile = $doc->getRawOriginal('file_url');
        $this->sort_order = $doc->sort_order;

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->documentId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->documentId) {
            $doc = LegalDocumentModel::find($this->documentId);
            if ($doc) {
                $rawFile = $doc->getRawOriginal('file_url');
                if ($rawFile && !str_starts_with($rawFile, 'http')) {
                    Storage::disk('public')->delete($rawFile);
                }
                $doc->delete();
                session()->flash('success', 'Dokumen legalitas berhasil dihapus.');
            }
        }
        $this->confirmingDeletion = false;
        $this->documentId = null;
    }
}
