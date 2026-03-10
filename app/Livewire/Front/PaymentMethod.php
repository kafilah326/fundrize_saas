<?php

namespace App\Livewire\Front;

use App\Models\AdminNotification;
use App\Models\AppSetting;
use App\Models\BankAccount;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\QurbanOrder;
use App\Models\QurbanSaving;
use App\Models\QurbanSavingsDeposit;
use App\Models\ZakatTransaction;
use App\Services\MetaConversionService;
use App\Services\WhatsAppNotificationService;
use App\Services\XenditService;
use App\Services\PakasirService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class PaymentMethod extends Component
{
    public $type;

    public $amount;

    public $selectedMethod;

    public $paymentGroup = 'bank_transfer'; // 'bank_transfer', 'xendit', or 'pakasir'

    public $adminFee = 0;

    public $total = 0;

    public $programName = '';

    public $activeGateway = null;

    public $xenditAvailable = false;

    public $pakasirAvailable = false;

    public function mount()
    {
        $checkout = session('checkout');

        if (! $checkout) {
            return redirect()->route('home');
        }

        $this->type = $checkout['type'];

        // Enforce login for Qurban Tabungan
        if ($this->type === 'qurban_tabungan' && ! Auth::check()) {
            return redirect()->route('login');
        }

        $this->amount = $checkout['amount'];
        $this->programName = $checkout['program_name'] ?? $checkout['target_name'] ?? 'Donasi Program';

        // Check active payment gateway
        $paymentGateway = AppSetting::get('payment_gateway', 'xendit');
        
        // Check Xendit availability
        $xenditKey = AppSetting::get('xendit_secret_key');
        if ($paymentGateway === 'xendit' && !empty($xenditKey)) {
            $this->xenditAvailable = true;
        }

        // Check Pakasir availability
        $pakasirSlug = AppSetting::get('pakasir_slug');
        $pakasirApiKey = AppSetting::get('pakasir_api_key');
        if ($paymentGateway === 'pakasir' && !empty($pakasirSlug) && !empty($pakasirApiKey)) {
            $this->pakasirAvailable = true;
        }

        // Set active gateway based on config
        if ($paymentGateway === 'xendit' && $this->xenditAvailable) {
            $this->activeGateway = 'xendit';
        } elseif ($paymentGateway === 'pakasir' && $this->pakasirAvailable) {
            $this->activeGateway = 'pakasir';
        }

        // Set default method
        $firstBank = BankAccount::where('is_active', true)->orderBy('sort_order')->first();
        if ($firstBank) {
            $this->selectedMethod = strtolower($firstBank->bank_name);
            $this->paymentGroup = 'bank_transfer';
        } elseif ($this->activeGateway) {
            $this->selectedMethod = $this->activeGateway;
            $this->paymentGroup = $this->activeGateway;
        }

        $this->calculateTotal();
    }

    public function selectBank($method)
    {
        $this->paymentGroup = 'bank_transfer';
        $this->selectedMethod = $method;
        $this->adminFee = 0; // Bank transfer gratis
        $this->calculateTotal();
    }

    public function selectXendit()
    {
        $this->paymentGroup = 'xendit';
        $this->selectedMethod = 'xendit';
        $this->adminFee = 0; // Sesuai tampilan view "Gratis"
        $this->calculateTotal();
    }

    public function selectPakasir()
    {
        $this->paymentGroup = 'pakasir';
        $this->selectedMethod = 'pakasir';
        $this->adminFee = 0; // Sesuai tampilan view "Gratis"
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = $this->amount + $this->adminFee;
    }

    private function generateUniqueCode()
    {
        // Generate 3 digit random (100-999) unused today
        $today = now()->startOfDay();
        $usedCodes = Payment::where('created_at', '>=', $today)
            ->whereNotNull('unique_code')
            ->pluck('unique_code')
            ->toArray();

        do {
            $code = rand(100, 999);
        } while (in_array($code, $usedCodes));

        return $code;
    }

    public function pay(XenditService $xenditService, WhatsAppNotificationService $waService)
    {
        $checkout = session('checkout');
        $trxId = 'TRX-'.date('YmdHis').'-'.rand(1000, 9999);
        $uniqueCode = null;
        $finalTotal = $this->total;

        // 1. Handle Unique Code for Bank Transfer
        if ($this->paymentGroup === 'bank_transfer') {
            $uniqueCode = $this->generateUniqueCode();
            $finalTotal += $uniqueCode;
        }

        // 2. Create Payment Record (Central Transaction)
        $paymentData = [
            'external_id' => $trxId,
            'transaction_type' => $this->type,
            'user_id' => Auth::id(),
            'unique_code' => $uniqueCode,
            'customer_name' => $checkout['name'] ?? 'Hamba Allah',
            'customer_email' => $checkout['email'] ?? null,
            'customer_phone' => $checkout['whatsapp'] ?? $checkout['phone'] ?? null,
            'payment_type' => $this->paymentGroup,
            'amount' => $this->amount,
            'admin_fee' => $this->adminFee,
            'total' => $finalTotal,
            'payment_method' => $this->selectedMethod,
            'status' => 'pending',
            'checkout_data' => $checkout,
            'expired_at' => now()->addHours(24),
        ];

        // Add specific references
        if ($this->type === 'program') {
            $paymentData['program_id'] = $checkout['program_id'];
        }

        $payment = Payment::create($paymentData);

        // Notify admin of new transaction (triggers 1.mp3 sound)
        AdminNotification::notify(
            'new_transaction',
            'Transaksi Baru!',
            'Transaksi '.$trxId.' sebesar Rp '.number_format($finalTotal).' dari '.($checkout['name'] ?? 'Hamba Allah'),
            ['payment_id' => $payment->id, 'amount' => $finalTotal, 'transaction_id' => $trxId]
        );

        $fundraiserId = session('referral_fundraiser_id');

        // 3. Create Specific Transaction Record
        if ($this->type === 'program') {
            $donation = Donation::create([
                'transaction_id' => $trxId,
                'user_id' => Auth::id(),
                'fundraiser_id' => $fundraiserId,
                'program_id' => $checkout['program_id'],
                'amount' => $this->amount,
                'package_quantity' => $checkout['package_quantity'] ?? null,
                'admin_fee' => $this->adminFee,
                'total' => $finalTotal,
                'donor_name' => $checkout['name'],
                'donor_phone' => $checkout['phone'],
                'donor_email' => $checkout['email'],
                'is_anonymous' => $checkout['is_anonymous'] ?? false,
                'doa' => $checkout['doa'] ?? null,
                'payment_method' => $this->selectedMethod,
                'status' => 'pending',
                'payment_expiry' => $payment->expired_at,
            ]);

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
        } elseif ($this->type === 'qurban_langsung') {
            $order = QurbanOrder::create([
                'transaction_id' => $trxId,
                'user_id' => Auth::id(),
                'fundraiser_id' => $fundraiserId,
                'qurban_animal_id' => $checkout['animal_data']['id'], // Assuming animal_data has id
                'hijri_year' => '1446', // Should be dynamic or config
                'donor_name' => $checkout['name'],
                'whatsapp' => $checkout['whatsapp'],
                'email' => $checkout['email'],
                'qurban_name' => $checkout['qurban_name'] ?? $checkout['name'],
                'address' => $checkout['address'],
                'city' => $checkout['city'],
                'postal_code' => $checkout['postal_code'],
                'slaughter_method' => $checkout['slaughter_method'],
                'delivery_method' => $checkout['delivery_method'],
                'amount' => $this->amount,
                'payment_method' => $this->selectedMethod,
                'status' => 'pending',
            ]);

            // Link payment to order
            $payment->update(['qurban_order_id' => $order->id]);

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
        } elseif ($this->type === 'qurban_tabungan') {
            $saving = null;

            // Find existing saving or create new
            if (isset($checkout['saving_id'])) {
                $saving = QurbanSaving::find($checkout['saving_id']);
            }

            if (! $saving) {
                $saving = QurbanSaving::firstOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'target_animal_type' => $checkout['target'],
                        'status' => 'active',
                    ],
                    [
                        'fundraiser_id' => $fundraiserId,
                        'target_amount' => $checkout['target_price'],
                        'saved_amount' => 0,
                        'target_hijri_year' => '1447',
                        'donor_name' => $checkout['name'],
                        'whatsapp' => $checkout['whatsapp'],
                        'qurban_name' => $checkout['qurban_name'] ?? $checkout['name'],
                        'reminder_enabled' => $checkout['reminder_enabled'] ?? false,
                        'reminder_frequency' => $checkout['reminder_frequency'] ?? 'bulanan',
                    ]
                );
            }

            // Create deposit record
            $deposit = QurbanSavingsDeposit::create([
                'qurban_saving_id' => $saving->id,
                'transaction_id' => $trxId,
                'amount' => $this->amount,
                'payment_method' => $this->selectedMethod,
                'status' => 'pending',
            ]);

            // Link payment to saving
            $payment->update(['qurban_saving_id' => $saving->id]);

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
        } elseif ($this->type === 'zakat') {
            $zakatData = [
                'transaction_id'   => $trxId,
                'user_id'          => Auth::id(),
                'zakat_type'       => $checkout['zakat_type'] ?? 'fitrah',
                'amount'           => $this->amount,
                'admin_fee'        => $this->adminFee,
                'total'            => $finalTotal,
                'donor_name'       => $checkout['name'] ?? 'Hamba Allah',
                'donor_phone'      => $checkout['phone'] ?? null,
                'donor_email'      => $checkout['email'] ?? null,
                'fundraiser_id'    => $fundraiserId,
                'payment_method'   => $this->selectedMethod,
                'status'           => 'pending',
                'payment_expiry'   => now()->addHours(24),
            ];

            // Fitrah specific
            if (($checkout['zakat_type'] ?? '') === 'fitrah') {
                $zakatData['jumlah_jiwa'] = $checkout['jumlah_jiwa'] ?? 1;
            }

            // Maal specific
            if (($checkout['zakat_type'] ?? '') === 'maal') {
                $zakatData['total_harta']      = $checkout['total_harta'] ?? null;
                $zakatData['nisab_at_time']    = $checkout['nisab_at_time'] ?? null;
                $zakatData['calculated_zakat'] = $checkout['calculated_zakat'] ?? $this->amount;
            }

            $zakatTrx = ZakatTransaction::create($zakatData);
            $payment->update(['zakat_transaction_id' => $zakatTrx->id]);

            // Calculate Commission using Program Commission Settings
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
                            'fundraiser_id'       => $fundraiserId,
                            'commissionable_type' => ZakatTransaction::class,
                            'commissionable_id'   => $zakatTrx->id,
                            'amount'              => $commissionAmount,
                            'status'              => 'pending',
                        ]);
                    }
                }
            }
        }

        // 4. Send Notification (Async if queue is setup, but synchronous for now)
        $waService->notifyPaymentCreated($payment);

        // 5. Send Meta InitiateCheckout event via Conversions API
        try {
            $metaService = app(MetaConversionService::class);
            $metaService->sendInitiateCheckout($payment);
        } catch (\Exception $e) {
            Log::error('Meta CAPI InitiateCheckout Error: '.$e->getMessage());
        }

        // 6. Handle Payment Gateway
        if ($this->paymentGroup === 'xendit') {
            try {
                $invoice = $xenditService->createInvoice([
                    'external_id' => $trxId,
                    'amount' => $finalTotal,
                    'payer_email' => $checkout['email'] ?? 'noreply@example.com',
                    'description' => $checkout['program_name'] ?? $checkout['target_name'] ?? 'Donasi',
                    'success_redirect_url' => route('payment.status', ['id' => $trxId]),
                    'failure_redirect_url' => route('payment.status', ['id' => $trxId]),
                ]);

                $payment->update([
                    'xendit_invoice_id' => $invoice['id'],
                    'xendit_invoice_url' => $invoice['invoice_url'],
                ]);

                return redirect()->away($invoice['invoice_url']);

            } catch (\Exception $e) {
                // Log error
                Log::error('Xendit Error: '.$e->getMessage());
                session()->flash('error', 'Gagal membuat pembayaran otomatis. Silakan coba lagi atau gunakan transfer bank.');

                return;
            }
        } elseif ($this->paymentGroup === 'pakasir') {
            try {
                $pakasirService = app(PakasirService::class);
                $redirectUrl = route('payment.status', ['id' => $trxId]);
                $paymentUrl = $pakasirService->getPaymentUrl($finalTotal, $trxId, $redirectUrl);

                // Store Pakasir URL in checkout_data for status page
                $checkoutData = $payment->checkout_data ?? [];
                $checkoutData['pakasir_payment_url'] = $paymentUrl;
                $payment->update(['checkout_data' => $checkoutData]);

                return redirect()->away($paymentUrl);

            } catch (\Exception $e) {
                Log::error('Pakasir Error: '.$e->getMessage());
                session()->flash('error', 'Gagal membuat pembayaran. Silakan coba lagi atau gunakan transfer bank.');

                return;
            }
        }

        // For Bank Transfer
        session(['transaction_id' => $trxId]); // Save trx_id to session for status page lookup

        return redirect()->route('payment.status', ['id' => $trxId]);
    }

    #[Layout('layouts.front')]
    #[Title('Metode Pembayaran')]
    public function render()
    {
        $bankAccounts = BankAccount::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('livewire.front.payment-method', [
            'bankAccounts' => $bankAccounts,
        ]);
    }
}
