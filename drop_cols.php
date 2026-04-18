<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

Schema::table('tenant_domains', function ($table) {
    if (Schema::hasColumn('tenant_domains', 'dns_target')) {
        $table->dropColumn(['dns_target', 'dns_verified', 'last_checked_at']);
    }
});
echo "Done dropping columns\n";
