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
    public $isBankModalOpen = false;

    // API Fields
    // StarSender logic moved to separate component
    public $xendit_secret_key;
    public $xendit_webhook_token;

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
        $this->is_active = $bank->is_active;
        $this->isBankModalOpen = true;
    }

    public function saveBank()
    {
        $this->validate([
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_holder_name' => 'required|string',
        ]);

        BankAccount::updateOrCreate(
            ['id' => $this->bankId],
            [
                'bank_name' => $this->bank_name,
                'account_number' => $this->account_number,
                'account_holder_name' => $this->account_holder_name,
                'is_active' => $this->is_active,
            ]
        );

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
}
