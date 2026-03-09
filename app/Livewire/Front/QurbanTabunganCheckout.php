<?php

namespace App\Livewire\Front;

use App\Models\QurbanAnimal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class QurbanTabunganCheckout extends Component
{
    public $target;

    public $deposit = 50000;

    public $showCustomDeposit = false;

    public $customDepositError = null;

    // Muqorib Data
    public $name;

    public $whatsapp;

    public $qurbanName; // Atas Nama

    public $email;

    public $isAnonymous = false;

    // Reminder
    public $reminder = false;

    public $reminderFrequency = 'bulanan';

    public $targets = [];

    public function mount()
    {
        // Load target tabungan dari database
        $animals = QurbanAnimal::tabungan()->active()->orderBy('price')->get();

        foreach ($animals as $animal) {
            $this->targets[$animal->id] = [
                'name' => $animal->name,
                'price' => (float) $animal->price,
                'desc' => $animal->description,
                'category' => $animal->category,
                'image' => $animal->image,
            ];
        }

        // Set default target ke animal pertama
        $this->target = $animals->first()?->id;

        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->whatsapp = $user->phone;
            $this->email = $user->email;
        }
    }

    public function setDeposit($value)
    {
        if ($value === 'custom') {
            $this->showCustomDeposit = true;
            $this->deposit = 0;
        } else {
            $this->showCustomDeposit = false;
            $this->deposit = $value;
            $this->customDeposit = null;
        }
    }

    public function updatedCustomDeposit()
    {
        $this->deposit = (int) str_replace(['.', ','], '', $this->customDeposit);

        if ($this->deposit > 0 && $this->deposit < 10000) {
            $this->customDepositError = 'Minimal setoran adalah Rp 10.000';
        } else {
            $this->customDepositError = null;
        }
    }

    public function submit()
    {
        if (str_starts_with($this->whatsapp, '0')) {
            $this->whatsapp = '62' . substr($this->whatsapp, 1);
        }

        $this->validate([
            'target' => 'required',
            'name' => 'required_if:isAnonymous,false',
            'whatsapp' => 'required',
            'deposit' => 'numeric|min:0',
        ], [
            'deposit.min' => 'Minimal setoran adalah Rp 10.000',
        ]);

        if ($this->deposit > 0 && $this->deposit < 10000) {
            $this->addError('deposit', 'Minimal setoran adalah Rp 10.000');
            return;
        }

        $targetData = $this->targets[$this->target];

        session([
            'checkout' => [
                'type' => 'qurban_tabungan',
                'target' => Str::slug($targetData['name']), // slug name for target_animal_type
                'animal_id' => $this->target,
                'target_name' => $targetData['name'],
                'target_price' => $targetData['price'],
                'amount' => $this->deposit,
                'name' => $this->name,
                'whatsapp' => $this->whatsapp,
                'qurban_name' => $this->qurbanName,
                'email' => $this->email,
                'is_anonymous' => $this->isAnonymous,
                'reminder_enabled' => $this->reminder,
                'reminder_frequency' => $this->reminderFrequency,
            ],
        ]);

        return redirect()->route('payment.method');
    }

    #[Layout('layouts.front')]
    #[Title('Buat Tabungan Qurban')]
    public function render()
    {
        return view('livewire.front.qurban-tabungan-checkout');
    }
}
