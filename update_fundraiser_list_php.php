<?php
$file = 'app/Livewire/Admin/FundraiserList.php';
$content = file_get_contents($file);

// 1. Add properties
$props = "    // Modal Commission\n    public \$isCommissionModalOpen = false;\n    public \$selectedFundraiserCommissions = [];\n    public \$selectedFundraiserName = '';\n\n    // Settings\n    public \$program_commission_type = 'none';\n    public \$program_commission_amount = 0;\n    public \$qurban_commission_type = 'none';\n    public \$qurban_commission_amount = 0;\n";
$content = preg_replace('/(\s*\/\/ Modal Commission.*?\n    public \$selectedFundraiserName = \'\';\n)/s', $props, $content);

// 2. Add mount method to load settings
$mountMethod = "
    public function mount()
    {
        \$this->program_commission_type = \App\Models\AppSetting::get('fundraiser_program_commission_type', 'none');
        \$this->program_commission_amount = \App\Models\AppSetting::get('fundraiser_program_commission_amount', 0);
        \$this->qurban_commission_type = \App\Models\AppSetting::get('fundraiser_qurban_commission_type', 'none');
        \$this->qurban_commission_amount = \App\Models\AppSetting::get('fundraiser_qurban_commission_amount', 0);
    }
";

// Insert before setTab
$content = str_replace("    public function setTab(\$tab)", $mountMethod . "\n    public function setTab(\$tab)", $content);

// 3. Add saveSettings method
$saveSettings = "
    public function saveSettings()
    {
        \$this->validate([
            'program_commission_type' => 'required|in:none,fixed,percentage',
            'program_commission_amount' => 'required|numeric|min:0',
            'qurban_commission_type' => 'required|in:none,fixed,percentage',
            'qurban_commission_amount' => 'required|numeric|min:0',
        ]);

        \$settings = [
            'fundraiser_program_commission_type' => ['value' => \$this->program_commission_type, 'type' => 'string'],
            'fundraiser_program_commission_amount' => ['value' => \$this->program_commission_amount, 'type' => 'number'],
            'fundraiser_qurban_commission_type' => ['value' => \$this->qurban_commission_type, 'type' => 'string'],
            'fundraiser_qurban_commission_amount' => ['value' => \$this->qurban_commission_amount, 'type' => 'number'],
        ];

        foreach (\$settings as \$key => \$data) {
            \App\Models\AppSetting::updateOrCreate(
                ['key' => \$key],
                [
                    'value' => \$data['value'],
                    'type' => \$data['type'],
                    'group' => 'fundraiser',
                    'label' => ucwords(str_replace('_', ' ', \$key))
                ]
            );
            \Illuminate\Support\Facades\Cache::forget('app_setting_' . \$key);
        }

        session()->flash('success', 'Pengaturan Ujroh berhasil disimpan.');
    }
";

// Insert after closeCommissionModal
$content = str_replace("    public function closeCommissionModal()\n    {\n        \$this->isCommissionModalOpen = false;\n        \$this->selectedFundraiserCommissions = [];\n    }", "    public function closeCommissionModal()\n    {\n        \$this->isCommissionModalOpen = false;\n        \$this->selectedFundraiserCommissions = [];\n    }\n" . $saveSettings, $content);

file_put_contents($file, $content);
echo "Updated FundraiserList.php\n";
