<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|numeric|digits_between:10,14',
        'password' => 'required|min:8|confirmed',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => 'user',
            'password' => $this->password,
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    #[Layout('layouts.front')]
    #[Title('Daftar Akun')]
    public function render()
    {
        return view('livewire.auth.register');
    }
}
