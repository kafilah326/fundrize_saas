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
    public $social_facebook, $social_instagram, $social_whatsapp, $social_youtube;
    // Note: Focus Areas handled as simple text/array logic if needed, simplified for now

    // Bank Account Fields
    // public $bankAccounts; // Removed: Passed directly to view for pagination
    public $bankId;
    public $bank_name, $account_number, $account_holder_name, $is_active = true;
    public $bank_icon; // temporary file upload
    public $existingBankIcon; // stored path
    public $isBankModalOpen = false;

    // API Fields
    // StarSender logic moved to separate component
    public $payment_gateway = 'xendit';
    
    public $xendit_mode = 'test';
    public $xendit_secret_key;
    public $xendit_webhook_token;
    
    public $pakasir_mode = 'sandbox';
    public $pakasir_slug;
    public $pakasir_api_key;

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

            $socialMedia = is_string($foundation->social_media) ? json_decode($foundation->social_media, true) : $foundation->social_media;
            if (is_array($socialMedia)) {
                $this->social_facebook = $socialMedia['facebook'] ?? '';
                $this->social_instagram = $socialMedia['instagram'] ?? '';
                $this->social_whatsapp = $socialMedia['whatsapp'] ?? '';
                $this->social_youtube = $socialMedia['youtube'] ?? '';
            }
        }

        // Load API Settings
        $this->payment_gateway = AppSetting::get('payment_gateway', 'xendit');
        
        $this->xendit_mode = AppSetting::get('xendit_mode', 'test');
        $this->xendit_secret_key = AppSetting::get('xendit_secret_key');
        $this->xendit_webhook_token = AppSetting::get('xendit_webhook_token');
        
        $this->pakasir_mode = AppSetting::get('pakasir_mode', 'sandbox');
        $this->pakasir_slug = AppSetting::get('pakasir_slug');
        $this->pakasir_api_key = AppSetting::get('pakasir_api_key');

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
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico,webp|dimensions:ratio=1/1|max:1024',
        ], [
            'favicon.dimensions' => 'Favicon harus berbentuk persegi (1:1). Rekomendasi: 512x512 px.'
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
            'social_media' => [
                'facebook' => $this->social_facebook,
                'instagram' => $this->social_instagram,
                'whatsapp' => $this->social_whatsapp,
                'youtube' => $this->social_youtube,
            ],
        ];

        if ($this->logo) {
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->read($this->logo->getRealPath());
            $processed = $image->cover(1200, 630)->toJpeg(85);
            $filename = 'foundation/' . uniqid() . '.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $processed);
            
            $foundation = FoundationSetting::first();
            if ($foundation) {
                $oldPath = $foundation->getRawOriginal('logo');
                if ($oldPath && !str_starts_with($oldPath, 'http')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
            $data['logo'] = $filename;
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
            'bank_icon' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        $data = [
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_holder_name' => $this->account_holder_name,
            'is_active' => $this->is_active,
        ];

        if ($this->bank_icon) {
            $iconPath = $this->bank_icon->store('bank-icons', 'public');
            $data['icon'] = $iconPath;
        }

        if ($this->bankId) {
            BankAccount::where('id', $this->bankId)->update($data);
        } else {
            BankAccount::create($data);
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
            'payment_gateway' => 'required|in:xendit,pakasir',
            'xendit_mode' => 'nullable|in:test,live',
            'xendit_secret_key' => 'nullable|string',
            'xendit_webhook_token' => 'nullable|string',
            'pakasir_mode' => 'nullable|in:sandbox,live',
            'pakasir_slug' => 'nullable|string',
            'pakasir_api_key' => 'nullable|string',
        ]);

        // Simpan Payment Gateway
        AppSetting::updateOrCreate(
            ['key' => 'payment_gateway'],
            [
                'value' => $this->payment_gateway,
                'group' => 'general',
                'type' => 'text',
                'label' => 'Payment Gateway',
                'description' => 'Gateway pembayaran yang aktif (xendit atau pakasir)',
            ]
        );
        \Illuminate\Support\Facades\Cache::forget('app_setting_payment_gateway');

        // Simpan mode Xendit
        AppSetting::updateOrCreate(
            ['key' => 'xendit_mode'],
            [
                'value' => $this->xendit_mode,
                'group' => 'xendit',
                'type' => 'text',
                'label' => 'Xendit Mode',
                'description' => 'Mode environment Xendit: test (sandbox) atau live (production)',
            ]
        );
        \Illuminate\Support\Facades\Cache::forget('app_setting_xendit_mode');

        AppSetting::updateOrCreate(
            ['key' => 'xendit_secret_key'],
            [
                'value' => $this->xendit_secret_key,
                'group' => 'xendit',
                'type' => 'text',
                'label' => 'Xendit Secret Key',
                'description' => 'API Key dari dashboard Xendit',
            ]
        );
        \Illuminate\Support\Facades\Cache::forget('app_setting_xendit_secret_key');

        AppSetting::updateOrCreate(
            ['key' => 'xendit_webhook_token'],
            [
                'value' => $this->xendit_webhook_token,
                'group' => 'xendit',
                'type' => 'text',
                'label' => 'Xendit Webhook Token',
                'description' => 'Webhook token dari dashboard Xendit',
            ]
        );
        \Illuminate\Support\Facades\Cache::forget('app_setting_xendit_webhook_token');

        // Simpan mode Pakasir
        AppSetting::updateOrCreate(
            ['key' => 'pakasir_mode'],
            [
                'value' => $this->pakasir_mode,
                'group' => 'pakasir',
                'type' => 'text',
                'label' => 'Pakasir Mode',
                'description' => 'Mode environment Pakasir: sandbox atau live',
            ]
        );
        \Illuminate\Support\Facades\Cache::forget('app_setting_pakasir_mode');

        AppSetting::updateOrCreate(
            ['key' => 'pakasir_slug'],
            [
                'value' => $this->pakasir_slug,
                'group' => 'pakasir',
                'type' => 'text',
                'label' => 'Pakasir Slug',
                'description' => 'Slug project di Pakasir',
            ]
        );
        \Illuminate\Support\Facades\Cache::forget('app_setting_pakasir_slug');

        AppSetting::updateOrCreate(
            ['key' => 'pakasir_api_key'],
            [
                'value' => $this->pakasir_api_key,
                'group' => 'pakasir',
                'type' => 'text',
                'label' => 'Pakasir API Key',
                'description' => 'API Key dari dashboard Pakasir',
            ]
        );
        \Illuminate\Support\Facades\Cache::forget('app_setting_pakasir_api_key');

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
