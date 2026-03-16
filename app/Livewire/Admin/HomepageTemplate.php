<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use Livewire\Component;

class HomepageTemplate extends Component
{
    public string $selectedTemplate = 'default';

    /**
     * Hardcoded list of available templates.
     * To add a new template: add entry here AND create the corresponding blade file.
     * Format: 'slug' => 'Display Label'
     */
    public array $availableTemplates = [
        'default' => 'Default (Standard)',
        'v2' => 'Version 2 (Baru)',
    ];

    public function mount(): void
    {
        $this->selectedTemplate = AppSetting::get('home_template', 'default');
    }

    public function save(): void
    {
        $this->validate([
            'selectedTemplate' => ['required', 'string', 'in:' . implode(',', array_keys($this->availableTemplates))],
        ]);

        AppSetting::updateOrCreate(
            ['key' => 'home_template'],
            [
                'value' => $this->selectedTemplate,
                'type' => 'text',
                'group' => 'appearance',
                'label' => 'Template Halaman Utama',
                'description' => 'Pilih template tampilan halaman utama yang aktif.'
            ]
        );
        \Illuminate\Support\Facades\Cache::forget('app_setting_home_template');

        session()->flash('success', 'Template halaman utama berhasil disimpan.');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.homepage-template')
            ->layout('layouts.admin', ['title' => 'Template Halaman Utama']);
    }
}
