<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Paket dasar untuk yayasan kecil yang baru mulai go-digital.',
                'price' => 0,
                'system_fee_percentage' => 5.00,
                'is_active' => true,
                'sort_order' => 1,
                'features' => [
                    'donation' => true,
                    'zakat' => true,
                    'qurban' => false,
                    'whatsapp' => false,
                    'custom_domain' => false,
                    'fundraiser' => false,
                    'dynamic_program' => false,
                ],
                'limits' => [
                    'max_users' => 2,
                    'max_programs' => 3,
                    'storage_mb' => 250,
                ],
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Solusi lengkap untuk yayasan menengah dengan pertumbuhan donatur tinggi.',
                'price' => 150000,
                'system_fee_percentage' => 3.50,
                'is_active' => true,
                'sort_order' => 2,
                'features' => [
                    'donation' => true,
                    'zakat' => true,
                    'qurban' => true,
                    'whatsapp' => true,
                    'custom_domain' => false,
                    'fundraiser' => true,
                    'dynamic_program' => true,
                ],
                'limits' => [
                    'max_users' => 10,
                    'max_programs' => 20,
                    'storage_mb' => 2000,
                ],
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Manajemen total dengan branding kustom dan dukungan prioritas.',
                'price' => 500000,
                'system_fee_percentage' => 2.00, // Fee sistem lebih kecil untuk paket mahal
                'is_active' => true,
                'sort_order' => 3,
                'features' => [
                    'donation' => true,
                    'zakat' => true,
                    'qurban' => true,
                    'whatsapp' => true,
                    'custom_domain' => true,
                    'fundraiser' => true,
                    'dynamic_program' => true,
                ],
                'limits' => [
                    'max_users' => 99,
                    'max_programs' => 99,
                    'storage_mb' => 10000,
                ],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
