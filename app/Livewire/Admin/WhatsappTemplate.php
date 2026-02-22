<?php

namespace App\Livewire\Admin;

use App\Models\WhatsappTemplate as WhatsappTemplateModel;
use Livewire\Component;
use Livewire\WithPagination;

class WhatsappTemplate extends Component
{
    use WithPagination;

    public $perPage = 10;

    // Form fields
    public $name;
    public $content;
    public $type = 'donasi';
    public $event = 'payment_created';
    public $is_active = true;
    public $selected_id;
    public $search = '';
    public $filterType = '';
    public $filterEvent = '';

    // Modal States
    public $showModal = false;
    public $isEditing = false;
    public $showDeleteModal = false;
    public $deleteId;
    public $showPreviewModal = false;
    public $previewContent = '';

    protected $rules = [
        'name' => 'nullable|string|max:255',
        'content' => 'required|string',
        'type' => 'required|in:donasi,qurban,tabungan_qurban',
        'event' => 'required|in:payment_created',
        'is_active' => 'boolean',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterEvent()
    {
        $this->resetPage();
    }

    public function render()
    {
        $templates = WhatsappTemplateModel::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->when($this->filterEvent, function ($query) {
                $query->where('event', $this->filterEvent);
            })
            ->latest()
            ->paginate($this->perPage);

        // Count active templates per type+event for info badges
        $templateCounts = WhatsappTemplateModel::where('is_active', true)
            ->selectRaw("type, event, count(*) as total")
            ->groupBy('type', 'event')
            ->get()
            ->groupBy('type')
            ->map(function ($items) {
                return $items->pluck('total', 'event');
            });

        return view('livewire.admin.whatsapp-template', [
            'templates' => $templates,
            'templateCounts' => $templateCounts,
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
        $this->event = 'payment_created';
        $this->is_active = true;
        $this->selected_id = null;
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        WhatsappTemplateModel::create([
            'name' => $this->name,
            'content' => $this->content,
            'type' => $this->type,
            'event' => $this->event,
            'is_active' => $this->is_active,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Template pesan WhatsApp berhasil dibuat.');
        $this->resetInput();
    }

    public function edit($id)
    {
        $record = WhatsappTemplateModel::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->content = $record->content;
        $this->type = $record->type;
        $this->event = $record->event;
        $this->is_active = (bool) $record->is_active;

        $this->isEditing = true;
        $this->showModal = true;
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate();

        if ($this->selected_id) {
            $record = WhatsappTemplateModel::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'content' => $this->content,
                'type' => $this->type,
                'event' => $this->event,
                'is_active' => $this->is_active,
            ]);

            $this->showModal = false;
            session()->flash('success', 'Template pesan WhatsApp berhasil diperbarui.');
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
            WhatsappTemplateModel::where('id', $this->deleteId)->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'Template pesan WhatsApp berhasil dihapus.');
            $this->deleteId = null;
        }
    }

    public function toggleActive($id)
    {
        $record = WhatsappTemplateModel::findOrFail($id);
        $record->update(['is_active' => !$record->is_active]);
        session()->flash('success', 'Status template berhasil diubah.');
    }

    public function insertParameter($key)
    {
        $this->content = ($this->content ?? '') . '{{' . $key . '}}';
    }

    public function preview($id)
    {
        $record = WhatsappTemplateModel::findOrFail($id);

        // Replace with sample data
        $sampleData = $this->getSampleData($record->type, $record->event);
        $preview = $record->content;
        foreach ($sampleData as $key => $value) {
            $preview = str_replace('{{' . $key . '}}', $value, $preview);
        }

        $this->previewContent = $preview;
        $this->showPreviewModal = true;
    }

    private function getSampleData(string $type, string $event): array
    {
        $common = [
            'nama' => 'Ahmad Fauzi',
            'no_transaksi' => 'TRX-20260219120000-1234',
            'jumlah' => 'Rp 500.000',
            'total' => 'Rp 500.500',
            'yayasan' => 'Yayasan Peduli',
            'link_pembayaran' => 'https://domain.com/payment/status?id=TRX-20260219120000-1234',
        ];

        $eventData = [];
        if ($event === 'payment_created') {
            $eventData = [
                'batas_waktu' => '20 Feb 2026 23:59',
                'metode_bayar' => 'Bank Transfer (BSI)',
                'info_bank' => "Bank: BSI\nNo. Rek: 1234567890\nA.N: Yayasan Peduli",
                'kode_unik' => '500',
            ];
        }

        $typeData = [];
        if ($type === 'donasi') {
            $typeData = [
                'program' => 'Bantu Pendidikan Anak Yatim',
                'tipe_transaksi' => 'Donasi Program',
            ];
        } elseif ($type === 'qurban') {
            $typeData = [
                'nama_qurban' => 'Ahmad Fauzi',
                'jenis_hewan' => 'Sapi (1/7 bagian)',
                'tipe_transaksi' => 'Qurban Langsung',
            ];
        } elseif ($type === 'tabungan_qurban') {
            $typeData = [
                'nama_tabungan' => 'Ahmad Fauzi',
                'target_tabungan' => 'Rp 3.500.000',
                'saldo_tabungan' => 'Rp 500.000',
                'sisa_tabungan' => 'Rp 3.000.000',
                'tipe_transaksi' => 'Tabungan Qurban',
            ];
        }

        return array_merge($common, $eventData, $typeData);
    }

    /**
     * Get parameters available for current type+event selection.
     * Returns array of ['key' => 'nama', 'label' => '{{nama}}', 'desc' => 'Nama pelanggan/donatur']
     */
    public function getAvailableParametersProperty(): array
    {
        $params = [];

        // Common parameters
        $params[] = ['key' => 'nama', 'label' => '{{nama}}', 'desc' => 'Nama pelanggan/donatur'];
        $params[] = ['key' => 'no_transaksi', 'label' => '{{no_transaksi}}', 'desc' => 'Nomor transaksi'];
        $params[] = ['key' => 'jumlah', 'label' => '{{jumlah}}', 'desc' => 'Jumlah pembayaran'];
        $params[] = ['key' => 'total', 'label' => '{{total}}', 'desc' => 'Total transfer'];
        $params[] = ['key' => 'yayasan', 'label' => '{{yayasan}}', 'desc' => 'Nama yayasan'];
        $params[] = ['key' => 'link_pembayaran', 'label' => '{{link_pembayaran}}', 'desc' => 'Link status pembayaran'];

        // Event-specific parameters
        if ($this->event === 'payment_created') {
            $params[] = ['key' => 'batas_waktu', 'label' => '{{batas_waktu}}', 'desc' => 'Batas waktu pembayaran'];
            $params[] = ['key' => 'metode_bayar', 'label' => '{{metode_bayar}}', 'desc' => 'Metode pembayaran'];
            $params[] = ['key' => 'info_bank', 'label' => '{{info_bank}}', 'desc' => 'Info rekening bank (nama, norek, atas nama)'];
            $params[] = ['key' => 'kode_unik', 'label' => '{{kode_unik}}', 'desc' => 'Kode unik transfer'];
        }

        // Type-specific parameters
        if ($this->type === 'donasi') {
            $params[] = ['key' => 'program', 'label' => '{{program}}', 'desc' => 'Nama program donasi'];
            $params[] = ['key' => 'tipe_transaksi', 'label' => '{{tipe_transaksi}}', 'desc' => 'Label tipe transaksi'];
        } elseif ($this->type === 'qurban') {
            $params[] = ['key' => 'nama_qurban', 'label' => '{{nama_qurban}}', 'desc' => 'Nama penerima qurban'];
            $params[] = ['key' => 'jenis_hewan', 'label' => '{{jenis_hewan}}', 'desc' => 'Jenis hewan qurban'];
            $params[] = ['key' => 'tipe_transaksi', 'label' => '{{tipe_transaksi}}', 'desc' => 'Label tipe transaksi'];
        } elseif ($this->type === 'tabungan_qurban') {
            $params[] = ['key' => 'nama_tabungan', 'label' => '{{nama_tabungan}}', 'desc' => 'Nama penabung'];
            $params[] = ['key' => 'target_tabungan', 'label' => '{{target_tabungan}}', 'desc' => 'Target tabungan'];
            $params[] = ['key' => 'saldo_tabungan', 'label' => '{{saldo_tabungan}}', 'desc' => 'Saldo tabungan saat ini'];
            $params[] = ['key' => 'sisa_tabungan', 'label' => '{{sisa_tabungan}}', 'desc' => 'Sisa kekurangan tabungan'];
            $params[] = ['key' => 'tipe_transaksi', 'label' => '{{tipe_transaksi}}', 'desc' => 'Label tipe transaksi'];
        }

        return $params;
    }
}
