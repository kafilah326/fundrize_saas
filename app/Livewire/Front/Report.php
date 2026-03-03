<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Program;
use App\Models\Donation;
use App\Models\ProgramDistribution;
use Carbon\Carbon;

use App\Models\AkadType;

class Report extends Component
{
    public $period;
    public $filter = 'semua';
    public $programs;
    public $financials;
    public $akadTypes;

    public function mount()
    {
        // Set default period to current month/year
        $this->period = now()->format('F Y');
        $this->akadTypes = AkadType::where('is_active', true)->get();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function formatRupiah($value)
    {
        $value = (float) $value;

        if ($value >= 1000000000) {
            return 'Rp ' . number_format($value / 1000000000, 1, ',', '.') . ' Milyar';
        } elseif ($value >= 1000000) {
            return 'Rp ' . number_format($value / 1000000, 1, ',', '.') . ' Juta';
        } elseif ($value >= 1000) {
            return 'Rp ' . number_format($value / 1000, 1, ',', '.') . ' Ribu';
        }

        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    #[Layout('layouts.front')]
    #[Title('Laporan')]
    public function render()
    {
        // Parse period
        try {
            $date = Carbon::createFromFormat('F Y', $this->period);
        } catch (\Exception $e) {
            $date = now();
        }

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Financial Summary (Global - all donations in this period)
        $danaMasuk = Donation::where('status', 'success')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Total distributed in this period
        $tersalurkan = ProgramDistribution::whereBetween('documentation_date', [$startOfMonth, $endOfMonth])
            ->sum('amount_distributed');

        // Operational Cost (5% of donations)
        $biayaOperasional = $danaMasuk * 0.05;

        // Remaining funds
        $sisaDana = max(0, $danaMasuk - $tersalurkan - $biayaOperasional);

        $this->financials = [
            'dana_masuk' => $danaMasuk,
            'tersalurkan' => $tersalurkan,
            'sisa_dana' => $sisaDana,
            'biaya_operasional' => $biayaOperasional
        ];

        // Program Reports - show all active programs with their distributions
        $query = Program::where('is_active', true)
            ->with(['categories', 'akads', 'distributions']);

        if ($this->filter !== 'semua') {
            $query->whereHas('akads', function($q) {
                $q->where('slug', $this->filter);
            });
        }

        $this->programs = $query->get();

        return view('livewire.front.report');
    }
}
