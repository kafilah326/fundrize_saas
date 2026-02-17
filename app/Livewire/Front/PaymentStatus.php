<?php

namespace App\Livewire\Front;

use App\Models\BankAccount;
use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class PaymentStatus extends Component
{
    public $checkout;
    public $trxId;
    public $expiryTime;
    public $bankAccount;
    public $paymentGroup = 'bank_transfer';
    public $paymentStatus = 'pending';
    public $uniqueCode = 0;
    public $totalTransfer = 0;

    public function mount()
    {
        // Try to get transaction ID from query param (Xendit return) or session (Bank Transfer)
        $trxId = request('id') ?? request('external_id') ?? session('transaction_id');
        
        if (!$trxId) {
            // Fallback for old session structure (just in case)
            if (session('checkout_final')) {
                $this->handleLegacySession();
                return;
            }
            return redirect()->route('home');
        }

        $payment = Payment::where('external_id', $trxId)->first();

        if (!$payment) {
            // If ID exists in session but not DB, handle error or fallback
            return redirect()->route('home');
        }

        $this->trxId = $payment->external_id;
        $this->paymentGroup = $payment->payment_type;
        $this->paymentStatus = $payment->status;
        $this->uniqueCode = $payment->unique_code ?? 0;
        
        // Reconstruct checkout data for view
        $this->checkout = $payment->checkout_data;
        // Ensure totals match the payment record
        $this->totalTransfer = $payment->total;
        $this->checkout['total'] = $payment->total;
        $this->checkout['admin_fee'] = $payment->admin_fee;
        $this->checkout['amount'] = $payment->amount;
        
        if ($payment->expired_at) {
            $this->expiryTime = $payment->expired_at;
        } else {
            $this->expiryTime = now()->addHours(24);
        }
        
        if ($this->paymentGroup === 'bank_transfer') {
            $bankName = $payment->payment_method;
            $this->bankAccount = BankAccount::where('bank_name', $bankName)->first();
        }
    }

    private function handleLegacySession()
    {
        // Keep existing logic for fallback if needed, or just redirect
        $sessionCheckout = session('checkout_final');
        $this->checkout = $sessionCheckout;
        $this->trxId = $this->checkout['trx_id'];
        $this->paymentGroup = $this->checkout['payment_group'] ?? 'bank_transfer';
        $this->paymentStatus = 'pending';
        $this->expiryTime = now()->addHours(24);
        
        if ($this->paymentGroup === 'bank_transfer') {
            $bankName = $this->checkout['payment_method'];
            $this->bankAccount = BankAccount::where('bank_name', $bankName)->first();
        }
    }

    #[Layout('layouts.front')]
    #[Title('Status Pembayaran')]
    public function render()
    {
        return view('livewire.front.payment-status');
    }
}
