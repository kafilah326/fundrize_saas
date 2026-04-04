<?php
$content = file_get_contents('D:/fundrize/resources/views/livewire/front/home-v2.blade.php');

// Remove gradients and replace colors
$replacements = [
    'bg-gradient-to-br from-teal-600 to-teal-500' => 'bg-primary',
    'bg-gradient-to-r from-teal-600 to-teal-500' => 'bg-primary',
    'bg-gradient-to-r from-gold-500 to-gold-400' => 'bg-secondary',
    'bg-gradient-to-br from-gold-500 to-gold-400' => 'bg-secondary',
    'bg-gradient-to-r from-navy-800 to-navy-700' => 'bg-dark',
    'bg-gradient-to-br from-navy-800 to-navy-700' => 'bg-dark',
    'bg-gradient-to-r from-red-600 to-red-500' => 'bg-red-500',
    'bg-gradient-to-r from-blue-600 to-blue-500' => 'bg-blue-500',
    'bg-gradient-to-r from-purple-600 to-purple-500' => 'bg-purple-500',
    'from-teal-600 to-teal-500' => 'bg-primary',
    'from-navy-800 to-navy-900' => 'bg-dark',
    'bg-gradient-to-br ' => '',
    'bg-gradient-to-r ' => '',
    
    // Teal to Primary
    'text-teal-700' => 'text-primary',
    'text-teal-600' => 'text-primary',
    'text-teal-500' => 'text-primary',
    'bg-teal-500' => 'bg-primary',
    'bg-teal-600' => 'bg-primary',
    'hover:bg-teal-600' => 'hover:bg-primary/90',
    'hover:bg-teal-500' => 'hover:bg-primary/90',
    'hover:text-teal-700' => 'hover:text-primary',
    'hover:text-teal-600' => 'hover:text-primary',
    'border-teal-500' => 'border-primary',
    'border-teal-100' => 'border-primary/20',
    'bg-teal-100' => 'bg-primary/10',
    'bg-teal-50' => 'bg-primary/5',
    'text-teal-50' => 'text-white/90',
    'text-teal-100' => 'text-white/80',
    
    // Navy to Dark
    'text-navy-900' => 'text-dark',
    'text-navy-800' => 'text-dark',
    'text-navy-700' => 'text-dark',
    'bg-navy-900' => 'bg-dark',
    'bg-navy-800' => 'bg-dark',
    'bg-navy-100' => 'bg-dark/10',
    'bg-navy-50' => 'bg-dark/5',
    'border-navy-100' => 'border-dark/20',
    
    // Gold to Secondary
    'text-gold-700' => 'text-secondary',
    'text-gold-500' => 'text-secondary',
    'bg-gold-500' => 'bg-secondary',
    'bg-gold-400' => 'bg-secondary',
    'bg-gold-100' => 'bg-secondary/20',
    
    // Replace text color names where gradient was
    'via-navy-900/60' => 'via-dark/60',
    'from-navy-900' => 'from-dark',
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

// Fix Layout breaking classes (which might have been restored by the revert)
$content = str_replace('max-w-7xl mx-auto', 'w-full', $content);
$content = str_replace('fixed top-0 left-0 right-0', 'sticky top-0', $content);

file_put_contents('D:/fundrize/resources/views/livewire/front/home-v2.blade.php', $content);
echo "Done";
