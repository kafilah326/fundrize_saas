<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OtpMail;

class ForgotPassword extends Component
{
    public $step = 1; // 1: Email, 2: OTP, 3: New Password
    public $email = '';
    public $otp = '';
    public $otp1 = '';
    public $otp2 = '';
    public $otp3 = '';
    public $otp4 = '';
    public $otp5 = '';
    public $otp6 = '';
    public $password = '';
    public $password_confirmation = '';
    
    protected $messages = [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.exists' => 'Email tidak terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'otp')) {
            $this->otp = $this->otp1 . $this->otp2 . $this->otp3 . $this->otp4 . $this->otp5 . $this->otp6;
        }
    }

    public function sendOtp()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $this->email)->first();
        
        // Generate 6 digit numeric code
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->otp_code = $code;
        $user->otp_expires_at = Carbon::now()->addMinutes(15);
        $user->save();

        try {
            Mail::to($this->email)->send(new OtpMail($code));
            $this->step = 2;
        } catch (\Exception $e) {
            $this->addError('email', 'Gagal mengirim email. Silakan coba lagi.');
        }
    }

    public function verifyOtp()
    {
        $this->otp = $this->otp1 . $this->otp2 . $this->otp3 . $this->otp4 . $this->otp5 . $this->otp6;
        
        if (strlen($this->otp) !== 6) {
            $this->addError('otp', 'Masukkan 6 digit kode OTP.');
            return;
        }

        $user = User::where('email', $this->email)->first();

        if (!$user || $user->otp_code !== $this->otp) {
            $this->addError('otp', 'Kode OTP salah.');
            return;
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            $this->addError('otp', 'Kode OTP sudah kadaluarsa. Silakan minta ulang.');
            return;
        }

        // Proceed to next step
        $this->step = 3;
    }

    public function resetPassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::where('email', $this->email)->first();
        $user->password = Hash::make($this->password);
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        session()->flash('success', 'Password berhasil direset. Silakan login.');
        return redirect()->route('login');
    }

    #[Layout('layouts.front')]
    #[Title('Lupa Password')]
    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
