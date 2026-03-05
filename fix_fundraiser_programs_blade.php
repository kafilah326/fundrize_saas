<?php
$file = 'resources/views/livewire/front/fundraiser-programs.blade.php';
$content = file_get_contents($file);

$searchBadge = <<<'EOL'
                        <!-- Ujroh Badge -->
                        <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-coins text-primary text-xs"></i>
                            <span class="text-xs font-bold text-dark">
                                Ujroh: 
                                @if($program->commission_type === 'percentage')
                                    {{ (int)$program->commission_amount }}%
                                @else
                                    Rp {{ number_format($program->commission_amount, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
EOL;

$replaceBadge = <<<'EOL'
                        <!-- Ujroh Badge -->
                        <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-coins text-primary text-xs"></i>
                            <span class="text-xs font-bold text-dark">
                                Ujroh: 
                                @if($commType === 'percentage')
                                    {{ (int)$commAmount }}%
                                @else
                                    Rp {{ number_format($commAmount, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
EOL;

$content = str_replace($searchBadge, $replaceBadge, $content);
file_put_contents($file, $content);
echo "Fixed fundraiser-programs.blade.php\n";
