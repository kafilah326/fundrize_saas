<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\TenantDomain;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $plans = Plan::all();
        
        if ($plans->isEmpty()) {
            $this->call(PlanSeeder::class);
            $plans = Plan::all();
        }

        $tenants = [
            [
                'name' => 'Yayasan Amal Jariyah',
                'slug' => 'amal-jariyah',
                'email' => 'admin@amaljariyah.or.id',
                'phone' => '081234567890',
                'status' => 'active',
                'plan_slug' => 'starter',
            ],
            [
                'name' => 'Lembaga Pendidikan Islam',
                'slug' => 'lpi-pusat',
                'email' => 'admin@lpipusat.com',
                'phone' => '081222333444',
                'status' => 'active',
                'plan_slug' => 'pro',
            ],
            [
                'name' => 'Dompet Kemanusiaan Global',
                'slug' => 'dkg-intl',
                'email' => 'admin@dkg-intl.org',
                'phone' => '081999888777',
                'status' => 'active',
                'plan_slug' => 'premium',
            ],
        ];

        foreach ($tenants as $t) {
            $plan = $plans->where('slug', $t['plan_slug'])->first();
            
            $tenant = Tenant::updateOrCreate(['slug' => $t['slug']], [
                'name' => $t['name'],
                'email' => $t['email'],
                'phone' => $t['phone'],
                'status' => $t['status'],
                'plan_id' => $plan->id,
            ]);

            // Create Subdomain
            TenantDomain::updateOrCreate(['tenant_id' => $tenant->id, 'type' => 'subdomain'], [
                'domain' => $t['slug'],
                'is_primary' => true,
                'dns_verified' => true,
            ]);

            // If Premium, add a sample custom domain
            if ($t['plan_slug'] === 'premium') {
                TenantDomain::updateOrCreate(['tenant_id' => $tenant->id, 'type' => 'custom'], [
                    'domain' => 'donasi.dkg-intl.org',
                    'is_primary' => false,
                    'dns_verified' => true,
                    'verified_at' => now(),
                ]);
            }

            // Create Tenant Admin
            User::updateOrCreate(['email' => $t['email']], [
                'tenant_id' => $tenant->id,
                'name' => 'Admin ' . $tenant->name,
                'phone' => $t['phone'],
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
