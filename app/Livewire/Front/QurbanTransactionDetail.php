<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\QurbanOrder;
use Illuminate\Support\Facades\Auth;

class QurbanTransactionDetail extends Component
{
    public $transactionId;
    public $order;

    public function mount($id)
    {
        $this->transactionId = $id;
        $this->order = QurbanOrder::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['animal', 'documentations'])
            ->firstOrFail();
    }

    #[Layout('layouts.front')]
    #[Title('Detail Transaksi Qurban')]
    public function render()
    {
        return view('livewire.front.qurban-transaction-detail');
    }
}
