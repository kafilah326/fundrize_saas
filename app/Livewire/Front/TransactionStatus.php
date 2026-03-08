<?php

namespace App\Livewire\Front;

use App\Models\AppSetting;
use App\Models\BankAccount;
use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class TransactionStatus extends Component
{
    public $checkout;
    public $trxId;
    public $expiryTime;
    public $bankAccount;
    public $paymentGroup = 'bank_transfer';
    public $paymentStatus = 'pending';
    public $uniqueCode = 0;
    public $totalTransfer = 0;
    public $payment;
    public $isValid = false;

    public function mount($id)
    {
        $this->trxId = $id;
        
        $this->payment = Payment::where('external_id', $this->trxId)->first();

        if (!$this->payment) {
            $this->isValid = false;
            return;
        }

        $this->isValid = true;
        $this->paymentGroup = $this->payment->payment_type;
        $this->paymentStatus = $this->payment->status;
        $this->uniqueCode = $this->payment->unique_code ?? 0;
        
        // Reconstruct checkout data for view
        $this->checkout = $this->payment->checkout_data;
        // Ensure totals match the payment record
        $this->totalTransfer = $this->payment->total;
        $this->checkout['total'] = $this->payment->total;
        $this->checkout['admin_fee'] = $this->payment->admin_fee;
        $this->checkout['amount'] = $this->payment->amount;
        
        if ($this->payment->expired_at) {
            $this->expiryTime = $this->payment->expired_at;
        } else {
            $this->expiryTime = $this->payment->created_at->addHours(24);
        }
        
        if ($this->paymentGroup === 'bank_transfer') {
            $bankName = $this->payment->payment_method;
            $this->bankAccount = BankAccount::where('bank_name', $bankName)->first();
        }
    }

    public function refreshStatus()
    {
        if ($this->payment) {
            $this->payment->refresh();
            $this->paymentStatus = $this->payment->status;
        }
    }

    #[Layout('layouts.front')]
    #[Title('Detail Transaksi')]
    public function render()
    {
        return view('livewire.front.transaction-status');
    }
}
