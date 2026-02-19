<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class GenerateVapidKeys extends Command
{
    protected $signature = 'webpush:generate-keys';
    protected $description = 'Generate VAPID keys for Web Push Notifications';

    public function handle()
    {
        $this->info('Generating VAPID keys...');

        try {
            $keys = VAPID::createVapidKeys();
            
            $this->saveSetting('vapid_public_key', $keys['publicKey']);
            $this->saveSetting('vapid_private_key', $keys['privateKey'], true);
            
            $this->info('VAPID keys generated and stored in app_settings.');
            $this->line('Public Key: ' . $keys['publicKey']);
        } catch (\Exception $e) {
            $this->error('Error generating keys: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }

    private function saveSetting($key, $value, $encrypt = false)
    {
        $finalValue = $encrypt ? Crypt::encryptString($value) : $value;
        $type = $encrypt ? 'encrypted' : 'text';

        // Check availability
        $exists = DB::table('app_settings')->where('key', $key)->exists();

        if ($exists) {
            DB::table('app_settings')->where('key', $key)->update([
                'value' => $finalValue,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('app_settings')->insert([
                'key' => $key,
                'value' => $finalValue,
                'type' => $type,
                'group' => 'notification',
                'label' => ucwords(str_replace('_', ' ', $key)),
                'description' => 'VAPID Key for Web Push',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
