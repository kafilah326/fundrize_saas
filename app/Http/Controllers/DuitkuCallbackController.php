<?php

namespace App\Http\Controllers;

use App\Models\SaasTransaction;
use App\Models\MaintenanceFee;
use App\Models\Tenant;
use App\Services\DuitkuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DuitkuCallbackController extends Controller
{
    protected $duitkuService;

    public function __construct(DuitkuService $duitkuService)
    {
        $this->duitkuService = $duitkuService;
    }

    public function handle(Request $request)
    {
        $data = $request->all();
        
        Log::info('Duitku Callback Received');

        $merchantOrderId = $data['merchantOrderId'] ?? '';
        $resultCode = $data['resultCode'] ?? '';

        if (!$this->duitkuService->validateCallback($data)) {
            Log::warning('Duitku Callback: Invalid Signature', [
                'received' => $data['signature'] ?? 'N/A',
                'merchantOrderId' => $merchantOrderId
            ]);
            return response('Invalid Signature', 400);
        }
        
        $transaction = SaasTransaction::where('external_id', $merchantOrderId)->first();

        if (!$transaction) {
            Log::error('Duitku Callback: Transaction not found ' . $merchantOrderId);
            return response('Transaction not found', 404);
        }

        if ($resultCode === '00') {
            // Success
            $transaction->update([
                'status' => 'paid',
                'payment_method' => $data['paymentCode'] ?? 'Duitku',
                'paid_at' => now(),
            ]);

            $this->processSuccess($transaction);
            Log::info('Duitku Callback: Success processed for ' . $merchantOrderId);
        } else {
            // Failed/Expired
            $transaction->update(['status' => 'failed']);
            Log::info('Duitku Callback: Transaction failed/expired for ' . $merchantOrderId . ' with code ' . $resultCode);
        }

        return response('OK', 200);
    }

    /**
     * Post-processing after successful payment
     */
    protected function processSuccess(SaasTransaction $transaction)
    {
        if ($transaction->type === 'registration') {
            $tenant = Tenant::find($transaction->tenant_id);
            if ($tenant) {
                $tenant->update(['status' => 'active']);
            }
        } elseif ($transaction->type === 'maintenance') {
            $metadata = $transaction->metadata;
            if (isset($metadata['month'], $metadata['year'])) {
                MaintenanceFee::updateOrCreate(
                    ['tenant_id' => $transaction->tenant_id, 'year' => $metadata['year'], 'month' => $metadata['month']],
                    [
                        'status' => 'verified',
                        'paid_at' => now(),
                        'fee_amount' => $transaction->amount,
                    ]
                );
            }
        } elseif ($transaction->type === 'subscription_upgrade') {
            $tenant = Tenant::find($transaction->tenant_id);
            $planId = $transaction->metadata['plan_id'] ?? null;
            if ($tenant && $planId) {
                $tenant->update([
                    'plan_id' => $planId,
                ]);
            }
        } elseif ($transaction->type === 'addon_purchase') {
            $tenant = Tenant::find($transaction->tenant_id);
            $addonId = $transaction->metadata['addon_id'] ?? null;
            $addon = \App\Models\Addon::find($addonId);

            if ($tenant && $addon) {
                $expiresAt = null;
                if ($addon->duration === 'monthly') {
                    $expiresAt = now()->addMonth();
                }

                \App\Models\TenantAddon::create([
                    'tenant_id' => $tenant->id,
                    'addon_id' => $addon->id,
                    'purchased_at' => now(),
                    'expires_at' => $expiresAt,
                    'status' => 'active',
                    'amount_paid' => $transaction->amount,
                ]);
            }
        }
    }
}
