<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_holder_name' => 'Yayasan Peduli',
                'icon' => 'https://storage.googleapis.com/uxpilot-auth.appspot.com/bank-bca.png',
                'sort_order' => 1,
            ],
            [
                'bank_name' => 'Mandiri',
                'account_number' => '1234567890',
                'account_holder_name' => 'Yayasan Peduli',
                'icon' => 'https://storage.googleapis.com/uxpilot-auth.appspot.com/bank-mandiri.png',
                'sort_order' => 2,
            ],
            [
                'bank_name' => 'BRI',
                'account_number' => '1234567890',
                'account_holder_name' => 'Yayasan Peduli',
                'icon' => 'https://storage.googleapis.com/uxpilot-auth.appspot.com/bank-bri.png',
                'sort_order' => 3,
            ],
        ];

        foreach ($banks as $bank) {
            BankAccount::create($bank);
        }
    }
}
