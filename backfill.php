<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- DRY RUN ---\n";
$orphanOrders = DB::select("SELECT COUNT(*) as c FROM qurban_orders WHERE status = 'paid' AND transaction_id NOT IN (SELECT external_id FROM payments)");
echo "Orphan orders: " . $orphanOrders[0]->c . "\n";

$orphanDeposits = DB::select("SELECT COUNT(*) as c FROM qurban_savings_deposits WHERE status = 'paid' AND transaction_id NOT IN (SELECT external_id FROM payments)");
echo "Orphan deposits: " . $orphanDeposits[0]->c . "\n";

$ordersToUpdate = DB::select("SELECT COUNT(*) as c FROM qurban_orders qo JOIN payments p ON p.external_id = qo.transaction_id WHERE qo.status = 'paid' AND qo.total IS NULL");
echo "QurbanOrders to backfill: " . $ordersToUpdate[0]->c . "\n";

$depositsToUpdate = DB::select("SELECT COUNT(*) as c FROM qurban_savings_deposits qsd JOIN payments p ON p.external_id = qsd.transaction_id WHERE qsd.status = 'paid' AND qsd.total IS NULL");
echo "QurbanSavingsDeposits to backfill: " . $depositsToUpdate[0]->c . "\n";

echo "\n--- EXECUTION ---\n";
$updatedOrders = DB::statement("UPDATE qurban_orders qo JOIN payments p ON p.external_id = qo.transaction_id SET qo.total = p.total WHERE qo.status = 'paid' AND qo.total IS NULL");
echo "Updated qurban_orders: done\n";

$updatedDeposits = DB::statement("UPDATE qurban_savings_deposits qsd JOIN payments p ON p.external_id = qsd.transaction_id SET qsd.total = p.total WHERE qsd.status = 'paid' AND qsd.total IS NULL");
echo "Updated qurban_savings_deposits: done\n";

