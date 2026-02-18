<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\AppSetting;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\Program;
use App\Models\QurbanOrder;
use App\Models\QurbanSaving;
use App\Models\QurbanSavingsDeposit;
use App\Services\MetaConversionService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    protected $waService;

    protected $metaService;

    public function __construct(WhatsAppNotificationService $waService, MetaConversionService $metaService)
    {
        $this->waService = $waService;
        $this->metaService = $metaService;
    }

    public function handleInvoice(Request $request)
    {
        // Verify token
        $verificationToken = $request->header('x-callback-token');
        if ($verificationToken !== AppSetting::get('xendit_webhook_token')) {
            Log::warning('Xendit Webhook: Invalid verification token');

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        $externalId = $data['external_id'];
        $status = $data['status']; // PENDING, PAID, SETTLED, EXPIRED

        Log::info('Xendit Webhook Received: '.$externalId.' - '.$status);

        $payment = Payment::where('external_id', $externalId)->first();

        if (! $payment) {
            Log::error('Xendit Webhook: Payment not found for external_id: '.$externalId);

            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Avoid re-processing if status is already final
        if ($payment->status === 'paid' || $payment->status === 'expired') {
            return response()->json(['message' => 'Already processed']);
        }

        switch ($status) {
            case 'PAID':
            case 'SETTLED':
                $this->handleSuccess($payment, $data);
                break;

            case 'EXPIRED':
                $this->handleExpired($payment);
                break;

            case 'FAILED':
                $payment->update(['status' => 'failed']);
                break;
        }

        return response()->json(['message' => 'Success']);
    }

    private function handleSuccess(Payment $payment, $data)
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(), // Or parse $data['paid_at']
            'payment_method' => $data['payment_method'] ?? 'Xendit',
        ]);

        // Notify admin of successful payment (triggers 2.mp3 sound)
        AdminNotification::notify(
            'payment_success',
            'Pembayaran Berhasil!',
            'Pembayaran '.$payment->external_id.' sebesar Rp '.number_format($payment->total).' telah dikonfirmasi via Xendit',
            ['payment_id' => $payment->id, 'amount' => $payment->total]
        );

        if ($payment->transaction_type === 'program') {
            $donation = Donation::where('transaction_id', $payment->external_id)->first();
            if ($donation) {
                $donation->update(['status' => 'success']);

                // Update Program Stats
                $program = Program::find($donation->program_id);
                if ($program) {
                    $totalDonation = $donation->amount + ($payment->unique_code ?? 0);
                    $program->increment('collected_amount', $totalDonation);
                    $program->increment('donor_count');
                }
            }
        } elseif ($payment->transaction_type === 'qurban_langsung') {
            QurbanOrder::where('transaction_id', $payment->external_id)
                ->update(['status' => 'paid']);

        } elseif ($payment->transaction_type === 'qurban_tabungan') {
            $deposit = QurbanSavingsDeposit::where('transaction_id', $payment->external_id)->first();
            if ($deposit) {
                $deposit->update(['status' => 'paid']);

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
            Log::error('Meta CAPI Purchase Error (Xendit): '.$e->getMessage());
        }
    }

    private function handleExpired(Payment $payment)
    {
        $payment->update([
            'status' => 'expired',
            'expired_at' => now(),
        ]);

        if ($payment->transaction_type === 'program') {
            Donation::where('transaction_id', $payment->external_id)->update(['status' => 'failed']);
        } elseif ($payment->transaction_type === 'qurban_langsung') {
            QurbanOrder::where('transaction_id', $payment->external_id)->update(['status' => 'expired']);
        } elseif ($payment->transaction_type === 'qurban_tabungan') {
            QurbanSavingsDeposit::where('transaction_id', $payment->external_id)->update(['status' => 'failed']);
        }

        $this->waService->notifyPaymentExpired($payment);
    }
}
