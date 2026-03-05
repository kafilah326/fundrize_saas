<?php
$file = 'app/Livewire/Admin/Qurban.php';
$content = file_get_contents($file);

// Remove properties
$content = str_replace("    public \$commission_type = 'none';\n", '', $content);
$content = str_replace("    public \$commission_amount = 0;\n", '', $content);

// Remove validation rules
$content = str_replace("        'commission_type' => 'required|in:none,fixed,percentage',\n", '', $content);
$content = str_replace("        'commission_amount' => 'nullable|numeric|min:0',\n", '', $content);

// Remove assignments
$content = str_replace("        \$this->commission_type = \$animal->commission_type ?? 'none';\n", '', $content);
$content = str_replace("        \$this->commission_amount = \$animal->commission_amount ?? 0;\n", '', $content);
$content = str_replace("            'commission_type' => \$this->commission_type,\n", '', $content);
$content = str_replace("            'commission_amount' => \$this->commission_amount ?: 0,\n", '', $content);
$content = str_replace("        \$this->commission_type = 'none';\n", '', $content);
$content = str_replace("        \$this->commission_amount = 0;\n", '', $content);

file_put_contents($file, $content);
echo "Cleaned Qurban.php\n";
