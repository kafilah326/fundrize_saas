<?php

namespace App\Livewire\Front;

use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class DynamicProgramCheckout extends Component
{
    public $slug;
    public $program;
    
    // Dynamic fields
    public $quantity = 1;

    // Contact fields
    public $name;
    public $phone;
    public $email;
    public $doa;
    public $isAnonymous = false;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->program = Program::where('slug', $slug)->firstOrFail();

        // Security check
        if (!$this->program->is_dynamic) {
            return redirect()->route('program.checkout', $this->slug);
        }

        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->phone = $user->phone;
            $this->email = $user->email;
        }
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function updatedQuantity($value)
    {
        $val = (int) $value;
        if ($val < 1) {
            $this->quantity = 1;
        }
    }

    public function submit()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
            'name' => 'required_if:isAnonymous,false',
            'phone' => 'required',
        ]);

        $totalAmount = $this->quantity * $this->program->package_price;

        session([
            'checkout' => [
                'type' => 'program',
                'program_id' => $this->program->id,
                'program_name' => collect([$this->program->categories->first()->name ?? null, $this->program->title])->filter()->join(' - '),
                'amount' => $totalAmount,
                'package_quantity' => $this->quantity,
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
    #[Title('Form Pembayaran')]
    public function render()
    {
        return view('livewire.front.dynamic-program-checkout', [
            'totalAmount' => $this->quantity * $this->program->package_price,
            'packageLabel' => $this->program->package_label ?? 'paket'
        ]);
    }
}
