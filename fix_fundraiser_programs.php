<?php
$file = 'app/Livewire/Front/FundraiserPrograms.php';
$content = file_get_contents($file);

$searchRender = <<<'EOL'
    #[Layout('layouts.front')]
    #[Title('Program Ber-Ujroh')]
    public function render()
    {
        $programs = Program::where('is_active', true)
            ->where('commission_type', '!=', 'none')
            ->latest()
            ->get();

        return view('livewire.front.fundraiser-programs', [
            'programs' => $programs
        ]);
    }
EOL;

$replaceRender = <<<'EOL'
    #[Layout('layouts.front')]
    #[Title('Program Ber-Ujroh')]
    public function render()
    {
        $commType = \App\Models\AppSetting::get('fundraiser_program_commission_type', 'none');
        $commAmount = \App\Models\AppSetting::get('fundraiser_program_commission_amount', 0);

        // If global setting is none, don't show any programs
        $programs = [];
        if ($commType !== 'none') {
            $programs = Program::where('is_active', true)
                ->latest()
                ->get();
        }

        return view('livewire.front.fundraiser-programs', [
            'programs' => $programs,
            'commType' => $commType,
            'commAmount' => $commAmount,
        ]);
    }
EOL;

$content = str_replace($searchRender, $replaceRender, $content);
file_put_contents($file, $content);
echo "Fixed FundraiserPrograms.php\n";
