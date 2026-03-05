<?php

namespace App\Livewire\Front;

use App\Models\FundraiserBank;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FundraiserBankManage extends Component
{
    public $fundraiser;
    
    // Modal Form
    public $showModal = false;
    public $bankId = null;
    public $bank_name = '';
    public $account_number = '';
    public $account_name = '';

    protected $rules = [
        'bank_name' => 'required|string|max:255',
        'account_number' => 'required|string|max:50',
        'account_name' => 'required|string|max:255',
    ];

    public function mount()
    {
        $user = Auth::user()->load('fundraiser');
        $this->fundraiser = $user->fundraiser;

        if (!$this->fundraiser || $this->fundraiser->status !== 'approved') {
            return redirect()->route('profile.index');
        }
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['bank_name', 'account_number', 'account_name', 'bankId']);

        if ($id) {
            $bank = FundraiserBank::where('fundraiser_id', $this->fundraiser->id)->findOrFail($id);
            $this->bankId = $bank->id;
            $this->bank_name = $bank->bank_name;
            $this->account_number = $bank->account_number;
            $this->account_name = $bank->account_name;
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate();

        $isFirst = FundraiserBank::where('fundraiser_id', $this->fundraiser->id)->count() === 0;

        FundraiserBank::updateOrCreate(
            [
                'id' => $this->bankId,
                'fundraiser_id' => $this->fundraiser->id
            ],
            [
                'bank_name' => $this->bank_name,
                'account_number' => $this->account_number,
                'account_name' => $this->account_name,
                'is_primary' => $isFirst ? true : false,
            ]
        );

        $this->showModal = false;
        session()->flash('success', 'Data rekening berhasil disimpan.');
    }

    public function delete($id)
    {
        $bank = FundraiserBank::where('fundraiser_id', $this->fundraiser->id)->findOrFail($id);
        
        // If deleting primary, set another one as primary if exists
        if ($bank->is_primary) {
            $other = FundraiserBank::where('fundraiser_id', $this->fundraiser->id)
                        ->where('id', '!=', $id)->first();
            if ($other) {
                $other->update(['is_primary' => true]);
            }
        }
        
        $bank->delete();
        session()->flash('success', 'Rekening berhasil dihapus.');
    }

    public function setAsPrimary($id)
    {
        // Reset all
        FundraiserBank::where('fundraiser_id', $this->fundraiser->id)->update(['is_primary' => false]);
        // Set new primary
        FundraiserBank::where('fundraiser_id', $this->fundraiser->id)->where('id', $id)->update(['is_primary' => true]);
        
        session()->flash('success', 'Rekening utama berhasil diubah.');
    }

    #[Layout('layouts.front')]
    #[Title('Rekening Bank')]
    public function render()
    {
        $banks = FundraiserBank::where('fundraiser_id', $this->fundraiser->id)->latest()->get();

        return view('livewire.front.fundraiser-bank-manage', [
            'banks' => $banks
        ]);
    }
}
