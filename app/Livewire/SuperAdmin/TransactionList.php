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

    public $type = 'all'; // all, registration, maintenance
    public $status = 'all';
    public $search = '';

    public function updatingSearch()
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
        $transactions = \App\Models\SaasTransaction::with('tenant')
            ->when($this->type !== 'all', fn($q) => $q->where('type', $this->type))
            ->when($this->status !== 'all', fn($q) => $q->where('status', $this->status))
            ->when($this->search, function($q) {
                $q->whereHas('tenant', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'));
            })
            ->latest()
            ->paginate(20);

        return view('livewire.super-admin.transaction-list', [
            'transactions' => $transactions,
        ]);
    }
}
