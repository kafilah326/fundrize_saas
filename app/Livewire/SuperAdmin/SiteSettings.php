<?php

namespace App\Livewire\SuperAdmin;

use App\Models\AppSetting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.superadmin')]
#[Title('Pengaturan Situs')]
class SiteSettings extends Component
{
    use WithFileUploads;

    public $activeTab = 'hero'; // hero, features, faq, cta
    
    // Hero Fields
    public $hero_title;
    public $hero_subtitle;
    public $hero_cta_text;
    public $hero_badge;
    public $hero_image;
    public $existing_hero_image;

    // Features Section
    public $features_title;
    public $features_subtitle;

    // FAQ Section
    public $faqs = [];

    // Final CTA
    public $cta_title;
    public $cta_subtitle;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // Global scope settings (tenant_id = null)
        $this->hero_title = AppSetting::get('landing_hero_title');
        $this->hero_subtitle = AppSetting::get('landing_hero_subtitle');
        $this->hero_cta_text = AppSetting::get('landing_hero_cta_text');
        $this->hero_badge = AppSetting::get('landing_hero_badge');
        $this->existing_hero_image = AppSetting::get('landing_hero_image');

        $this->features_title = AppSetting::get('landing_features_title');
        $this->features_subtitle = AppSetting::get('landing_features_subtitle');

        $this->faqs = json_decode(AppSetting::get('landing_faqs', '[]'), true);

        $this->cta_title = AppSetting::get('landing_cta_title');
        $this->cta_subtitle = AppSetting::get('landing_cta_subtitle');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function addFaq()
    {
        $this->faqs[] = ['q' => '', 'a' => ''];
    }

    public function removeFaq($index)
    {
        unset($this->faqs[$index]);
        $this->faqs = array_values($this->faqs);
    }

    public function saveSettings()
    {
        // Helper to update global setting
        $updateGlobal = function($key, $value, $group, $type, $label) {
            AppSetting::updateOrCreate(
                ['key' => $key, 'tenant_id' => null],
                ['value' => $value, 'group' => $group, 'type' => $type, 'label' => $label]
            );
            // Clear cache manually since public static set() does it but here we are using updateOrCreate
            \Illuminate\Support\Facades\Cache::forget("app_setting_global_{$key}");
        };

        // Save Hero
        $updateGlobal('landing_hero_title', $this->hero_title, 'landing_hero', 'text', 'Hero Title');
        $updateGlobal('landing_hero_subtitle', $this->hero_subtitle, 'landing_hero', 'textarea', 'Hero Subtitle');
        $updateGlobal('landing_hero_cta_text', $this->hero_cta_text, 'landing_hero', 'text', 'Hero CTA Text');
        $updateGlobal('landing_hero_badge', $this->hero_badge, 'landing_hero', 'text', 'Hero Badge');

        // Handle Image Upload
        if ($this->hero_image) {
            $imagePath = $this->hero_image->store('landing', 'public');
            $updateGlobal('landing_hero_image', $imagePath, 'landing_hero', 'text', 'Hero Image');
            $this->existing_hero_image = $imagePath;
            $this->hero_image = null;
        }

        // Save Features
        $updateGlobal('landing_features_title', $this->features_title, 'landing_features', 'text', 'Features Title');
        $updateGlobal('landing_features_subtitle', $this->features_subtitle, 'landing_features', 'textarea', 'Features Subtitle');

        // Save FAQ
        $updateGlobal('landing_faqs', json_encode($this->faqs), 'landing_faq', 'json', 'Landing FAQs');

        // Save CTA
        $updateGlobal('landing_cta_title', $this->cta_title, 'landing_cta', 'text', 'CTA Title');
        $updateGlobal('landing_cta_subtitle', $this->cta_subtitle, 'landing_cta', 'textarea', 'CTA Subtitle');

        session()->flash('success', 'Pengaturan situs berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.super-admin.site-settings');
    }
}
