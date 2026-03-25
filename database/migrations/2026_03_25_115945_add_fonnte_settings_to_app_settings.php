<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\AppSetting;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'key' => 'fonnte_token',
                'value' => null,
                'group' => 'fonnte',
                'type' => 'encrypted',
                'label' => 'Fonnte API Token',
                'description' => 'Token API untuk Fonnte WhatsApp API',
            ],
            [
                'key' => 'wa_provider',
                'value' => 'starsender',
                'group' => 'general',
                'type' => 'text',
                'label' => 'WhatsApp Provider',
                'description' => 'Pilih provider WhatsApp (starsender / fonnte)',
            ],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down(): void
    {
        AppSetting::whereIn('key', [
            'fonnte_token',
            'wa_provider',
        ])->delete();
    }
};
