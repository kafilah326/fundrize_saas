<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\QurbanSaving;
use Illuminate\Support\Facades\Auth;

class QurbanSavingsDetail extends Component
{
    public $savingsId;
    public $saving;

    public function mount($id)
    {
        $this->savingsId = $id;
        $this->saving = QurbanSaving::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('documentations')
            ->firstOrFail();
    }

    #[Layout('layouts.front')]
    #[Title('Detail Tabungan Qurban')]
    public function render()
    {
        return view('livewire.front.qurban-savings-detail');
    }
}
