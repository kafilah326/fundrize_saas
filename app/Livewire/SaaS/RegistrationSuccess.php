<?php

namespace App\Livewire\SaaS;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.saas')]
#[Title('Registrasi Berhasil')]
class RegistrationSuccess extends Component
{
    public $name;
    public $domain;
    public $email;

    public function mount()
    {
        $this->name = session('success_message', 'Yayasan Anda Berhasil Didaftarkan');
        $this->domain = session('tenant_domain');
        $this->email = session('admin_email');

        if (!$this->domain) {
            return redirect()->route('central.landing');
        }
    }

    public function render()
    {
        return view('livewire.saas.registration-success');
    }
}
