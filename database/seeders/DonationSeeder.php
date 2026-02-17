<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\User;
use App\Models\Program;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'siti.nurhaliza@email.com')->first();
        $programs = Program::all();

        if (!$user || $programs->isEmpty()) return;

        $donations = [
            [
                'program_id' => $programs[0]->id, // Bangun Sekolah
                'amount' => 500000,
                'status' => 'success', // 'berhasil' in view, mapped to 'success'
                'payment_method' => 'Transfer BCA',
                'created_at' => '2026-01-12 14:30:00',
            ],
            [
                'program_id' => $programs[1]->id, // Klinik Gratis
                'amount' => 250000,
                'status' => 'pending',
                'payment_method' => 'Gopay',
                'created_at' => '2026-01-10 09:15:00',
            ],
            [
                'program_id' => $programs[2]->id, // Sumur Air
                'amount' => 1000000,
                'status' => 'success',
                'payment_method' => 'Virtual Account BNI',
                'created_at' => '2026-01-08 16:45:00',
            ],
            [
                'program_id' => $programs[3]->id, // Santunan Bulanan
                'amount' => 300000,
                'status' => 'failed', // 'gagal' in view, mapped to 'failed'
                'payment_method' => 'QRIS',
                'created_at' => '2026-01-03 08:00:00',
            ],
        ];

        foreach ($donations as $i => $data) {
            Donation::create([
                'transaction_id' => 'TRX-2026-001-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'program_id' => $data['program_id'],
                'amount' => $data['amount'],
                'total' => $data['amount'], // Assuming 0 admin fee for seeder
                'donor_name' => $user->name,
                'donor_phone' => $user->phone,
                'donor_email' => $user->email,
                'payment_method' => $data['payment_method'],
                'status' => $data['status'],
                'created_at' => $data['created_at'],
            ]);
        }
    }
}
