<?php

namespace App\Livewire\Front;

use App\Models\FundraiserCommission;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FundraiserHistory extends Component
{
    use WithPagination;

    public $fundraiser;
    public $filter = 'all'; // all, month, week, today

    public function mount()
    {
        $user = Auth::user()->load('fundraiser');
        $this->fundraiser = $user->fundraiser;

        if (!$this->fundraiser || $this->fundraiser->status !== 'approved') {
            return redirect()->route('profile.index');
        }
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    #[Layout('layouts.front')]
    #[Title('Riwayat Ujroh')]
    public function render()
    {
        // Hitung total ujroh (semua yg success)
        $totalUjroh = FundraiserCommission::where('fundraiser_id', $this->fundraiser->id)
            ->where('status', 'success')
            ->sum('amount');

        // Hitung ujroh yang sudah dicairkan (withdrawals yg approved)
        $withdrawnUjroh = $this->fundraiser->withdrawals()
            ->where('status', 'approved')
            ->sum('amount');

        // Saldo saat ini
        $availableBalance = $this->fundraiser->available_balance;

        // Query commissions
        $query = FundraiserCommission::with(['commissionable'])
            ->where('fundraiser_id', $this->fundraiser->id)
            ->where('status', 'success');

        if ($this->filter === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($this->filter === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]);
        } elseif ($this->filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        }

        $commissions = $query->latest()->paginate(10);

        return view('livewire.front.fundraiser-history', [
            'commissions' => $commissions,
            'totalUjroh' => $totalUjroh,
            'withdrawnUjroh' => $withdrawnUjroh,
            'availableBalance' => $availableBalance,
        ]);
    }
}
