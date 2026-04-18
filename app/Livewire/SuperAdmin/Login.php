<?php

namespace App\Livewire\SuperAdmin;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.superadmin')]
class Login extends Component
{
    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::guard('superadmin')->attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect()->route('superadmin.dashboard');
        }

        session()->flash('error', 'Kredensial tidak valid.');
    }

    public function render()
    {
        return view('livewire.super-admin.login')
            ->title('Login');
    }
}
