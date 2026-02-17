<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            [
                'key' => 'meta_pixel_id',
                'value' => '', // Default empty
                'group' => 'meta',
                'type' => 'text',
                'label' => 'Meta Pixel ID',
                'description' => 'ID Pixel dari Meta Business Manager',
            ],
            [
                'key' => 'meta_access_token',
                'value' => Crypt::encryptString(''), // Default empty encrypted
                'group' => 'meta',
                'type' => 'encrypted',
                'label' => 'Meta Conversions API Access Token',
                'description' => 'Access Token untuk Conversions API (CAPI)',
            ],
            [
                'key' => 'meta_pixel_enabled',
                'value' => 'false',
                'group' => 'meta',
                'type' => 'boolean',
                'label' => 'Enable Meta Pixel',
                'description' => 'Aktifkan tracking Meta Pixel di browser',
            ],
            [
                'key' => 'meta_capi_enabled',
                'value' => 'false',
                'group' => 'meta',
                'type' => 'boolean',
                'label' => 'Enable Conversions API',
                'description' => 'Aktifkan server-side tracking (CAPI)',
            ],
            [
                'key' => 'meta_test_event_code',
                'value' => '',
                'group' => 'meta',
                'type' => 'text',
                'label' => 'Test Event Code',
                'description' => 'Kode unik untuk testing event di Events Manager (Opsional)',
            ],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        AppSetting::where('group', 'meta')->delete();
    }
};
