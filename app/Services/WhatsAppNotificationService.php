<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\FoundationSetting;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected $starSender;
    protected $foundationName;

    public function __construct(StarSenderService $starSender)
    {
        $this->starSender = $starSender;
        $this->foundationName = FoundationSetting::value('name') ?? 'Yayasan Peduli';
    }

    /**
     * Notify user when payment is created
     */
    public function notifyPaymentCreated(Payment $payment)
    {
        try {
            if (!$this->shouldSend($payment)) return;

            $message = $this->composePaymentCreatedMessage($payment);
            $this->send($payment->customer_phone, $message, 'payment_created', $payment->id);
        } catch (\Exception $e) {
            Log::error('WA Notify Payment Created Error: ' . $e->getMessage());
        }
    }

    /**
     * Notify user when payment is successful
     */
    public function notifyPaymentSuccess(Payment $payment)
    {
        try {
            if (!$this->shouldSend($payment)) return;

            $message = $this->composePaymentSuccessMessage($payment);
            $this->send($payment->customer_phone, $message, 'payment_success', $payment->id);
        } catch (\Exception $e) {
            Log::error('WA Notify Payment Success Error: ' . $e->getMessage());
        }
    }

    /**
     * Notify user when payment is expired
     */
    public function notifyPaymentExpired(Payment $payment)
    {
        try {
            if (!$this->shouldSend($payment)) return;

            $message = $this->composePaymentExpiredMessage($payment);
            $this->send($payment->customer_phone, $message, 'payment_expired', $payment->id);
        } catch (\Exception $e) {
            Log::error('WA Notify Payment Expired Error: ' . $e->getMessage());
        }
    }

    private function shouldSend(Payment $payment): bool
    {
        if (empty($payment->customer_phone)) {
            Log::info('WA Notify: No phone number for payment ' . $payment->external_id);
            return false;
        }

        // Check if enabled in settings
        if (!$this->starSender->isEnabled()) {
            return false;
        }

        return true;
    }

    private function send($phone, $message, $eventType, $paymentId)
    {
        $result = $this->starSender->sendMessage($phone, $message, $eventType, $paymentId);
        
        if (!$result['status']) {
            Log::warning("WA Notify Failed to {$phone}: " . ($result['message'] ?? 'Unknown error'));
        } else {
            Log::info("WA Notify Sent to {$phone}");
        }
    }

    private function composePaymentCreatedMessage(Payment $payment): string
    {
        $name = $payment->customer_name ?? 'Hamba Allah';
        $trxId = $payment->external_id;
        $amount = number_format($payment->amount, 0, ',', '.');
        $total = number_format($payment->total, 0, ',', '.');
        $expiry = $payment->expired_at ? $payment->expired_at->format('d M Y H:i') : '-';
        $link = route('payment.status', ['id' => $trxId]); // Using public link but routed to payment status for now
        
        $typeLabel = $this->getTransactionLabel($payment);
        
        $paymentInfo = "";
        if ($payment->payment_type === 'bank_transfer') {
            $bankName = $payment->payment_method;
            $bankAccount = BankAccount::where('bank_name', $bankName)->first();
            
            if ($bankAccount) {
                $paymentInfo = "- Bank: {$bankAccount->bank_name}\n" .
                               "- No. Rek: {$bankAccount->account_number}\n" .
                               "- A.N: {$bankAccount->account_holder_name}\n" .
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

        return "Assalamu'alaikum, Bapak/Ibu {$name}.\n\n" .
               "Terima kasih atas niat baik Anda untuk {$typeLabel}.\n\n" .
               "Berikut informasi pembayaran Anda:\n" .
               "- No. Transaksi: {$trxId}\n" .
               "- Jumlah Donasi: Rp {$amount}\n" .
               "{$paymentInfo}" .
               "- *Total Transfer: Rp {$total}*\n" .
               "- Batas Waktu: {$expiry} WIB\n\n" .
               "Silakan lakukan pembayaran sebelum batas waktu.\n\n" .
               "Pantau status transaksi di:\n" . route('payment.status', ['id' => $trxId]) . "\n\n" .
               "Jazakallahu khairan.\n{$this->foundationName}";
    }

    private function composePaymentSuccessMessage(Payment $payment): string
    {
        $name = $payment->customer_name ?? 'Hamba Allah';
        $trxId = $payment->external_id;
        $amount = number_format($payment->total, 0, ',', '.');
        $link = route('payment.status', ['id' => $trxId]);
        
        $typeLabel = $this->getTransactionLabel($payment);
        $detail = $this->getTransactionDetail($payment);

        return "Assalamu'alaikum, Bapak/Ibu {$name}.\n\n" .
               "Alhamdulillah, pembayaran Anda telah kami terima.\n\n" .
               "Detail Transaksi:\n" .
               "- No. Transaksi: {$trxId}\n" .
               "- Tipe: {$typeLabel}\n" .
               "{$detail}" .
               "- Jumlah: Rp {$amount}\n" .
               "- Status: *BERHASIL*\n\n" .
               "Semoga menjadi amal jariyah yang diterima Allah SWT.\n\n" .
               "Cek detail transaksi:\n{$link}\n\n" .
               "Jazakallahu khairan.\n{$this->foundationName}";
    }

    private function composePaymentExpiredMessage(Payment $payment): string
    {
        $name = $payment->customer_name ?? 'Hamba Allah';
        $trxId = $payment->external_id;
        $link = route('home'); 
        
        return "Assalamu'alaikum, Bapak/Ibu {$name}.\n\n" .
               "Mohon maaf, transaksi Anda dengan nomor {$trxId} telah kedaluwarsa (expired).\n\n" .
               "Jika Anda masih ingin melanjutkan donasi/qurban, silakan buat transaksi baru melalui link berikut:\n{$link}\n\n" .
               "Terima kasih.\n{$this->foundationName}";
    }

    private function getTransactionLabel(Payment $payment): string
    {
        return match ($payment->transaction_type) {
            'program' => 'Donasi Program',
            'qurban_langsung' => 'Qurban Langsung',
            'qurban_tabungan' => 'Tabungan Qurban',
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
        }
        return "";
    }
}
