<?php

namespace App\Livewire\Front;

use App\Models\Program;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FundraiserPrograms extends Component
{
    public $user;
    public $fundraiser;

    public function mount()
    {
        $this->user = Auth::user()->load('fundraiser');
        $this->fundraiser = $this->user->fundraiser;

        if (!$this->fundraiser || $this->fundraiser->status !== 'approved') {
            return redirect()->route('profile.index');
        }
    }

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
}