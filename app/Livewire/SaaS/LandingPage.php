<?php

namespace App\Livewire\SaaS;

use App\Models\Plan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.saas')]
class LandingPage extends Component
{
    public function render()
    {
        $plans = Plan::active()->orderBy('sort_order')->get();
        
        $allFeatures = [
            'dynamic_program' => 'Program Dinamis (Paket)',
            'custom_domain' => 'Custom Domain Support',
            'whatsapp' => 'WhatsApp Notification',
            'fundraiser' => 'Manajemen Fundraiser',
            'qurban' => 'Modul Qurban',
            'zakat' => 'Modul Zakat',
        ];

        return view('livewire.saas.landing-page', compact('plans', 'allFeatures'));
    }
}
