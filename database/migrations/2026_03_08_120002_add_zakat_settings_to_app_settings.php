<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'key'         => 'zakat_fitrah_price',
                'value'       => '45000',
                'group'       => 'zakat',
                'type'        => 'number',
                'label'       => 'Harga Zakat Fitrah per Jiwa',
                'description' => 'Nominal zakat fitrah untuk satu jiwa (dalam rupiah)',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'zakat_gold_price_per_gram',
                'value'       => '1500000',
                'group'       => 'zakat',
                'type'        => 'number',
                'label'       => 'Harga Emas per Gram (untuk Nisab)',
                'description' => 'Harga emas per gram dalam rupiah, digunakan untuk menghitung nisab zakat mal (85 gram emas)',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('app_settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down(): void
    {
        DB::table('app_settings')->whereIn('key', [
            'zakat_fitrah_price',
            'zakat_gold_price_per_gram',
        ])->delete();
    }
};
