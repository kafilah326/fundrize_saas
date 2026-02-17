<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class MetaSetting extends Component
{
    public $pixel_id;
    public $access_token;
    public $pixel_enabled;
    public $capi_enabled;
    public $test_event_code;

    public function mount()
    {
        $this->pixel_id = AppSetting::get('meta_pixel_id');
        $this->access_token = AppSetting::get('meta_access_token');
        $this->pixel_enabled = AppSetting::get('meta_pixel_enabled') === 'true';
        $this->capi_enabled = AppSetting::get('meta_capi_enabled') === 'true';
        $this->test_event_code = AppSetting::get('meta_test_event_code');
    }

    public function save()
    {
        $this->validate([
            'pixel_id' => 'required|string',
            'access_token' => 'required|string',
        ]);

        AppSetting::set('meta_pixel_id', $this->pixel_id);
        AppSetting::set('meta_access_token', $this->access_token);
        AppSetting::set('meta_pixel_enabled', $this->pixel_enabled ? 'true' : 'false');
        AppSetting::set('meta_capi_enabled', $this->capi_enabled ? 'true' : 'false');
        AppSetting::set('meta_test_event_code', $this->test_event_code);

        session()->flash('success', 'Pengaturan Meta berhasil disimpan.');
    }

    #[Layout('layouts.admin')]
    #[Title('Meta Setting')]
    public function render()
    {
        return view('livewire.admin.meta-setting');
    }
}
