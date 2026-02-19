<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    protected $signature = 'webpush:generate-keys {--public= : Manual Public Key} {--private= : Manual Private Key}';

    protected $description = 'Generate or Set VAPID keys for Web Push Notifications';

    public function handle()
    {
        $manualPublic = $this->option('public');
        $manualPrivate = $this->option('private');

        if ($manualPublic && $manualPrivate) {
            $this->info('Setting provided VAPID keys...');
            $keys = ['publicKey' => $manualPublic, 'privateKey' => $manualPrivate];
        } else {
            $this->info('Generating VAPID keys...');
            try {
                $keys = VAPID::createVapidKeys();
            } catch (\Exception $e) {
                $this->error('Error generating keys: '.$e->getMessage());
                $this->info('Try generating manually with: npx web-push generate-vapid-keys');
                $this->info('Then run: php artisan webpush:generate-keys --public=KEY --private=KEY');

                return;
            }
        }

        try {
            $this->saveSetting('vapid_public_key', $keys['publicKey']);
            $this->saveSetting('vapid_private_key', $keys['privateKey'], true);

            $this->info('VAPID keys stored in app_settings.');
            $this->line('Public Key: '.$keys['publicKey']);
        } catch (\Exception $e) {
            $this->error('Error saving keys: '.$e->getMessage());
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
