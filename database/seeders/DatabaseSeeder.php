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
            BankAccountSeeder::class,
            AppSettingSeeder::class,
            UserSeeder::class,
            FoundationSeeder::class,
            CategorySeeder::class,
            ProgramSeeder::class,
            DonationSeeder::class,
            QurbanSeeder::class,
            WhatsappTemplateSeeder::class,
        ]);
    }
}
