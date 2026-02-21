<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\FoundationSetting;
use App\Models\BankAccount;
use App\Models\AppSetting;

class Settings extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $activeTab = 'foundation'; // foundation, bank, api
    public $perPage = 5;

    // Foundation Fields
    public $foundationId;
    public $name, $tagline, $about, $vision, $mission, $address, $phone, $email;
    public $logo, $existingLogo;
    public $favicon, $existingFavicon;
    // Note: Social Media and Focus Areas handled as simple text/array logic if needed, simplified for now

    // Bank Account Fields
    // public $bankAccounts; // Removed: Passed directly to view for pagination
    public $bankId;
    public $bank_name, $account_number, $account_holder_name, $is_active = true;
    public $bank_icon; // temporary file upload
    public $existingBankIcon; // stored path
    public $isBankModalOpen = false;

    // API Fields
    // StarSender logic moved to separate component
    public $xendit_secret_key;
    public $xendit_webhook_token;

    // Appearance Fields
    public $theme_color;
    public $default_theme_color = '#FF6B35';
    public $secondary_color;
    public $default_secondary_color = '#FDF2EB'; // Very light orange/cream

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required|string',
        'address' => 'required|string',
    ];

    public function mount()
    {
        // Load Foundation Settings
        $foundation = FoundationSetting::first();
        if ($foundation) {
            $this->foundationId = $foundation->id;
            $this->name = $foundation->name;
            $this->tagline = $foundation->tagline;
            $this->about = $foundation->about;
            $this->vision = $foundation->vision;
            $this->mission = is_array($foundation->mission) ? implode("\n", $foundation->mission) : $foundation->mission;
            $this->address = $foundation->address;
            $this->phone = $foundation->phone;
            $this->email = $foundation->email;
            $this->existingLogo = $foundation->logo;
            $this->existingFavicon = $foundation->favicon;
        }

        // Load API Settings
        $this->xendit_secret_key = AppSetting::get('xendit_secret_key');
        $this->xendit_webhook_token = AppSetting::get('xendit_webhook_token');

        // Load Appearance Settings
        $this->theme_color = AppSetting::get('theme_color', $this->default_theme_color);
        $this->secondary_color = AppSetting::get('secondary_color', $this->default_secondary_color);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $bankAccounts = BankAccount::orderBy('sort_order')->paginate($this->perPage);
        
        return view('livewire.admin.settings', [
            'bankAccounts' => $bankAccounts
        ])->layout('layouts.admin');
    }

    // Foundation Methods
    public function saveFoundation()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
        ]);

        $data = [
            'name' => $this->name,
            'tagline' => $this->tagline,
            'about' => $this->about,
            'vision' => $this->vision,
            'mission' => explode("\n", $this->mission), // Convert newline separated string to array
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
        ];

        if ($this->logo) {
            $logoName = $this->logo->store('foundation', 'public');
            $data['logo'] = $logoName;
        }

        if ($this->favicon) {
            $faviconName = $this->favicon->store('foundation', 'public');
            $data['favicon'] = $faviconName;
        }

        if ($this->foundationId) {
            FoundationSetting::where('id', $this->foundationId)->update($data);
        } else {
            FoundationSetting::create($data);
        }
        
        // Update local state to reflect changes
        if (isset($data['logo'])) {
            $this->existingLogo = $data['logo'];
            $this->logo = null; // Clear the temporary upload
        }
        
        if (isset($data['favicon'])) {
            $this->existingFavicon = $data['favicon'];
            $this->favicon = null; // Clear the temporary upload
        }

        session()->flash('success', 'Profil yayasan berhasil diperbarui.');
    }

    // Bank Methods
    public function createBank()
    {
        $this->resetBankForm();
        $this->isBankModalOpen = true;
    }

    public function editBank($id)
    {
        $bank = BankAccount::findOrFail($id);
        $this->bankId = $id;
        $this->bank_name = $bank->bank_name;
        $this->account_number = $bank->account_number;
        $this->account_holder_name = $bank->account_holder_name;
        $this->existingBankIcon = $bank->icon;
        $this->is_active = $bank->is_active;
        $this->isBankModalOpen = true;
    }

    public function saveBank()
    {
        $this->validate([
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_holder_name' => 'required|string',
            'bank_icon' => 'nullable|image|max:2048',
        ]);

        $data = [
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_holder_name' => $this->account_holder_name,
            'is_active' => $this->is_active,
        ];

        if ($this->bank_icon) {
            $iconName = $this->bank_icon->store('bank-icons', 'public');
            $data['icon'] = $iconName;
        }

        BankAccount::updateOrCreate(
            ['id' => $this->bankId],
            $data
        );

        // Clear local file state
        if (isset($data['icon'])) {
            $this->existingBankIcon = $data['icon'];
            $this->bank_icon = null;
        }

        session()->flash('success', 'Rekening bank berhasil disimpan.');
        $this->isBankModalOpen = false;
        $this->resetBankForm();
    }

    public function deleteBank($id)
    {
        BankAccount::destroy($id);
        session()->flash('success', 'Rekening bank berhasil dihapus.');
    }

    public function toggleBankStatus($id)
    {
        $bank = BankAccount::findOrFail($id);
        $bank->is_active = !$bank->is_active;
        $bank->save();
    }

    private function resetBankForm()
    {
        $this->bankId = null;
        $this->bank_name = '';
        $this->account_number = '';
        $this->account_holder_name = '';
        $this->is_active = true;
        $this->bank_icon = null;
        $this->existingBankIcon = null;
    }
    
    public function closeBankModal()
    {
        $this->isBankModalOpen = false;
        $this->resetBankForm();
    }

    // API Methods
    public function saveApi()
    {
        $this->validate([
            'xendit_secret_key' => 'required|string',
            'xendit_webhook_token' => 'required|string',
        ]);

        AppSetting::set('xendit_secret_key', $this->xendit_secret_key);
        AppSetting::set('xendit_webhook_token', $this->xendit_webhook_token);

        session()->flash('success', 'Pengaturan API berhasil diperbarui.');
    }

    // Appearance Methods
    public function saveAppearance()
    {
        $this->validate([
            'theme_color' => 'required|string',
            'secondary_color' => 'required|string',
        ]);

        // Simpan ke database jika ada method set di AppSetting
        AppSetting::updateOrCreate(
            ['key' => 'theme_color'],
            [
                'value' => $this->theme_color,
                'group' => 'appearance',
                'type' => 'text',
                'label' => 'Theme Primary Color',
                'description' => 'Warna utama tampilan depan'
            ]
        );

        AppSetting::updateOrCreate(
            ['key' => 'secondary_color'],
            [
                'value' => $this->secondary_color,
                'group' => 'appearance',
                'type' => 'text',
                'label' => 'Theme Secondary/Background Color',
                'description' => 'Warna latar belakang (tint) untuk elemen sekunder'
            ]
        );
        
        // Hapus cache agar perubahan langsung terlihat
        \Illuminate\Support\Facades\Cache::forget('app_setting_theme_color');
        \Illuminate\Support\Facades\Cache::forget('app_setting_secondary_color');

        session()->flash('success', 'Pengaturan tampilan berhasil diperbarui.');
    }

    public function resetThemeColor()
    {
        $this->theme_color = $this->default_theme_color;
        $this->secondary_color = $this->default_secondary_color;
        
        AppSetting::updateOrCreate(
            ['key' => 'theme_color'],
            [
                'value' => $this->default_theme_color,
                'group' => 'appearance',
                'type' => 'text',
                'label' => 'Theme Primary Color',
                'description' => 'Warna utama tampilan depan'
            ]
        );

        AppSetting::updateOrCreate(
            ['key' => 'secondary_color'],
            [
                'value' => $this->default_secondary_color,
                'group' => 'appearance',
                'type' => 'text',
                'label' => 'Theme Secondary/Background Color',
                'description' => 'Warna latar belakang (tint) untuk elemen sekunder'
            ]
        );
        
        \Illuminate\Support\Facades\Cache::forget('app_setting_theme_color');
        \Illuminate\Support\Facades\Cache::forget('app_setting_secondary_color');
        
        session()->flash('success', 'Warna tema dikembalikan ke default.');
    }
}
