<?php
$file = 'app/Livewire/Front/PaymentMethod.php';
$content = file_get_contents($file);

// Replace Program Commission block
$searchProgram = <<<'EOL'
            // Calculate Commission
            if ($fundraiserId) {
                $program = \App\Models\Program::find($checkout['program_id']);
                if ($program && $program->commission_type !== 'none') {
                    $commissionAmount = 0;
                    if ($program->commission_type === 'fixed') {
                        $commissionAmount = $program->commission_amount;
                    } elseif ($program->commission_type === 'percentage') {
                        $commissionAmount = ($this->amount * $program->commission_amount) / 100;
                    }

                    if ($commissionAmount > 0) {
                        \App\Models\FundraiserCommission::create([
                            'fundraiser_id' => $fundraiserId,
                            'commissionable_type' => \App\Models\Donation::class,
                            'commissionable_id' => $donation->id,
                            'amount' => $commissionAmount,
                            'status' => 'pending',
                        ]);
                    }
                }
            }
EOL;

$replaceProgram = <<<'EOL'
            // Calculate Commission using Global Settings
            if ($fundraiserId) {
                $commType = \App\Models\AppSetting::get('fundraiser_program_commission_type', 'none');
                $commAmountSet = \App\Models\AppSetting::get('fundraiser_program_commission_amount', 0);
                
                if ($commType !== 'none') {
                    $commissionAmount = 0;
                    if ($commType === 'fixed') {
                        $commissionAmount = $commAmountSet;
                    } elseif ($commType === 'percentage') {
                        $commissionAmount = ($this->amount * $commAmountSet) / 100;
                    }

                    if ($commissionAmount > 0) {
                        \App\Models\FundraiserCommission::create([
                            'fundraiser_id' => $fundraiserId,
                            'commissionable_type' => \App\Models\Donation::class,
                            'commissionable_id' => $donation->id,
                            'amount' => $commissionAmount,
                            'status' => 'pending',
                        ]);
                    }
                }
            }
EOL;

$content = str_replace($searchProgram, $replaceProgram, $content);


// Replace Qurban Langsung Commission block
$searchQurbanLangsung = <<<'EOL'
            // Calculate Commission
            if ($fundraiserId) {
                $animal = \App\Models\QurbanAnimal::find($checkout['animal_data']['id']);
                if ($animal && $animal->commission_type !== 'none') {
                    $commissionAmount = 0;
                    if ($animal->commission_type === 'fixed') {
                        $commissionAmount = $animal->commission_amount;
                    } elseif ($animal->commission_type === 'percentage') {
                        $commissionAmount = ($this->amount * $animal->commission_amount) / 100;
                    }

                    if ($commissionAmount > 0) {
                        \App\Models\FundraiserCommission::create([
                            'fundraiser_id' => $fundraiserId,
                            'commissionable_type' => \App\Models\QurbanOrder::class,
                            'commissionable_id' => $order->id,
                            'amount' => $commissionAmount,
                            'status' => 'pending',
                        ]);
                    }
                }
            }
EOL;

$replaceQurbanLangsung = <<<'EOL'
            // Calculate Commission using Global Settings
            if ($fundraiserId) {
                $commType = \App\Models\AppSetting::get('fundraiser_qurban_commission_type', 'none');
                $commAmountSet = \App\Models\AppSetting::get('fundraiser_qurban_commission_amount', 0);
                
                if ($commType !== 'none') {
                    $commissionAmount = 0;
                    if ($commType === 'fixed') {
                        $commissionAmount = $commAmountSet;
                    } elseif ($commType === 'percentage') {
                        $commissionAmount = ($this->amount * $commAmountSet) / 100;
                    }

                    if ($commissionAmount > 0) {
                        \App\Models\FundraiserCommission::create([
                            'fundraiser_id' => $fundraiserId,
                            'commissionable_type' => \App\Models\QurbanOrder::class,
                            'commissionable_id' => $order->id,
                            'amount' => $commissionAmount,
                            'status' => 'pending',
                        ]);
                    }
                }
            }
EOL;

$content = str_replace($searchQurbanLangsung, $replaceQurbanLangsung, $content);


// Replace Qurban Tabungan Commission block
$searchQurbanTabungan = <<<'EOL'
            // Calculate Commission
            if ($fundraiserId && isset($checkout['animal_id'])) {
                $animal = \App\Models\QurbanAnimal::find($checkout['animal_id']);
                if ($animal && $animal->commission_type !== 'none') {
                    $commissionAmount = 0;
                    if ($animal->commission_type === 'fixed') {
                        $commissionAmount = $animal->commission_amount;
                    } elseif ($animal->commission_type === 'percentage') {
                        $commissionAmount = ($this->amount * $animal->commission_amount) / 100;
                    }

                    if ($commissionAmount > 0) {
                        \App\Models\FundraiserCommission::create([
                            'fundraiser_id' => $fundraiserId,
                            'commissionable_type' => \App\Models\QurbanSavingsDeposit::class,
                            'commissionable_id' => $deposit->id,
                            'amount' => $commissionAmount,
                            'status' => 'pending',
                        ]);
                    }
                }
            }
EOL;

$replaceQurbanTabungan = <<<'EOL'
            // Calculate Commission using Global Settings
            if ($fundraiserId) {
                $commType = \App\Models\AppSetting::get('fundraiser_qurban_commission_type', 'none');
                $commAmountSet = \App\Models\AppSetting::get('fundraiser_qurban_commission_amount', 0);
                
                if ($commType !== 'none') {
                    $commissionAmount = 0;
                    if ($commType === 'fixed') {
                        $commissionAmount = $commAmountSet;
                    } elseif ($commType === 'percentage') {
                        $commissionAmount = ($this->amount * $commAmountSet) / 100;
                    }

                    if ($commissionAmount > 0) {
                        \App\Models\FundraiserCommission::create([
                            'fundraiser_id' => $fundraiserId,
                            'commissionable_type' => \App\Models\QurbanSavingsDeposit::class,
                            'commissionable_id' => $deposit->id,
                            'amount' => $commissionAmount,
                            'status' => 'pending',
                        ]);
                    }
                }
            }
EOL;

$content = str_replace($searchQurbanTabungan, $replaceQurbanTabungan, $content);

file_put_contents($file, $content);
echo "Fixed PaymentMethod.php\n";
