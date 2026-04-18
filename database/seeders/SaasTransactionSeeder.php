<?php

namespace Database\Seeders;

use App\Models\SaasTransaction;
use App\Models\MaintenanceFee;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class SaasTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::with('plan')->get();

        foreach ($tenants as $tenant) {
            // 1. Seed Registration Payment (for paid plans)
            if ($tenant->plan->price > 0) {
                SaasTransaction::create([
                    'tenant_id' => $tenant->id,
                    'external_id' => 'REG-' . time() . '-' . $tenant->id,
                    'reference' => 'REF-' . strtoupper(bin2hex(random_bytes(4))),
                    'type' => 'registration',
                    'amount' => $tenant->plan->price,
                    'status' => 'paid',
                    'payment_method' => 'VA_MANDIRI',
                    'paid_at' => $tenant->created_at->addMinutes(15),
                ]);
            }

            // 2. Seed Maintenance Fee History (Last 3 months)
            for ($i = 1; $i <= 3; $i++) {
                $date = now()->subMonths($i);
                $amount = 1000000; // Simulated collected amount
                $feeAmount = ($amount * $tenant->plan->system_fee_percentage) / 100;

                $externalId = "MNT-SIM-{$tenant->id}-{$date->year}-{$date->month}";
                
                // Create the record in saas_transactions
                SaasTransaction::create([
                    'tenant_id' => $tenant->id,
                    'external_id' => $externalId,
                    'reference' => 'REF-' . strtoupper(bin2hex(random_bytes(4))),
                    'type' => 'maintenance',
                    'amount' => $feeAmount,
                    'status' => 'paid',
                    'payment_method' => 'QRIS',
                    'paid_at' => $date->endOfMonth(),
                    'metadata' => [
                        'year' => $date->year,
                        'month' => $date->month,
                    ],
                ]);

                // Create the record in maintenance_fees (SaaS billing logic)
                MaintenanceFee::create([
                    'tenant_id' => $tenant->id,
                    'year' => $date->year,
                    'month' => $date->month,
                    'total_amount' => $amount,
                    'fee_amount' => $feeAmount,
                    'status' => 'verified',
                    'paid_at' => $date->endOfMonth(),
                ]);
            }
        }
    }
}
