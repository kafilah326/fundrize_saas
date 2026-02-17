<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;

class MyDonation extends Component
{
    public $filter = 'semua';
    public $donations;

    public function setFilter($status)
    {
        $this->filter = $status;
    }

    #[Layout('layouts.front')]
    #[Title('Donasi Saya')]
    public function render()
    {
        $query = Donation::where('user_id', Auth::id())->with('program')->latest();

        if ($this->filter !== 'semua') {
            // Map view filter to DB status if needed, assuming direct mapping for now
            // 'berhasil' -> 'success', 'pending' -> 'pending', 'gagal' -> 'failed'
            $statusMap = [
                'berhasil' => 'success',
                'pending' => 'pending',
                'gagal' => 'failed',
            ];
            $query->where('status', $statusMap[$this->filter]);
        }

        $this->donations = $query->get();

        return view('livewire.front.my-donation');
    }
}
