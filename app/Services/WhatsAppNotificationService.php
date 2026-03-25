<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\FoundationSetting;
use App\Models\Payment;
use App\Models\QurbanSaving;
use App\Models\WhatsappTemplate;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected $sender;

    protected $foundationName;

    public function __construct()
    {
        $provider = \App\Models\AppSetting::get('wa_provider', 'starsender');
        if ($provider === 'fonnte') {
            $this->sender = app(\App\Services\FonnteService::class);
        } else {
            $this->sender = app(\App\Services\StarSenderService::class);
        }
        $this->foundationName = FoundationSetting::value('name') ?? 'Yayasan Peduli';
    }

    /**
     * Notify user when payment is created
     */
    public function notifyPaymentCreated(Payment $payment)
    {
        try {
            if (! $this->shouldSend($payment)) {
                return;
            }

            $message = $this->composePaymentCreatedMessage($payment);
            $this->send($payment->customer_phone, $message, 'payment_created', $payment->id);
        } catch (\Exception $e) {
            Log::error('WA Notify Payment Created Error: '.$e->getMessage());
        }
    }

    /**
     * Notify user when payment is successful
     */
    public function notifyPaymentSuccess(Payment $payment)
    {
        try {
            if (! $this->shouldSend($payment)) {
                return;
            }

            $message = $this->composePaymentSuccessMessage($payment);
            $this->send($payment->customer_phone, $message, 'payment_success', $payment->id);
        } catch (\Exception $e) {
            Log::error('WA Notify Payment Success Error: '.$e->getMessage());
        }
    }

    /**
     * Notify user when payment is expired
     */
    public function notifyPaymentExpired(Payment $payment)
    {
        try {
            if (! $this->shouldSend($payment)) {
                return;
            }

            $message = $this->composePaymentExpiredMessage($payment);
            $this->send($payment->customer_phone, $message, 'payment_expired', $payment->id);
        } catch (\Exception $e) {
            Log::error('WA Notify Payment Expired Error: '.$e->getMessage());
        }
    }

    private function shouldSend(Payment $payment): bool
    {
        if (empty($payment->customer_phone)) {
            Log::info('WA Notify: No phone number for payment '.$payment->external_id);

            return false;
        }

        // Check if enabled in settings
        if (! $this->sender->isEnabled()) {
            return false;
        }

        return true;
    }

    private function send($phone, $message, $eventType, $paymentId)
    {
        $result = $this->sender->sendMessage($phone, $message, $eventType, $paymentId);

        if (! $result['status']) {
            Log::warning("WA Notify Failed to {$phone}: ".($result['message'] ?? 'Unknown error'));
        } else {
            Log::info("WA Notify Sent to {$phone}");
        }
    }

    // ========================================================================
    // Template Type Mapping
    // ========================================================================

    /**
     * Map payment transaction_type to whatsapp_template type.
     */
    private function getTemplateType(Payment $payment): string
    {
        return match ($payment->transaction_type) {
            'program' => 'donasi',
            'qurban_langsung' => 'qurban',
            'qurban_tabungan' => 'tabungan_qurban',
            'zakat' => 'zakat',
            default => 'donasi',
        };
    }

    // ========================================================================
    // Variable Replacement
    // ========================================================================

    /**
     * Build all available variables for template replacement.
     */
    private function buildVariables(Payment $payment, string $event): array
    {
        $name = $payment->customer_name;
        $trxId = $payment->external_id;
        $amount = 'Rp '.number_format($payment->amount, 0, ',', '.');
        $total = 'Rp '.number_format($payment->total, 0, ',', '.');
        $link = route('payment.status', ['id' => $trxId]);

        // For xendit, override link with invoice URL
        if ($payment->payment_type === 'xendit' && $payment->xendit_invoice_url) {
            $link = $payment->xendit_invoice_url;
        }

        $variables = [
            'nama' => $name,
            'no_transaksi' => $trxId,
            'jumlah' => $amount,
            'total' => $total,
            'yayasan' => $this->foundationName,
            'link_pembayaran' => $link,
            'tipe_transaksi' => $this->getTransactionLabel($payment),
        ];

        // Event-specific: payment_created
        if ($event === 'payment_created') {
            $variables['batas_waktu'] = $payment->expired_at
                ? $payment->expired_at->format('d M Y H:i').' WIB'
                : '-';

            // Payment method info
            if ($payment->payment_type === 'bank_transfer') {
                $bankName = $payment->payment_method;
                $bankAccount = BankAccount::where('bank_name', $bankName)->first();

                $variables['metode_bayar'] = 'Bank Transfer ('.$bankName.')';
                $variables['kode_unik'] = (string) $payment->unique_code;

                if ($bankAccount) {
                    $variables['info_bank'] = "Bank: {$bankAccount->bank_name}\n".
                        "No. Rek: {$bankAccount->account_number}\n".
                        "A.N: {$bankAccount->account_holder_name}";
                } else {
                    $variables['info_bank'] = "Bank: {$bankName}";
                }
            } elseif ($payment->payment_type === 'xendit') {
                $variables['metode_bayar'] = 'Pembayaran Otomatis (Xendit)';
                $variables['info_bank'] = 'Pembayaran Otomatis (Xendit)';
                $variables['kode_unik'] = '-';
            } else {
                $variables['metode_bayar'] = $payment->payment_method ?? '-';
                $variables['info_bank'] = '-';
                $variables['kode_unik'] = '-';
            }
        }

        // Type-specific variables
        $checkout = $payment->checkout_data ?? [];

        if ($payment->transaction_type === 'program') {
            $variables['program'] = $checkout['program_name'] ?? 'Program Kebaikan';
        } elseif ($payment->transaction_type === 'qurban_langsung') {
            $variables['nama_qurban'] = $checkout['qurban_name'] ?? '-';
            $variables['jenis_hewan'] = $checkout['target_name'] ?? ($checkout['animal_data']['name'] ?? '-');
        } elseif ($payment->transaction_type === 'qurban_tabungan') {
            $variables['nama_tabungan'] = $checkout['qurban_name'] ?? '-';
            $variables['target_tabungan'] = 'Rp '.number_format($checkout['target_price'] ?? 0, 0, ',', '.');

            // Try to get current saving balance
            $saving = null;
            if ($payment->qurban_saving_id) {
                $saving = QurbanSaving::find($payment->qurban_saving_id);
            }

            if ($saving) {
                $variables['saldo_tabungan'] = 'Rp '.number_format($saving->saved_amount, 0, ',', '.');
                $sisa = max(0, $saving->target_amount - $saving->saved_amount);
                $variables['sisa_tabungan'] = 'Rp '.number_format($sisa, 0, ',', '.');
            } else {
                $variables['saldo_tabungan'] = '-';
                $variables['sisa_tabungan'] = '-';
            }
        } elseif ($payment->transaction_type === 'zakat') {
            $zakatType = $checkout['zakat_type'] ?? 'maal';
            $jenisZakat = $zakatType === 'fitrah' ? 'Zakat Fitrah' : 'Zakat Mal';

            if ($zakatType === 'fitrah') {
                $jumlahJiwa = $checkout['jumlah_jiwa'] ?? 1;
                $detailZakat = $jumlahJiwa . ' Jiwa';
            } else {
                $totalHarta = $checkout['total_harta'] ?? 0;
                $detailZakat = 'Harta Rp ' . number_format($totalHarta, 0, ',', '.');
            }

            $variables['jenis_zakat']  = $jenisZakat;
            $variables['detail_zakat'] = $detailZakat;
            $variables['jumlah_jiwa']  = $zakatType === 'fitrah' ? ($checkout['jumlah_jiwa'] ?? '-') : '-';
            $variables['total_harta']  = $zakatType === 'maal'
                ? 'Rp ' . number_format($checkout['total_harta'] ?? 0, 0, ',', '.')
                : '-';
        }

        return $variables;
    }

    /**
     * Replace all {{variable}} placeholders in a template string.
     */
    private function replaceVariables(string $template, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $template = str_replace('{{'.$key.'}}', $value, $template);
        }

        return $template;
    }

    // ========================================================================
    // Compose Messages (Dynamic Template with Fallback)
    // ========================================================================

    private function composePaymentCreatedMessage(Payment $payment): string
    {
        $templateType = $this->getTemplateType($payment);
        $template = WhatsappTemplate::getRandomTemplate($templateType, 'payment_created');

        if ($template) {
            $variables = $this->buildVariables($payment, 'payment_created');

            return $this->replaceVariables($template->content, $variables);
        }

        // Fallback: hardcoded message
        return $this->fallbackPaymentCreatedMessage($payment);
    }

    private function composePaymentSuccessMessage(Payment $payment): string
    {
        $templateType = $this->getTemplateType($payment);
        $template = WhatsappTemplate::getRandomTemplate($templateType, 'payment_success');

        if ($template) {
            $variables = $this->buildVariables($payment, 'payment_success');

            return $this->replaceVariables($template->content, $variables);
        }

        // Fallback: hardcoded message
        return $this->fallbackPaymentSuccessMessage($payment);
    }

    private function composePaymentExpiredMessage(Payment $payment): string
    {
        $templateType = $this->getTemplateType($payment);
        $template = WhatsappTemplate::getRandomTemplate($templateType, 'payment_expired');

        if ($template) {
            $variables = $this->buildVariables($payment, 'payment_expired');

            return $this->replaceVariables($template->content, $variables);
        }

        // Fallback: hardcoded message
        return $this->fallbackPaymentExpiredMessage($payment);
    }

    // ========================================================================
    // Fallback Messages (Original Hardcoded Messages)
    // ========================================================================

    private function fallbackPaymentCreatedMessage(Payment $payment): string
    {
        $name = $payment->customer_name ?? 'Hamba Allah';
        $trxId = $payment->external_id;
        $amount = number_format($payment->amount, 0, ',', '.');
        $total = number_format($payment->total, 0, ',', '.');
        $expiry = $payment->expired_at ? $payment->expired_at->format('d M Y H:i') : '-';
        $link = route('payment.status', ['id' => $trxId]);

        $typeLabel = $this->getTransactionLabel($payment);

        $paymentInfo = '';
        if ($payment->payment_type === 'bank_transfer') {
            $bankName = $payment->payment_method;
            $bankAccount = BankAccount::where('bank_name', $bankName)->first();

            if ($bankAccount) {
                $paymentInfo = "- Bank: {$bankAccount->bank_name}\n".
                               "- No. Rek: {$bankAccount->account_number}\n".
                               "- A.N: {$bankAccount->account_holder_name}\n".
                               "- Kode Unik: {$payment->unique_code}\n";
            } else {
                $paymentInfo = "- Bank: {$bankName}\n";
            }
        } elseif ($payment->payment_type === 'xendit') {
            $paymentInfo = "- Metode: Pembayaran Otomatis (Xendit)\n";
            if ($payment->xendit_invoice_url) {
                $link = $payment->xendit_invoice_url;
            }
        }

        return "Assalamu'alaikum, Bapak/Ibu {$name}.\n\n".
               "Terima kasih atas niat baik Anda untuk {$typeLabel}.\n\n".
               "Berikut informasi pembayaran Anda:\n".
               "- No. Transaksi: {$trxId}\n".
               "- Jumlah Donasi: Rp {$amount}\n".
               "{$paymentInfo}".
               "- *Total Transfer: Rp {$total}*\n".
               "- Batas Waktu: {$expiry} WIB\n\n".
               "Silakan lakukan pembayaran sebelum batas waktu.\n\n".
               "Pantau status transaksi di:\n".route('payment.status', ['id' => $trxId])."\n\n".
               "Jazakallahu khairan.\n{$this->foundationName}";
    }

    private function fallbackPaymentSuccessMessage(Payment $payment): string
    {
        $name = $payment->customer_name ?? 'Hamba Allah';
        $trxId = $payment->external_id;
        $amount = number_format($payment->total, 0, ',', '.');
        $link = route('payment.status', ['id' => $trxId]);

        $typeLabel = $this->getTransactionLabel($payment);
        $detail = $this->getTransactionDetail($payment);

        return "Assalamu'alaikum, Bapak/Ibu {$name}.\n\n".
               "Alhamdulillah, pembayaran Anda telah kami terima.\n\n".
               "Detail Transaksi:\n".
               "- No. Transaksi: {$trxId}\n".
               "- Tipe: {$typeLabel}\n".
               "{$detail}".
               "- Jumlah: Rp {$amount}\n".
               "- Status: *BERHASIL*\n\n".
               "Semoga menjadi amal jariyah yang diterima Allah SWT.\n\n".
               "Cek detail transaksi:\n{$link}\n\n".
               "Jazakallahu khairan.\n{$this->foundationName}";
    }

    private function fallbackPaymentExpiredMessage(Payment $payment): string
    {
        $name = $payment->customer_name ?? 'Hamba Allah';
        $trxId = $payment->external_id;
        $link = route('home');

        $typeLabel = strtolower($this->getTransactionLabel($payment));

        return "Assalamu'alaikum, Bapak/Ibu {$name}.\n\n".
               "Mohon maaf, transaksi Anda dengan nomor {$trxId} telah kedaluwarsa (expired).\n\n".
               "Jika Anda masih ingin melanjutkan {$typeLabel}, silakan buat transaksi baru melalui link berikut:\n{$link}\n\n".
               "Terima kasih.\n{$this->foundationName}";
    }

    // ========================================================================
    // Helper Methods
    // ========================================================================

    private function getTransactionLabel(Payment $payment): string
    {
        return match ($payment->transaction_type) {
            'program' => 'Donasi Program',
            'qurban_langsung' => 'Qurban Langsung',
            'qurban_tabungan' => 'Tabungan Qurban',
            'zakat' => 'Zakat',
            default => 'Donasi',
        };
    }

    private function getTransactionDetail(Payment $payment): string
    {
        if ($payment->transaction_type === 'program') {
            $programName = $payment->checkout_data['program_name'] ?? 'Program Kebaikan';

            return "- Program: {$programName}\n";
        } elseif ($payment->transaction_type === 'qurban_langsung') {
            $qurbanName = $payment->checkout_data['qurban_name'] ?? '-';

            return "- Qurban Atas Nama: {$qurbanName}\n";
        } elseif ($payment->transaction_type === 'qurban_tabungan') {
            $qurbanName = $payment->checkout_data['qurban_name'] ?? '-';

            return "- Tabungan Atas Nama: {$qurbanName}\n";
        } elseif ($payment->transaction_type === 'zakat') {
            $checkout  = $payment->checkout_data;
            $zakatType = $checkout['zakat_type'] ?? 'maal';
            if ($zakatType === 'fitrah') {
                $jiwa = $checkout['jumlah_jiwa'] ?? 1;
                return "- Detail: Zakat Fitrah — {$jiwa} Jiwa\n";
            }
            return "- Detail: Zakat Mal\n";
        }
        return '';
    }
}
