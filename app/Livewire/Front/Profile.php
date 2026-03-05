<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

use App\Models\FoundationSetting;

class Profile extends Component
{
    public $user;
    public $foundation;

    public function mount()
    {
        $this->user = Auth::user()->load('fundraiser');
        $this->foundation = FoundationSetting::first();
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    #[Layout('layouts.front')]
    #[Title('Profil')]
    public function render()
    {
        return view('livewire.front.profile');
    }
}
