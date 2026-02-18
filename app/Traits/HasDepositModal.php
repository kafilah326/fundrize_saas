<?php

namespace App\Traits;

use App\Models\QurbanSaving;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait HasDepositModal
{
    public $showDepositModal = false;
    public $depositAmount = 50000;
    public $customDepositAmount;
    public $showCustomDepositInput = false;
    public $selectedSavingId;
    public $selectedSaving;

    public function openDepositModal($savingId)
    {
        $this->selectedSavingId = $savingId;
        $this->selectedSaving = QurbanSaving::where('id', $savingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $this->showDepositModal = true;
        $this->depositAmount = 50000; // Reset to default
        $this->showCustomDepositInput = false;
        $this->customDepositAmount = null;
    }

    public function closeDepositModal()
    {
        $this->showDepositModal = false;
        $this->selectedSaving = null;
        $this->selectedSavingId = null;
    }

    public function setDepositAmount($amount)
    {
        if ($amount === 'custom') {
            $this->showCustomDepositInput = true;
            $this->depositAmount = 0;
        } else {
            $this->showCustomDepositInput = false;
            $this->depositAmount = $amount;
            $this->customDepositAmount = null;
        }
    }

    public function updatedCustomDepositAmount()
    {
        // Remove dots/commas and convert to int
        $this->depositAmount = (int) str_replace(['.', ','], '', $this->customDepositAmount);
    }

    public function submitDeposit()
    {
        $this->validate([
            'depositAmount' => 'required|numeric|min:10000', // Minimum 10k for example
        ]);

        if (!$this->selectedSaving) {
            return;
        }

        // Store checkout data in session
        session([
            'checkout' => [
                'type' => 'qurban_tabungan',
                'saving_id' => $this->selectedSaving->id,
                'target' => $this->selectedSaving->target_animal_type,
                'target_name' => str_replace('-', ' ', $this->selectedSaving->target_animal_type), // Just for display if needed
                'target_price' => $this->selectedSaving->target_amount, // Keep original target amount
                'amount' => $this->depositAmount,
                'name' => $this->selectedSaving->donor_name,
                'whatsapp' => $this->selectedSaving->whatsapp,
                'qurban_name' => $this->selectedSaving->qurban_name,
                'email' => Auth::user()->email,
                'is_anonymous' => false, // Assuming existing savings are not anonymous or we use saved pref
                'is_deposit' => true, // Flag to indicate this is a top-up
            ]
        ]);

        return redirect()->route('payment.method');
    }
}
