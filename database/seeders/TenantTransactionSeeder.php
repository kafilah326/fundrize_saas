<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Tenant;
use App\Models\ZakatTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TenantTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();
        $methods = ['VA_MANDIRI', 'VA_BNI', 'QRIS', 'OVO', 'VA_BRI'];
        
        foreach ($tenants as $tenant) {
            $programs = Program::where('tenant_id', $tenant->id)->get();
            
            foreach ($programs as $program) {
                // Generate 15-25 donations per program
                $numDonations = rand(15, 25);
                $totalCollected = 0;
                $donorCount = 0;

                for ($i = 0; $i < $numDonations; $i++) {
                    $amount = rand(5, 50) * 10000; // 50k to 500k
                    $adminFee = 0;
                    $total = $amount + $adminFee;
                    $date = Carbon::now()->subDays(rand(1, 90));
                    $externalId = 'DON-' . strtoupper(Str::random(10));

                    // 1. Create Payment
                    Payment::create([
                        'tenant_id' => $tenant->id,
                        'external_id' => $externalId,
                        'transaction_type' => 'program',
                        'payment_type' => 'xendit', // Added required field
                        'program_id' => $program->id,
                        'amount' => $amount,
                        'admin_fee' => $adminFee,
                        'total' => $total,
                        'payment_method' => $methods[array_rand($methods)],
                        'status' => 'paid',
                        'customer_name' => 'Donatur Terhormat ' . ($i + 1),
                        'customer_email' => 'donatur' . ($i + 1) . '@example.com',
                        'paid_at' => $date,
                    ]);

                    // 2. Create Donation
                    Donation::create([
                        'tenant_id' => $tenant->id,
                        'transaction_id' => $externalId,
                        'program_id' => $program->id,
                        'amount' => $amount,
                        'admin_fee' => $adminFee,
                        'total' => $total,
                        'donor_name' => 'Donatur Terhormat ' . ($i + 1),
                        'donor_email' => 'donatur' . ($i + 1) . '@example.com',
                        'status' => 'success', // Changed from 'paid' to 'success'
                        'created_at' => $date,
                    ]);

                    $totalCollected += $amount;
                    $donorCount++;
                }

                // Update Program totals
                $program->update([
                    'collected_amount' => $totalCollected,
                    'donor_count' => $donorCount,
                ]);
            }

            // Seed some Zakat for each tenant
            for ($j = 0; $j < 10; $j++) {
                $amount = rand(1, 10) * 100000;
                $date = Carbon::now()->subDays(rand(1, 60));
                $externalId = 'ZAK-' . strtoupper(Str::random(10));

                Payment::create([
                    'tenant_id' => $tenant->id,
                    'external_id' => $externalId,
                    'transaction_type' => 'zakat',
                    'payment_type' => 'xendit', // Added required field
                    'amount' => $amount,
                    'total' => $amount,
                    'payment_method' => $methods[array_rand($methods)],
                    'status' => 'paid',
                    'customer_name' => 'Muzzaki ' . ($j + 1),
                    'paid_at' => $date,
                ]);

                ZakatTransaction::create([
                    'tenant_id' => $tenant->id,
                    'transaction_id' => $externalId,
                    'zakat_type' => $j % 2 === 0 ? 'fitrah' : 'maal',
                    'amount' => $amount,
                    'total' => $amount,
                    'donor_name' => 'Muzzaki ' . ($j + 1),
                    'status' => 'success', // Changed from 'paid' to 'success'
                    'created_at' => $date,
                ]);
            }
        }
    }
}
