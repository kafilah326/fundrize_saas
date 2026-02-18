<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\QurbanOrder;
use App\Models\QurbanSaving;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasDepositModal;

class QurbanHistory extends Component
{
    use HasDepositModal;

    public $orders;
    public $savings;

    public function mount()
    {
        $user = Auth::user();
        $this->orders = QurbanOrder::where('user_id', $user->id)
            ->with('animal')
            ->latest()
            ->get();
            
        $this->savings = QurbanSaving::where('user_id', $user->id)
            ->with(['deposits' => function($q) {
                $q->latest();
            }])
            ->first(); // Assuming single active saving for now
    }

    #[Layout('layouts.front')]
    #[Title('Qurban')]
    public function render()
    {
        return view('livewire.front.qurban-history');
    }
}
