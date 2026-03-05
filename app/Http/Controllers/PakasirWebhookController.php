<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\Program;
use App\Models\QurbanOrder;
use App\Models\QurbanSaving;
use App\Models\QurbanSavingsDeposit;
use App\Services\MetaConversionService;
use App\Services\PakasirService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PakasirWebhookController extends Controller
{
    protected $waService;
    protected $metaService;
    protected $pakasirService;

    public function __construct(
        WhatsAppNotificationService $waService, 
        MetaConversionService $metaService,
        PakasirService $pakasirService
    ) {
        $this->waService = $waService;
        $this->metaService = $metaService;
        $this->pakasirService = $pakasirService;
    }

    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $data = $payload ?: $request->all();
        
        $orderId = $data['order_id'] ?? null;
        $amount = $data['amount'] ?? null;
        $status = $data['status'] ?? null;
        
        Log::info('Pakasir Webhook Received: ' . $orderId . ' - Status: ' . $status, ['payload' => $data]);

        if (!$orderId || !$amount) {
            Log::warning('Pakasir Webhook: Invalid payload', ['data' => $data]);
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $payment = Payment::where('external_id', $orderId)->first();

        if (!$payment) {
            Log::error('Pakasir Webhook: Payment not found for external_id: ' . $orderId);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Avoid re-processing if status is already final
        if ($payment->status === 'paid' || $payment->status === 'expired') {
            return response()->json(['message' => 'Already processed']);
        }

        // Pakasir recommends double-checking status via API
        $detail = $this->pakasirService->getTransactionDetail($amount, $orderId);

        $trxStatus = null;
        $transactionData = $data;

        if (!$detail || !isset($detail['transaction'])) {
            Log::warning('Pakasir Webhook: Verification failed via API for ' . $orderId . '. Proceeding with webhook status.');
            $trxStatus = $status;
        } else {
            $trxStatus = $detail['transaction']['status'];
            $transactionData = array_merge($transactionData, $detail['transaction']);
        }

        if ($trxStatus === 'completed') {
            try {
                $this->handleSuccess($payment, $transactionData);
            } catch (\Exception $e) {
                Log::error('Pakasir Webhook: Error handling success for ' . $orderId . ': ' . $e->getMessage());
                // Still return success to prevent webhook retries from Pakasir
            }
        }

        return response()->json(['message' => 'Success']);
    }

    private function handleSuccess(Payment $payment, $transactionData)
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(), // Or parse $transactionData['completed_at']
            'payment_method' => $transactionData['payment_method'] ?? 'Pakasir',
        ]);

        // Notify admin of successful payment (triggers 2.mp3 sound)
        AdminNotification::notify(
            'payment_success',
            'Pembayaran Berhasil!',
            'Pembayaran '.$payment->external_id.' sebesar Rp '.number_format($payment->total).' telah dikonfirmasi via Pakasir',
            ['payment_id' => $payment->id, 'amount' => $payment->total]
        );

        if ($payment->transaction_type === 'program') {
            $donation = Donation::where('transaction_id', $payment->external_id)->first();
            if ($donation) {
                $donation->update(['status' => 'success']);
                
                if ($donation->fundraiserCommission) {
                    $donation->fundraiserCommission->update(['status' => 'success']);
                }

                // Update Program Stats
                $program = Program::find($donation->program_id);
                if ($program) {
                    $totalDonation = $donation->amount + ($payment->unique_code ?? 0);
                    $program->increment('collected_amount', $totalDonation);
                    $program->increment('donor_count');
                }
            }
        } elseif ($payment->transaction_type === 'qurban_langsung') {
            $order = QurbanOrder::where('transaction_id', $payment->external_id)->first();
            if ($order) {
                $order->update(['status' => 'paid']);
                if ($order->fundraiserCommission) {
                    $order->fundraiserCommission->update(['status' => 'success']);
                }
            }

        } elseif ($payment->transaction_type === 'qurban_tabungan') {
            $deposit = QurbanSavingsDeposit::where('transaction_id', $payment->external_id)->first();
            if ($deposit) {
                $deposit->update(['status' => 'paid']);
                
                if ($deposit->fundraiserCommission) {
                    $deposit->fundraiserCommission->update(['status' => 'success']);
                }

                $saving = QurbanSaving::find($deposit->qurban_saving_id);
                if ($saving) {
                    $saving->increment('saved_amount', $deposit->amount);

                    if ($saving->saved_amount >= $saving->target_amount) {
                        $saving->update(['status' => 'completed']);
                    }
                }
            }
        }

        $this->waService->notifyPaymentSuccess($payment);

        // Send Meta Purchase event via Conversions API
        try {
            $this->metaService->sendPurchase($payment);
        } catch (\Exception $e) {
            Log::error('Meta CAPI Purchase Error (Pakasir): '.$e->getMessage());
        }
    }
}
