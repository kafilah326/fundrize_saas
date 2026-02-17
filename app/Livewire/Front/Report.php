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

        // Financial Summary Logic (Personalized)
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Get user donations in this period
        $userDonations = Donation::where('user_id', auth()->id())
            ->where('status', 'success')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with(['program.distributions'])
            ->get();

        $danaMasuk = $userDonations->sum('amount');
        
        // Calculate "Tersalurkan" as the proportional impact of the user's donation
        // Logic: (User Donation / Total Collected) * Total Distributed
        $tersalurkan = 0;
        foreach ($userDonations as $donation) {
            if ($donation->program && $donation->program->collected_amount > 0) {
                // Get total distributed for this program (all time or period? 
                // Context: Report is per period. If I donate in Jan, and distribution happens in Feb, 
                // my Jan report should show my donation. 
                // If I check Feb report, my donation is 0. 
                // So "Tersalurkan" in Jan report should logically reflect distributions *happening* in Jan 
                // that were funded by available money? 
                // OR should it reflect the *eventual* distribution of THAT money?
                // The prompt says "tampilkan report yang telah user donasikan saja" (Show reports of what user donated).
                // And "cardnya juga anjing" (The cards are also [messy/wrong]).
                // The previous implementation utilized $startOfMonth and $endOfMonth for distributions.
                // Let's stick to the Period-based view for consistency with the dropdown.
                // So: Distribution *in this period* for programs I supported *in this period*? 
                // Or programs I supported *ever*?
                // If I donated last month, and distribution is this month, should I see it?
                // The current page filters "Program Reports" by:
                // Program::whereHas('distributions', in_period).
                // AND whereHas('donations', user_id).
                // So it shows programs I donated to (ever?) that have activity in this period.
                // WAIT. My previous filter query was:
                // $query->whereHas('donations', function ($q) { $q->where('user_id', auth()->id())...});
                // This finds programs I donated to.
                // AND the base query has `whereHas('distributions', range)`.
                // So it displays programs I supported that have distributions THIS month.
                // SO the financial summary should reflect THAT.
                
                // Let's refine the logic for "Tersalurkan" to match the visual list.
                // The list shows "Dana Tersalurkan" for the program in that period.
                // If the user wants the cards to match the list's *context*:
                // Dana Masuk: Sum of MY donations in this period.
                // Tersalurkan: Sum of (My Share of Distribution) for distributions in this period?
                // This is getting complicated.
                // Simpler interpretation of "cardnya juga":
                // Just sum up the "Dana Tersalurkan" of the programs shown in the list?
                // But that might be huge (Program Total) vs "Dana Masuk" (User Total).
                // If "Dana Masuk" is MY money, "Tersalurkan" should be related to MY money.
                
                // Let's stick to the approved plan's logic (User Donations in Period -> Proportional Distribution).
                // Caveat: If distribution happened effectively *before* donation in same month, or whatever, we ignore time-travel complexity.
                // We use the program's *current* total collected/distributed state to estimate ratio.
                
                $totalDistributedProgram = $donation->program->distributions->sum('amount_distributed');
                $ratio = $donation->program->collected_amount > 0 ? ($totalDistributedProgram / $donation->program->collected_amount) : 0;
                
                // Capping ratio at 1 (100%) to avoid weirdness if data is messy
                $ratio = min(1, $ratio);
                
                $tersalurkan += $donation->amount * $ratio;
            }
        }
        
        // Operational Cost (Assumed 5% of donation)
        $biayaOperasional = $danaMasuk * 0.05; 
        
        // Sisa Dana = What is left of MY donation that hasn't been "distributed" (conceptually)
        $sisaDana = $danaMasuk - $tersalurkan - $biayaOperasional;
        
        // Prevent negative sisa dana due to rounding or logic mismatch
        $sisaDana = max(0, $sisaDana);

        $this->financials = [
            'dana_masuk' => $danaMasuk,
            'tersalurkan' => $tersalurkan,
            'sisa_dana' => $sisaDana,
            'biaya_operasional' => $biayaOperasional
        ];

        // Program Reports
        $query = Program::whereHas('distributions', function($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('documentation_date', [$startOfMonth, $endOfMonth]);
        })->with(['categories', 'akads', 'distributions' => function($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('documentation_date', [$startOfMonth, $endOfMonth]);
        }]);

        // Filter programs by user donations
        $query->whereHas('donations', function($q) {
            $q->where('user_id', auth()->id())
              ->where('status', 'success');
        });

        if ($this->filter !== 'semua') {
            $query->whereHas('akads', function($q) {
                $q->where('slug', $this->filter);
            });
        }

        $this->programs = $query->get();

        return view('livewire.front.report');
    }
}
