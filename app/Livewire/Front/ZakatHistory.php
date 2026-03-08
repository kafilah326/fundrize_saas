<?php

namespace App\Livewire\Front;

use App\Models\ZakatTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ZakatHistory extends Component
{
    public $selectedYear = '';
    public $selectedType = '';
    public $years = [];
    public $stats = [
        'total_amount' => 0,
        'fitrah_count' => 0,
        'maal_count'   => 0,
        'total_count'  => 0,
    ];

    public function mount()
    {
        $this->years = ZakatTransaction::where('user_id', Auth::id())
            ->where('status', 'success')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $this->calculateStats();
    }

    public function calculateStats()
    {
        $query = ZakatTransaction::where('user_id', Auth::id())
            ->where('status', 'success');

        if ($this->selectedYear) {
            $query->whereYear('created_at', $this->selectedYear);
        }

        $successTrx = $query->get();

        $this->stats['total_amount'] = $successTrx->sum('total');
        $this->stats['fitrah_count'] = $successTrx->where('zakat_type', 'fitrah')->count();
        $this->stats['maal_count']   = $successTrx->where('zakat_type', 'maal')->count();
        $this->stats['total_count']  = $successTrx->count();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['selectedYear', 'selectedType'])) {
            $this->calculateStats();
        }
    }

    #[Layout('layouts.front')]
    #[Title('Zakat Saya')]
    public function render()
    {
        $query = ZakatTransaction::where('user_id', Auth::id())
            ->with('payment')
            ->latest();

        if ($this->selectedYear) {
            $query->whereYear('created_at', $this->selectedYear);
        }

        if ($this->selectedType) {
            $query->where('zakat_type', $this->selectedType);
        }

        $zakatTransactions = $query->get();

        return view('livewire.front.zakat-history', [
            'zakatTransactions' => $zakatTransactions
        ]);
    }
}
