<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\Program;
use App\Models\User;

class Dashboard extends Component
{
    use WithPagination;

    public function render()
    {
        // Stats
        $totalDonations = Payment::where('status', 'paid')->sum('amount');
        $activePrograms = Program::where('is_active', true)->count();
        $totalDonors = User::where('role', 'user')->count(); 
        
        // Recent Donations
        $recentDonations = Payment::with('program')
            ->where('status', 'paid')
            ->whereIn('transaction_type', ['program', 'donation']) // Handle both potential types
            ->latest()
            ->paginate(5, ['*'], 'donationsPage');

        // Top Programs
        $topPrograms = Program::where('is_active', true)
            ->orderByDesc('collected_amount')
            ->paginate(5, ['*'], 'programsPage');

        return view('livewire.admin.dashboard', [
            'totalDonations' => $totalDonations,
            'activePrograms' => $activePrograms,
            'totalDonors' => $totalDonors,
            'recentDonations' => $recentDonations,
            'topPrograms' => $topPrograms
        ])->layout('layouts.admin');
    }
}
