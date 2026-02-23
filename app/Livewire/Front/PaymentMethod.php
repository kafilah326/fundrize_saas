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
use App\Services\MetaConversionService;
use App\Services\WhatsAppNotificationService;
use App\Services\XenditService;
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

    public $paymentGroup = 'bank_transfer'; // 'bank_transfer' or 'xendit'

    public $adminFee = 0;

    public $total = 0;

    public $programName = '';

    public $xenditAvailable = false;

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

        // Check if Xendit credentials are configured
        $xenditKey = AppSetting::get('xendit_secret_key');
        $this->xenditAvailable = !empty($xenditKey);

        // Set default method
        $firstBank = BankAccount::where('is_active', true)->orderBy('sort_order')->first();
        if ($firstBank) {
            $this->selectedMethod = strtolower($firstBank->bank_name);
            $this->paymentGroup = 'bank_transfer';
        } elseif ($this->xenditAvailable) {
            $this->selectedMethod = 'xendit';
            $this->paymentGroup = 'xendit';
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

        // 3. Create Specific Transaction Record
        if ($this->type === 'program') {
            Donation::create([
                'transaction_id' => $trxId,
                'user_id' => Auth::id(),
                'program_id' => $checkout['program_id'],
                'amount' => $this->amount,
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
        } elseif ($this->type === 'qurban_langsung') {
            $order = QurbanOrder::create([
                'transaction_id' => $trxId,
                'user_id' => Auth::id(),
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
