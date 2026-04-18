<?php

namespace App\Console\Commands;

use App\Models\TenantAddon;
use Illuminate\Console\Command;

class DeactivateExpiredAddons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addons:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate monthly add-ons that have expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expired = TenantAddon::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        $count = $expired->count();

        foreach ($expired as $addon) {
            $addon->update(['status' => 'expired']);
            $this->info("Deactivated add-on #{$addon->id} for tenant #{$addon->tenant_id}");
        }

        $this->info("Cleanup finished. {$count} add-ons deactivated.");
        
        return 0;
    }
}
