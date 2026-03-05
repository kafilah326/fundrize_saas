<?php

namespace App\Livewire\Front;

use App\Models\FundraiserWithdrawal as WithdrawalModel;
use App\Models\FundraiserBank;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class FundraiserWithdrawal extends Component
{
    use WithPagination;

    public $fundraiser;
    public $availableBalance = 0;
    public $primaryBank = null;
    public $hasPendingWithdrawal = false;

    // Form
    public $amount;

    protected $rules = [
        'amount' => 'required|numeric|min:50000',
    ];

    public function mount()
    {
        $user = Auth::user()->load('fundraiser');
        $this->fundraiser = $user->fundraiser;

        if (!$this->fundraiser || $this->fundraiser->status !== 'approved') {
            return redirect()->route('profile.index');
        }

        $this->availableBalance = $this->fundraiser->available_balance;
        $this->primaryBank = FundraiserBank::where('fundraiser_id', $this->fundraiser->id)
                                ->where('is_primary', true)->first();
                                
        // If no primary bank but has banks, just pick the first one
        if (!$this->primaryBank) {
            $this->primaryBank = FundraiserBank::where('fundraiser_id', $this->fundraiser->id)->first();
        }

        $this->hasPendingWithdrawal = WithdrawalModel::where('fundraiser_id', $this->fundraiser->id)
                                        ->where('status', 'pending')
                                        ->exists();
    }

    public function setAmount($val)
    {
        if ($val === 'all') {
            $this->amount = $this->availableBalance;
        } else {
            $this->amount = $val;
        }
    }

    public function submitWithdrawal()
    {
        if ($this->hasPendingWithdrawal) {
            session()->flash('error', 'Anda masih memiliki pengajuan pencairan yang sedang diproses. Silakan tunggu hingga selesai.');
            return;
        }

        $this->validate();

        if (!$this->primaryBank) {
            session()->flash('error', 'Silakan tambahkan rekening bank tujuan terlebih dahulu.');
            return;
        }

        $this->availableBalance = $this->fundraiser->available_balance;

        if ($this->amount > $this->availableBalance) {
            $this->addError('amount', 'Nominal melebihi saldo ujroh Anda (Rp ' . number_format($this->availableBalance, 0, ',', '.') . ').');
            return;
        }

        if ($this->amount < 50000) {
            $this->addError('amount', 'Minimal pencairan adalah Rp 50.000.');
            return;
        }

        WithdrawalModel::create([
            'fundraiser_id' => $this->fundraiser->id,
            'amount' => $this->amount,
            'bank_name' => $this->primaryBank->bank_name,
            'account_number' => $this->primaryBank->account_number,
            'account_name' => $this->primaryBank->account_name,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Pengajuan pencairan berhasil dibuat. Silakan tunggu konfirmasi dari admin.');

        $this->reset('amount');
        $this->availableBalance = $this->fundraiser->available_balance;
        $this->hasPendingWithdrawal = true;
    }

    #[Layout('layouts.front')]
    #[Title('Pencairan Ujroh')]
    public function render()
    {
        $withdrawals = WithdrawalModel::where('fundraiser_id', $this->fundraiser->id)
            ->latest()
            ->paginate(10);

        return view('livewire.front.fundraiser-withdrawal', [
            'withdrawals' => $withdrawals
        ]);
    }
}
