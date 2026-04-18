<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'users', 'programs', 'categories', 'akad_types', 'donations', 'payments', 'banners', 
            'bank_accounts', 'qurban_animals', 'qurban_orders', 'qurban_savings', 'qurban_savings_deposits',
            'foundation_settings', 'app_settings', 'legal_documents', 'maintenance_fees', 'admin_notifications',
            'push_subscriptions', 'bank_followups', 'whatsapp_templates', 'whatsapp_message_logs', 
            'zakat_transactions', 'zakat_distributions', 'fundraisers', 'fundraiser_commissions', 
            'fundraiser_withdrawals', 'fundraiser_banks', 'fundraiser_visits', 'program_distributions', 
            'program_updates', 'qurban_documentations', 'qurban_tabungan_settings'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'tenant_id')) {
                        // We cannot use constrained() straightly if tenants table might not exist yet during this migration
                        // Wait, tenants table is created AFTER this migration because of timestamp!
                        // I'll just use unsignedBigInteger and foreign constraints can be added later or not at all.
                        $table->unsignedBigInteger('tenant_id')->nullable();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users', 'programs', 'categories', 'akad_types', 'donations', 'payments', 'banners', 
            'bank_accounts', 'qurban_animals', 'qurban_orders', 'qurban_savings', 'qurban_savings_deposits',
            'foundation_settings', 'app_settings', 'legal_documents', 'maintenance_fees', 'admin_notifications',
            'push_subscriptions', 'bank_followups', 'whatsapp_templates', 'whatsapp_message_logs', 
            'zakat_transactions', 'zakat_distributions', 'fundraisers', 'fundraiser_commissions', 
            'fundraiser_withdrawals', 'fundraiser_banks', 'fundraiser_visits', 'program_distributions', 
            'program_updates', 'qurban_documentations', 'qurban_tabungan_settings'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($tableName, 'tenant_id')) {
                        $table->dropForeign(['tenant_id']);
                        $table->dropColumn('tenant_id');
                    }
                });
            }
        }
    }
};
