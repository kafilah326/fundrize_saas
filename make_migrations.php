<?php
$timestamp = date('Y_m_d_His');
$orderFile = "database/migrations/{$timestamp}_add_total_to_qurban_orders_table.php";
$orderContent = "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        Schema::table('qurban_orders', function (Blueprint \$table) {\n            \$table->decimal('total', 15, 2)->nullable()->after('amount');\n        });\n    }\n\n    public function down(): void\n    {\n        Schema::table('qurban_orders', function (Blueprint \$table) {\n            \$table->dropColumn('total');\n        });\n    }\n};\n";
file_put_contents($orderFile, $orderContent);
echo "Created $orderFile\n";

sleep(1);
$timestamp2 = date('Y_m_d_His');
$depoFile = "database/migrations/{$timestamp2}_add_total_to_qurban_savings_deposits_table.php";
$depoContent = "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        Schema::table('qurban_savings_deposits', function (Blueprint \$table) {\n            \$table->decimal('total', 15, 2)->nullable()->after('amount');\n        });\n    }\n\n    public function down(): void\n    {\n        Schema::table('qurban_savings_deposits', function (Blueprint \$table) {\n            \$table->dropColumn('total');\n        });\n    }\n};\n";
file_put_contents($depoFile, $depoContent);
echo "Created $depoFile\n";
