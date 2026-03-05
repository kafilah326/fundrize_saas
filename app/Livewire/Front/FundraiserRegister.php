<?php

namespace App\Livewire\Front;

use App\Models\Fundraiser;
use App\Models\FoundationSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FundraiserRegister extends Component
{
    public $name = '';
    public $whatsapp = '';
    public $email = '';
    public $address = '';
    public $domicile = '';

    public function mount()
    {
        $user = Auth::user()->load('fundraiser');
        
        // Prevent if already pending or approved
        if ($user->fundraiser && in_array($user->fundraiser->status, ['pending', 'approved'])) {
            return redirect()->route('profile.index');
        }

        $this->name = $user->name;
        $this->whatsapp = $user->phone ?? '';
        $this->email = $user->email;
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'domicile' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        
        // Generate base referral code from foundation name
        $foundationName = FoundationSetting::first()->name ?? 'Yayasan';
        $words = explode(' ', trim($foundationName));
        $baseCode = '';
        
        if (count($words) == 1) {
            $baseCode = substr($words[0], 0, 3);
        } else {
            $baseCode = substr($words[0], 0, 2) . substr($words[1], 0, 1);
        }
        $baseCode = strtoupper($baseCode);

        // Check if user already has a fundraiser record
        $existingFundraiser = Fundraiser::where('user_id', $user->id)->first();
        
        // Only generate new referral code if creating new or if existing doesn't have one
        $referralCode = $existingFundraiser->referral_code ?? null;
        
        if (!$referralCode) {
            do {
                $randomStr = strtoupper(Str::random(4));
                $referralCode = $baseCode . $randomStr;
            } while (Fundraiser::where('referral_code', $referralCode)->exists());
        }

        Fundraiser::updateOrCreate(
            ['user_id' => $user->id],
            [
                'referral_code' => $referralCode,
                'name' => $this->name,
                'whatsapp' => $this->whatsapp,
                'email' => $this->email,
                'address' => $this->address,
                'domicile' => $this->domicile,
                'status' => 'pending',
                'rejected_reason' => null,
            ]
        );

        session()->flash('success', 'Pendaftaran berhasil! Silakan tunggu konfirmasi dari admin.');
        return redirect()->route('profile.index');
    }

    #[Layout('layouts.front')]
    #[Title('Jadi Fundriser')]
    public function render()
    {
        return view('livewire.front.fundraiser-register');
    }
}
