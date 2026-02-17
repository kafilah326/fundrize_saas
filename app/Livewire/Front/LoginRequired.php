<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class LoginRequired extends Component
{
    public $tab = '';

    public function mount()
    {
        $this->tab = request()->query('tab', 'home');
    }

    #[Layout('layouts.front')]
    #[Title('Akses Terbatas')]
    public function render()
    {
        return view('livewire.front.login-required');
    }
}
