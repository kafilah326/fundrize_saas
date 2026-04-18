<?php

namespace App\Livewire\Admin;

use App\Models\Fundraiser;
use App\Models\FundraiserWithdrawal;
use App\Models\FundraiserCommission;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class FundraiserList extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $activeTab = 'fundraisers'; // fundraisers, withdrawals, commissions

    #[Url]
    public $search = '';

    #[Url]
    public $statusFilter = '';

    public $perPage = 10;
    
    // Modal Fundraiser
    public $isOpen = false;
    public $fundraiserId = null;
    public $fundraiserDetail = null;
    public $rejectReason = '';

    // Modal Withdrawal
    public $isWithdrawalModalOpen = false;
    public $withdrawalId = null;
    public $withdrawalDetail = null;
    public $withdrawalRejectReason = '';
    public $receiptImage = null;    // Modal Commission
    public $isCommissionModalOpen = false;
    public $selectedFundraiserCommissions = [];
    public $selectedFundraiserName = '';

    // Settings
    public $program_commission_type = 'none';
    public $program_commission_amount = 0;
    public $qurban_commission_type = 'none';
    public $qurban_commission_amount = 0;

    protected $queryString = [
        'activeTab' => ['except' => 'fundraisers'],
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'page' => ['except' => 1],
    ];


    public function mount()
    {
        $this->program_commission_type = \App\Models\AppSetting::get('fundraiser_program_commission_type', 'none');
        $this->program_commission_amount = \App\Models\AppSetting::get('fundraiser_program_commission_amount', 0);
        $this->qurban_commission_type = \App\Models\AppSetting::get('fundraiser_qurban_commission_type', 'none');
        $this->qurban_commission_amount = \App\Models\AppSetting::get('fundraiser_qurban_commission_amount', 0);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->search = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    // --- FUNDRAISER METHODS ---

    public function showDetail($id)
    {
        $this->fundraiserId = $id;
        $this->fundraiserDetail = Fundraiser::with('user')->findOrFail($id);
        $this->rejectReason = $this->fundraiserDetail->rejected_reason ?? '';
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->fundraiserId = null;
        $this->fundraiserDetail = null;
        $this->rejectReason = '';
    }

    public function approve($id)
    {
        $fundraiser = Fundraiser::findOrFail($id);
        $fundraiser->update(['status' => 'approved', 'rejected_reason' => null]);
        session()->flash('success', 'Fundriser berhasil disetujui.');

        if ($this->isOpen && $this->fundraiserId == $id) {
            $this->closeModal();
        }
    }

    public function reject()
    {
        $this->validate([
            'rejectReason' => 'required|string',
        ]);

        if ($this->fundraiserDetail) {
            $this->fundraiserDetail->update([
                'status' => 'rejected',
                'rejected_reason' => $this->rejectReason,
            ]);
            session()->flash('success', 'Fundriser ditolak.');
            $this->closeModal();
        }
    }

    public function delete($id)
    {
        Fundraiser::findOrFail($id)->delete();
        session()->flash('success', 'Data fundriser berhasil dihapus.');
    }


    // --- WITHDRAWAL METHODS ---

    public function showWithdrawalDetail($id)
    {
        $this->withdrawalId = $id;
        $this->withdrawalDetail = FundraiserWithdrawal::with('fundraiser')->findOrFail($id);
        $this->withdrawalRejectReason = $this->withdrawalDetail->rejected_reason ?? '';
        $this->receiptImage = null;
        $this->isWithdrawalModalOpen = true;
    }

    public function closeWithdrawalModal()
    {
        $this->isWithdrawalModalOpen = false;
        $this->withdrawalId = null;
        $this->withdrawalDetail = null;
        $this->withdrawalRejectReason = '';
        $this->receiptImage = null;
    }

    public function approveWithdrawal()
    {
        $this->validate([
            'receiptImage' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($this->withdrawalDetail && $this->withdrawalDetail->status === 'pending') {
            
            $imagePath = null;
            if ($this->receiptImage) {
                $imagePath = $this->receiptImage->store('withdrawals', 'public');
            }

            $this->withdrawalDetail->update([
                'status' => 'approved',
                'receipt_image' => $imagePath,
                'processed_at' => now(),
                'rejected_reason' => null,
            ]);

            session()->flash('success', 'Pencairan berhasil disetujui.');
            $this->closeWithdrawalModal();
        }
    }

    public function rejectWithdrawal()
    {
        $this->validate([
            'withdrawalRejectReason' => 'required|string',
        ]);

        if ($this->withdrawalDetail && $this->withdrawalDetail->status === 'pending') {
            $this->withdrawalDetail->update([
                'status' => 'rejected',
                'rejected_reason' => $this->withdrawalRejectReason,
                'processed_at' => now(),
            ]);

            session()->flash('success', 'Pencairan ditolak.');
            $this->closeWithdrawalModal();
        }
    }


    // --- COMMISSION METHODS ---

    public function showCommissionDetail($fundraiserId)
    {
        $fundraiser = Fundraiser::with(['commissions.commissionable'])->findOrFail($fundraiserId);
        $this->selectedFundraiserName = $fundraiser->name;
        // Ambil semua komisi, urutkan dari yang terbaru
        $this->selectedFundraiserCommissions = $fundraiser->commissions()->latest()->get();
        $this->isCommissionModalOpen = true;
    }

    public function closeCommissionModal()
    {
        $this->isCommissionModalOpen = false;
        $this->selectedFundraiserCommissions = [];
    }

    public function saveSettings()
    {
        $this->validate([
            'program_commission_type' => 'required|in:none,fixed,percentage',
            'program_commission_amount' => 'required|numeric|min:0',
            'qurban_commission_type' => 'required|in:none,fixed,percentage',
            'qurban_commission_amount' => 'required|numeric|min:0',
        ]);

        $settings = [
            'fundraiser_program_commission_type' => ['value' => $this->program_commission_type, 'type' => 'string'],
            'fundraiser_program_commission_amount' => ['value' => $this->program_commission_amount, 'type' => 'number'],
            'fundraiser_qurban_commission_type' => ['value' => $this->qurban_commission_type, 'type' => 'string'],
            'fundraiser_qurban_commission_amount' => ['value' => $this->qurban_commission_amount, 'type' => 'number'],
        ];

        foreach ($settings as $key => $data) {
            \App\Models\AppSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data['value'],
                    'type' => $data['type'],
                    'group' => 'fundraiser',
                    'label' => ucwords(str_replace('_', ' ', $key))
                ]
            );
            \Illuminate\Support\Facades\Cache::forget('app_setting_' . $key);
        }

        session()->flash('success', 'Pengaturan Ujroh berhasil disimpan.');
    }


    #[Layout('layouts.admin')]
    public function render()
    {
        $data = null;

        if ($this->activeTab === 'fundraisers') {
            $data = Fundraiser::with('user')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhere('whatsapp', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->statusFilter, function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                ->latest()
                ->paginate($this->perPage);
        } elseif ($this->activeTab === 'withdrawals') {
            $data = FundraiserWithdrawal::with('fundraiser')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->whereHas('fundraiser', function($sub) {
                            $sub->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('bank_name', 'like', '%' . $this->search . '%')
                        ->orWhere('account_name', 'like', '%' . $this->search . '%')
                        ->orWhere('account_number', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->statusFilter, function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                ->latest()
                ->paginate($this->perPage);
        } elseif ($this->activeTab === 'commissions') {
            $data = Fundraiser::with('user')
                ->withSum(['commissions as total_commission' => function($query) {
                    $query->where('status', 'success');
                }], 'amount')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhere('whatsapp', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->statusFilter, function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                ->latest()
                ->paginate($this->perPage);
        }

        return view('livewire.admin.fundraiser-list', [
            'data' => $data,
        ]);
    }
}
