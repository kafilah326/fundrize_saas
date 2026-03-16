<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'starsender_api_key',
                'value' => Crypt::encryptString('66858581-c39e-4aa6-adcc-885b75657a5d'),
                'group' => 'starsender',
                'type' => 'encrypted',
                'label' => 'StarSender API Key',
                'description' => 'API Key from StarSender dashboard',
            ],
            [
                'key' => 'starsender_base_url',
                'value' => 'https://api.starsender.online/api',
                'group' => 'starsender',
                'type' => 'text',
                'label' => 'StarSender Base URL',
                'description' => 'Base URL for StarSender API',
            ],
            [
                'key' => 'starsender_enabled',
                'value' => 'true',
                'group' => 'starsender',
                'type' => 'boolean',
                'label' => 'Enable WhatsApp Notifications',
                'description' => 'Enable or disable WhatsApp notifications via StarSender',
            ],
            [
                'key' => 'xendit_mode',
                'value' => 'test',
                'group' => 'xendit',
                'type' => 'text',
                'label' => 'Xendit Mode',
                'description' => 'Mode environment Xendit: test (sandbox) atau live (production)',
            ],
            [
                'key' => 'xendit_secret_key',
                'value' => Crypt::encryptString(''), // Placeholder
                'group' => 'xendit',
                'type' => 'encrypted',
                'label' => 'Xendit Secret Key',
                'description' => 'Secret Key from Xendit dashboard',
            ],
            [
                'key' => 'xendit_webhook_token',
                'value' => Crypt::encryptString(''), // Placeholder
                'group' => 'xendit',
                'type' => 'encrypted',
                'label' => 'Xendit Webhook Token',
                'description' => 'Verification token for Xendit webhooks',
            ],
            [
                'key' => 'home_template',
                'value'       => 'default',
                'group'       => 'appearance',
                'type'        => 'text',
                'label'       => 'Template Halaman Utama',
                'description' => 'Pilih template tampilan halaman utama yang aktif. Slug harus sesuai dengan nama file home-{slug}.blade.php',
            ],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
