<?php
$file = 'resources/views/livewire/front/my-donation.blade.php';
$content = file_get_contents($file);

// 1. Update list card display
$content = str_replace(
    '<p class="text-base font-bold text-dark">Rp {{ number_format($donation->amount, 0, \',\', \'.\') }}</p>',
    '<p class="text-base font-bold text-dark">Rp {{ number_format($donation->amount + ($donation->payment?->unique_code ?? 0), 0, \',\', \'.\') }}</p>',
    $content
);

// 2. Update modal data passing
$searchData = "                    amount: 'Rp {{ number_format(\$donation->amount, 0, ',', '.') }}',";
$replaceData = "                    amount: 'Rp {{ number_format(\$donation->amount, 0, ',', '.') }}',
                    uniqueCode: {{ \$donation->payment?->unique_code ?? 0 }},
                    total: 'Rp {{ number_format(\$donation->amount + (\$donation->payment?->unique_code ?? 0), 0, ',', '.') }}',";
$content = str_replace($searchData, $replaceData, $content);

// 3. Update the modal display section
$searchModal = '                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nominal Donasi</p>
                        <p class="text-xl font-bold text-primary" x-text="selectedDonation.amount"></p>
                    </div>';
$replaceModal = '                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nominal Donasi</p>
                        <p class="text-sm font-semibold text-dark" x-text="selectedDonation.amount"></p>
                    </div>

                    <template x-if="selectedDonation && selectedDonation.uniqueCode > 0">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Kode Unik</p>
                            <p class="text-sm font-semibold text-dark" x-text="\'Rp \' + new Intl.NumberFormat(\'id-ID\').format(selectedDonation.uniqueCode)"></p>
                        </div>
                    </template>

                    <div class="border-t border-gray-100 pt-3">
                        <p class="text-xs text-gray-500 mb-1">Total</p>
                        <p class="text-xl font-bold text-primary" x-text="selectedDonation.total"></p>
                    </div>';
$content = str_replace($searchModal, $replaceModal, $content);

file_put_contents($file, $content);
echo "Fixed my-donation.blade.php\n";
