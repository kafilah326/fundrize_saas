<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    public function save()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed|different:current_password',
        ], [
            'current_password.current_password' => 'Password lama tidak sesuai',
            'password.different' => 'Password baru tidak boleh sama dengan password lama',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($this->password)
        ]);

        session()->flash('success', 'Password berhasil diubah');
        return redirect()->route('profile.index');
    }

    #[Layout('layouts.front')]
    #[Title('Ubah Password')]
    public function render()
    {
        return view('livewire.front.change-password');
    }
}
