<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use App\Models\MaintenanceFee;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.superadmin')]
#[Title('Riwayat Transaksi')]
class TransactionList extends Component
{
    use WithPagination;

    public $type = 'all'; // all, registration, maintenance, addon_purchase
    public $status = 'all'; 
    public $search = '';
    public $dateRange = 'all'; // all, today, this_week, this_month

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => 'all'],
        'status' => ['except' => 'all'],
        'dateRange' => ['except' => 'all'],
    ];

    public function updating()
    {
        $this->resetPage();
    }

    public function verifyPayment($id)
    {
        $fee = MaintenanceFee::findOrFail($id);
        $fee->update([
            'status' => 'verified',
            'paid_at' => now(),
        ]);

        session()->flash('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function rejectPayment($id)
    {
        $fee = MaintenanceFee::findOrFail($id);
        $fee->update(['status' => 'rejected']);
        session()->flash('error', 'Pembayaran telah ditolak.');
    }

    public function render()
    {
        $query = \App\Models\SaasTransaction::with('tenant')
            ->when($this->type !== 'all', fn($q) => $q->where('type', $this->type))
            ->when($this->status !== 'all', fn($q) => $q->where('status', $this->status))
            ->when($this->search, function($q) {
                $q->whereHas('tenant', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'));
            })
            ->when($this->dateRange !== 'all', function($q) {
                if ($this->dateRange === 'today') $q->whereDate('created_at', now());
                if ($this->dateRange === 'this_week') $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                if ($this->dateRange === 'this_month') $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            });

        // Calculate Stats
        $stats = [
            'total_amount' => (clone $query)->where('status', 'paid')->sum('amount'),
            'paid_count' => (clone $query)->where('status', 'paid')->count(),
            'pending_count' => (clone $query)->where('status', 'pending')->count(),
        ];

        $transactions = $query->latest()->paginate(10);

        return view('livewire.super-admin.transaction-list', [
            'transactions' => $transactions,
            'stats' => $stats
        ]);
    }
}
