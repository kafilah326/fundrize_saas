<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use App\Models\Program;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Stats
        $totalDonations = Payment::where('status', 'paid')->sum(DB::raw('amount + COALESCE(unique_code, 0)'));
        $activePrograms = Program::where('is_active', true)->count();
        $totalDonors = User::where('role', 'user')->count();

        // Bar Chart Data (Transaction Totals from 1st of current month to today)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfToday = Carbon::now()->endOfDay();

        // Debugging: Log the query parameters
        // info('Start: ' . $startOfMonth->toDateTimeString());
        // info('End: ' . $endOfToday->toDateTimeString());

        $dailyTransactions = Payment::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(amount + COALESCE(unique_code, 0)) as total')
        )
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startOfMonth, $endOfToday])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Convert to key-value pair for easier lookup
        $dailyData = [];
        foreach ($dailyTransactions as $trx) {
            $dailyData[$trx->date] = $trx->total;
        }

        // Prepare chart data array
        $chartData = [];
        $currentDate = $startOfMonth->copy();
        $today = Carbon::now();

        while ($currentDate->format('Y-m-d') <= $today->format('Y-m-d')) {
            $dateString = $currentDate->format('Y-m-d');
            $chartData[] = [
                'date' => $currentDate->format('d M'),
                'total' => isset($dailyData[$dateString]) ? (float) $dailyData[$dateString] : 0,
            ];
            $currentDate->addDay();
        }

        // Today's Transactions
        $todayTransactions = Payment::with(['program', 'user', 'qurbanOrder', 'qurbanSaving'])
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get();

        return view('livewire.admin.dashboard', [
            'totalDonations' => $totalDonations,
            'activePrograms' => $activePrograms,
            'totalDonors' => $totalDonors,
            'chartData' => $chartData,
            'todayTransactions' => $todayTransactions,
        ])->layout('layouts.admin');
    }
}
