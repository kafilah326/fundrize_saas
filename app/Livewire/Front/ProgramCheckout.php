<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;

class ProgramCheckout extends Component
{
    public $slug;
    public $program;
    public $amount = 50000;
    public $customAmount;
    public $name;
    public $phone;
    public $email;
    public $doa;
    public $isAnonymous = false;
    public $showCustomAmount = false;
    public $customAmountError = null;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->program = Program::where('slug', $slug)->firstOrFail();

        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->phone = $user->phone;
            $this->email = $user->email;
        }
    }

    public function setAmount($value)
    {
        if ($value === 'custom') {
            $this->showCustomAmount = true;
            $this->amount = 0;
        } else {
            $this->showCustomAmount = false;
            $this->amount = $value;
            $this->customAmount = null;
        }
    }
    
    public function updatedCustomAmount()
    {
        $this->amount = (int) str_replace(['.', ','], '', $this->customAmount);

        if ($this->amount > 0 && $this->amount < 10000) {
            $this->customAmountError = 'Minimal donasi adalah Rp 10.000';
        } else {
            $this->customAmountError = null;
        }
    }

    public function submit()
    {
        $this->validate([
            'amount' => 'required|numeric|min:10000',
            'name' => 'required_if:isAnonymous,false',
            'phone' => 'required',
        ], [
            'amount.min' => 'Minimal donasi adalah Rp 10.000',
        ]);

        session([
            'checkout' => [
                'type' => 'program',
                'program_id' => $this->program->id,
                'program_name' => $this->program->title,
                'amount' => $this->showCustomAmount ? (int)$this->customAmount : $this->amount,
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'doa' => $this->doa,
                'is_anonymous' => $this->isAnonymous,
            ]
        ]);

        return redirect()->route('payment.method');
    }
    
    #[Layout('layouts.front')]
    #[Title('Form Donasi')]
    public function render()
    {
        return view('livewire.front.program-checkout');
    }
}
