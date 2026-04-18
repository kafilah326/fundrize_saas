<?php

$modelsPath = __DIR__ . '/app/Models/';

$models = [
    'User.php', 'Program.php', 'Category.php', 'AkadType.php', 'Donation.php', 'Payment.php', 'Banner.php', 
    'BankAccount.php', 'QurbanAnimal.php', 'QurbanOrder.php', 'QurbanSaving.php', 'QurbanSavingsDeposit.php',
    'FoundationSetting.php', 'AppSetting.php', 'LegalDocument.php', 'MaintenanceFee.php', 'AdminNotification.php',
    'PushSubscription.php', 'BankFollowup.php', 'WhatsappTemplate.php', 'WhatsappMessageLog.php', 
    'ZakatTransaction.php', 'ZakatDistribution.php', 'Fundraiser.php', 'FundraiserCommission.php', 
    'FundraiserWithdrawal.php', 'FundraiserBank.php', 'FundraiserVisit.php', 'ProgramDistribution.php', 
    'ProgramUpdate.php', 'QurbanDocumentation.php', 'QurbanTabunganSetting.php'
];

foreach ($models as $modelFile) {
    $path = $modelsPath . $modelFile;
    if (!file_exists($path)) {
        echo "File not found: $modelFile\n";
        continue;
    }

    $content = file_get_contents($path);

    // 1. Add Trait Namespace
    if (strpos($content, 'use App\Traits\BelongsToTenant;') === false) {
        $content = preg_replace('/(namespace App\\\\Models;)/', "$1\n\nuse App\Traits\BelongsToTenant;", $content);
    }

    // Fix the faulty '// use App\Traits'
    $content = str_replace('// use App\Traits\BelongsToTenant;', 'use App\Traits\BelongsToTenant;', $content);

    // 2. Add 'use BelongsToTenant;' inside the class block
    if (strpos($content, 'use BelongsToTenant;') === false) {
        $content = preg_replace('/(class [a-zA-Z0-9_]+ extends [^{]+\{)/', "$1\n    use BelongsToTenant;\n", $content);
    }

    // 3. Add 'tenant_id' to $fillable 
    if (strpos($content, "'tenant_id'") === false && strpos($content, '"tenant_id"') === false) {
        $content = preg_replace('/(\$fillable\s*=\s*\[)/', "$1\n        'tenant_id',", $content);
    }

    file_put_contents($path, $content);
    echo "Modified: $modelFile\n";
}

echo "Done.\n";
