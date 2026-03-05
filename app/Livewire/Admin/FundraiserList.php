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
    public $receiptImage = null;

    // Modal Commission
    public $isCommissionModalOpen = false;
    public $selectedFundraiserCommissions = [];
    public $selectedFundraiserName = '';

    protected $queryString = [
        'activeTab' => ['except' => 'fundraisers'],
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'page' => ['except' => 1],
    ];

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
                    $query->whereHas('fundraiser', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('bank_name', 'like', '%' . $this->search . '%')
                    ->orWhere('account_name', 'like', '%' . $this->search . '%')
                    ->orWhere('account_number', 'like', '%' . $this->search . '%');
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
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('whatsapp', 'like', '%' . $this->search . '%');
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
