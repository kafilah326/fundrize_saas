<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
            PlanSeeder::class,
            CategorySeeder::class,
            TenantSeeder::class,
            SaasTransactionSeeder::class,
            ProgramSeeder::class,
            TenantTransactionSeeder::class,
            BankAccountSeeder::class,
            AppSettingSeeder::class,
            WhatsappTemplateSeeder::class,
            QurbanTabunganSettingSeeder::class,
        ]);
    }
}
