<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class QurbanCheckout extends Component
{
    public $animal;

    // Donor Data
    public $name;
    public $whatsapp;
    public $qurbanName; // Atas Nama
    public $email;
    public $address;
    public $city;
    public $postalCode;
    public $isAnonymous = false;

    // Options
    public $slaughterMethod = 'wakalah'; // wakalah, hadir
    public $deliveryMethod = 'dikirim'; // dikirim, ambil, wakaf

    public function mount()
    {
        $this->animal = session('selected_animal');
        
        if (!$this->animal) {
            return redirect()->route('qurban.index');
        }

        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->whatsapp = $user->phone;
            $this->email = $user->email;
        }
    }

    public function updatedIsAnonymous($value)
    {
        // Don't change the actual name fields — is_anonymous flag
        // is already stored in session and used for display purposes.
        // Keep the real name so it's saved to the database.
    }

    public function submit()
    {
        $rules = [
            'name' => 'required',
            'whatsapp' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postalCode' => 'required',
            'slaughterMethod' => 'required',
            'deliveryMethod' => 'required',
        ];

        $this->validate($rules);

        session([
            'checkout' => [
                'type' => 'qurban_langsung',
                'target_name' => $this->animal['name'],
                'target_price' => $this->animal['price'],
                'amount' => $this->animal['price'],
                'animal_data' => $this->animal,
                'name' => $this->name,
                'whatsapp' => $this->whatsapp,
                'qurban_name' => $this->qurbanName,
                'email' => $this->email,
                'address' => $this->address,
                'city' => $this->city,
                'postal_code' => $this->postalCode,
                'slaughter_method' => $this->slaughterMethod,
                'delivery_method' => $this->deliveryMethod,
                'is_anonymous' => $this->isAnonymous,
            ]
        ]);

        return redirect()->route('payment.method');
    }

    #[Layout('layouts.front')]
    #[Title('Formulir Qurban')]
    public function render()
    {
        return view('livewire.front.qurban-checkout');
    }
}
