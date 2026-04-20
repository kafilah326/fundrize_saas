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
    public $tenantId;
    public $status = 'pending';

    public function mount()
    {
        $this->name = session('success_message', 'Yayasan Anda Berhasil Didaftarkan');
        $this->domain = session('tenant_domain');
        $this->email = session('admin_email');
        $this->tenantId = session('tenant_id');

        if (!$this->domain) {
            return redirect()->route('central.landing');
        }

        $this->checkStatus();
    }

    public function checkStatus()
    {
        if (!$this->tenantId) return;

        $tenant = \App\Models\Tenant::find($this->tenantId);
        if ($tenant) {
            $this->status = $tenant->status;
            
            if ($this->status === 'active') {
                $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $this->domain . "/login";
                return redirect()->to($url);
            }
        }
    }

    public function render()
    {
        return view('livewire.saas.registration-success');
    }
}
