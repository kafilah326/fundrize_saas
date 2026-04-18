<?php

namespace App\Livewire\SaaS;

use App\Models\Plan;
use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.saas')]
class LandingPage extends Component
{
    public function render()
    {
        $plans = Plan::active()->orderBy('sort_order')->get();
        
        $settings = [
            'hero_title' => AppSetting::get('landing_hero_title', 'Digitalkan Yayasan Anda Dalam Sekejap.'),
            'hero_subtitle' => AppSetting::get('landing_hero_subtitle'),
            'hero_cta_text' => AppSetting::get('landing_hero_cta_text', 'Lihat Fitur'),
            'hero_badge' => AppSetting::get('landing_hero_badge'),
            'hero_image' => AppSetting::get('landing_hero_image'),
            'features_title' => AppSetting::get('landing_features_title', 'Kenapa Harus Menggunakan Fundrize?'),
            'features_subtitle' => AppSetting::get('landing_features_subtitle'),
            'faqs' => json_decode(AppSetting::get('landing_faqs', '[]'), true),
            'cta_title' => AppSetting::get('landing_cta_title'),
            'cta_subtitle' => AppSetting::get('landing_cta_subtitle'),
        ];

        $allFeatures = [
            'dynamic_program' => 'Program Dinamis (Paket)',
            'custom_domain' => 'Custom Domain Support',
            'whatsapp' => 'WhatsApp Notification',
            'fundraiser' => 'Manajemen Fundraiser',
            'qurban' => 'Modul Qurban',
            'zakat' => 'Modul Zakat',
        ];

        return view('livewire.saas.landing-page', compact('plans', 'allFeatures', 'settings'));
    }
}
