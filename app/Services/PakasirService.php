<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PakasirService
{
    protected $slug;
    protected $apiKey;
    protected $mode;

    public function __construct()
    {
        $this->slug = AppSetting::get('pakasir_slug');
        $this->apiKey = AppSetting::get('pakasir_api_key');
        $this->mode = AppSetting::get('pakasir_mode', 'sandbox');
    }

    /**
     * Check if Pakasir credentials are configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->slug) && !empty($this->apiKey);
    }

    /**
     * Get current Pakasir mode (sandbox/live).
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Get payment URL for redirect integration.
     */
    public function getPaymentUrl(int|float $amount, string $orderId, ?string $redirectUrl = null): string
    {
        // Pakasir amount format: no dots, no spaces
        $formattedAmount = round($amount);
        
        $url = "https://app.pakasir.com/pay/{$this->slug}/{$formattedAmount}?order_id={$orderId}";
        
        if ($redirectUrl) {
            $url .= "&redirect=" . urlencode($redirectUrl);
        }

        Log::info("Pakasir [{$this->mode}] Generating URL for order: {$orderId}");

        return $url;
    }

    /**
     * Verify transaction status via API
     */
    public function getTransactionDetail(int|float $amount, string $orderId): ?array
    {
        if (!$this->isConfigured()) {
            Log::warning('Pakasir is not configured.');
            return null;
        }

        $formattedAmount = round($amount);
        
        $response = Http::get("https://app.pakasir.com/api/transactiondetail", [
            'project' => $this->slug,
            'amount' => $formattedAmount,
            'order_id' => $orderId,
            'api_key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Pakasir Transaction Detail Error: ' . $response->body());
        return null;
    }

    /**
     * Create a transaction via API (C.2)
     * 
     * @param string $method Payment method: qris, bni_va, bri_va, cimb_niaga_va, sampoerna_va, bnc_va, maybank_va, permata_va, atm_bersama_va, artha_graha_va, paypal
     * @param int|float $amount Transaction amount
     * @param string $orderId Order/Invoice ID
     * @return array|null Response with payment details (payment_number, total_payment, expired_at, etc.)
     */
    public function createTransaction(string $method, int|float $amount, string $orderId): ?array
    {
        if (!$this->isConfigured()) {
            Log::warning('Pakasir is not configured.');
            return null;
        }

        $formattedAmount = round($amount);

        Log::info("Pakasir [{$this->mode}] Creating transaction: {$orderId} via {$method}");

        $response = Http::post("https://app.pakasir.com/api/transactioncreate/{$method}", [
            'project' => $this->slug,
            'order_id' => $orderId,
            'amount' => $formattedAmount,
            'api_key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Pakasir Transaction Create Error: ' . $response->body());
        return null;
    }

    /**
     * Simulate payment for sandbox testing (C.4)
     * Only works when project is in Sandbox mode.
     * 
     * @param int|float $amount Transaction amount
     * @param string $orderId Order/Invoice ID
     * @return array|null
     */
    public function simulatePayment(int|float $amount, string $orderId): ?array
    {
        if (!$this->isConfigured()) {
            Log::warning('Pakasir is not configured.');
            return null;
        }

        if ($this->mode !== 'sandbox') {
            Log::warning('Pakasir Payment Simulation only available in sandbox mode.');
            return null;
        }

        $formattedAmount = round($amount);

        Log::info("Pakasir [sandbox] Simulating payment for order: {$orderId}");

        $response = Http::post("https://app.pakasir.com/api/paymentsimulation", [
            'project' => $this->slug,
            'order_id' => $orderId,
            'amount' => $formattedAmount,
            'api_key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Pakasir Payment Simulation Error: ' . $response->body());
        return null;
    }

    /**
     * Cancel a transaction (C.5)
     * 
     * @param int|float $amount Transaction amount
     * @param string $orderId Order/Invoice ID
     * @return array|null
     */
    public function cancelTransaction(int|float $amount, string $orderId): ?array
    {
        if (!$this->isConfigured()) {
            Log::warning('Pakasir is not configured.');
            return null;
        }

        $formattedAmount = round($amount);

        Log::info("Pakasir [{$this->mode}] Cancelling transaction: {$orderId}");

        $response = Http::post("https://app.pakasir.com/api/transactioncancel", [
            'project' => $this->slug,
            'order_id' => $orderId,
            'amount' => $formattedAmount,
            'api_key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Pakasir Transaction Cancel Error: ' . $response->body());
        return null;
    }
}
