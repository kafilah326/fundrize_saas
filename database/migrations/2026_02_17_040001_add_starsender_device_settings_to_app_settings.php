<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\AppSetting;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'key' => 'starsender_device_id',
                'value' => null,
                'group' => 'starsender',
                'type' => 'text',
                'label' => 'StarSender Device ID',
                'description' => 'ID device yang terdaftar di StarSender',
            ],
            [
                'key' => 'starsender_device_connected_at',
                'value' => null,
                'group' => 'starsender',
                'type' => 'text',
                'label' => 'Tanggal Koneksi Device',
                'description' => 'Tanggal pertama kali device terhubung',
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
            'starsender_device_id',
            'starsender_device_connected_at',
        ])->delete();
    }
};
